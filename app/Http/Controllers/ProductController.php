<?php

namespace App\Http\Controllers;

use App\Models\Autonet;
use App\Models\AutonetCollection;
use App\Models\AutonetUser;
use App\Models\Product;
use App\Models\PurchasedProduct;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Week;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('is_sold', 0)->get();
        return view('products.products', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.add_product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'title' => 'required|max:255',
            'product_type_id' => 'required|int',
            'product_sub_type_id' => 'required|int',
            'bv' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('addProduct')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            $product = new Product();
            $product->title = $request->title;
            $product->product_type_id = $request->product_type_id;
            $product->product_sub_type_id = $request->product_sub_type_id;
            $product->bv = $request->bv;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->save();
            return redirect('getProducts')->with(['status' => 'success', 'message' => 'Product stored successfully']);
        } catch (Exception $e) {
            return redirect('addProduct')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Buying product
     *
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function buyProduct(Request $req)
    {
        $product = Product::where([['id', $req->product_id], ['is_sold', 0]])->first();
        if (!$product) {
            return back()->with(['status' => 'danger', 'message' => 'Product not found']);
        }

        DB::transaction(function () use ($req, $product) {
            $bv = $product->bv;
            $product->is_sold = 1;
            $product->save();

            $getWeek = Week::where('is_distributed', 0)->orderBy('id', 'desc')->first();

            //---------------------------------Adding week if not exist------------------
            if (!$getWeek) {
                $oldWeekNo = Week::orderBy('id', 'desc')->first();
                $week = new Week;
                $week->week_no = $oldWeekNo ? $oldWeekNo->week_no + 1 : 1;
                $week->save();
                $week_id = $week->id;
            } else {
                $week_id = $getWeek->id;
            }
            $sessionUserId = Session::get('user_id');

            //----------------------------inserting product record----------------------
            $purchasedProduct = new PurchasedProduct();
            $purchasedProduct->user_id = $sessionUserId;
            $purchasedProduct->product_id = $req->product_id;
            $purchasedProduct->week_id = $week_id;
            $purchasedProduct->save();

            //---------------------updating user status from free ton paid user------------------
            $user = User::find($sessionUserId);
            $reference_id = $user->reference_id;
            $user->is_free_user = 0;
            $user->save();

            //-------------------------------Inserting specific percentage in autonets-------------------

            $autonets = Autonet::get();
            foreach ($autonets as $autonet) {
                $autonetCollection = new AutonetCollection();
                $autonetCollection->week_id = $week_id;
                $autonetCollection->product_id = $req->product_id;
                $autonetCollection->autonet_id = $autonet->id;
                $autonetCollection->bv = $bv * $autonet->percentage / 100;
                $autonetCollection->save();
            }
            //---------------------Distributing comission in network------------------

            $parent1 = User::find($reference_id);
            if ($parent1) {
                $parent1Ledger = new UserLedger;
                $parent1Ledger->user_id = $parent1->id;
                $parent1Ledger->product_id = $req->product_id;
                $parent1Ledger->network_user_id = $sessionUserId;
                $parent1Ledger->debit = 0;
                $parent1Ledger->credit = 0;
                $parent1Ledger->balance = 0;
                $parent1Ledger->bv = $bv * 40 / 100;
                $parent1Ledger->week_id = $week_id;
                $parent1Ledger->save();
                $parent1Bv = UserLedger::where([['week_id', $week_id], ['user_id', $parent1->id]])->sum('bv');

                //----------------------------------if user reached  to autonet points-----------------
                $autonetUser = AutonetUser::where([['user_id', $parent1->id], ['week_id', $week_id]])->first();
                if ($parent1Bv >= $autonets[0]->bv  && $parent1Bv < $autonets[1]->bv) {
                    if (!$autonetUser) {
                        $AutonetUser = new AutonetUser;
                        $AutonetUser->week_id = $week_id;
                        $AutonetUser->user_id = $parent1->id;
                        $AutonetUser->autonet_id = $autonets[0]->id;
                        $AutonetUser->save();
                    }
                } elseif ($parent1Bv >= $autonets[1]->bv  && $parent1Bv < $autonets[2]->bv) {
                    if ($parent1->is_free_user == 0) {
                        if (!$autonetUser) {
                            $AutonetUser = new AutonetUser;
                            $AutonetUser->week_id = $week_id;
                            $AutonetUser->user_id = $parent1->id;
                            $AutonetUser->autonet_id = $autonets[1]->id;
                            $AutonetUser->save();
                        } else {
                            $autonetUser->autonet_id = $autonets[1]->id;
                            $autonetUser->save();
                        }
                    }
                } elseif ($parent1Bv >= $autonets[2]->bv  && $parent1Bv < $autonets[0]->bv +  $autonets[2]->bv) {
                    if ($parent1->is_free_user == 0) {
                        if (!$autonetUser) {
                            $AutonetUser = new AutonetUser;
                            $AutonetUser->week_id = $week_id;
                            $AutonetUser->user_id = $parent1->id;
                            $AutonetUser->autonet_id = $autonets[2]->id;
                            $AutonetUser->save();
                        } else {
                            $autonetUser->autonet_id = $autonets[2]->id;
                            $autonetUser->save();
                        }
                    }
                }
                // elseif ($parent1Bv >= $autonets[0]->bv +  $autonets[2]->bv  && $parent1Bv < $autonets[0]->bv + $autonets[1]->bv +  $autonets[2]->bv) {
                //     # code...
                // }elseif ($parent1Bv >= $autonets[0]->bv + $autonets[1]->bv +  $autonets[2]->bv  && $parent1Bv < $autonets[0]->bv +  $autonets[2]->bv) {
                //     # code...
                // }

                $parent2 = User::find($parent1->reference_id);
                if ($parent2) {
                    if ($parent2->is_free_user == 0) {
                        $parent2Ledger = new UserLedger;
                        $parent2Ledger->user_id = $parent2->id;
                        $parent2Ledger->product_id = $req->product_id;
                        $parent2Ledger->network_user_id = $sessionUserId;
                        $parent2Ledger->debit = 0;
                        $parent2Ledger->credit = 0;
                        $parent2Ledger->balance = 0;
                        $parent2Ledger->bv = $bv * 5 / 100;
                        $parent2Ledger->week_id = $week_id;
                        $parent2Ledger->save();
                    }

                    $parent2Bv = UserLedger::where([['week_id', $week_id], ['user_id', $parent2->id]])->sum('bv');

                    //----------------------------------if user reached  to autonet points-----------------
                    $autonetUser = AutonetUser::where([['user_id', $parent2->id], ['week_id', $week_id]])->first();
                    if ($parent2Bv >= $autonets[0]->bv  && $parent2Bv < $autonets[1]->bv) {
                        if (!$autonetUser) {
                            $AutonetUser = new AutonetUser;
                            $AutonetUser->week_id = $week_id;
                            $AutonetUser->user_id = $parent2->id;
                            $AutonetUser->autonet_id = $autonets[0]->id;
                            $AutonetUser->save();
                        }
                    } elseif ($parent2Bv >= $autonets[1]->bv  && $parent2Bv < $autonets[2]->bv) {
                        if ($parent2->is_free_user == 0) {
                            if (!$autonetUser) {
                                $AutonetUser = new AutonetUser;
                                $AutonetUser->week_id = $week_id;
                                $AutonetUser->user_id = $parent2->id;
                                $AutonetUser->autonet_id = $autonets[1]->id;
                                $AutonetUser->save();
                            } else {
                                $autonetUser->autonet_id = $autonets[1]->id;
                                $autonetUser->save();
                            }
                        }
                    } elseif ($parent2Bv >= $autonets[2]->bv  && $parent2Bv < $autonets[0]->bv +  $autonets[2]->bv) {
                        if ($parent2->is_free_user == 0) {
                            if (!$autonetUser) {
                                $AutonetUser = new AutonetUser;
                                $AutonetUser->week_id = $week_id;
                                $AutonetUser->user_id = $parent2->id;
                                $AutonetUser->autonet_id = $autonets[2]->id;
                                $AutonetUser->save();
                            } else {
                                $autonetUser->autonet_id = $autonets[2]->id;
                                $autonetUser->save();
                            }
                        }
                    }
                    $parent3 = User::find($parent2->reference_id);
                }
                if ($parent3) {
                    if ($parent3->is_free_user == 0) {
                        $parent3Ledger = new UserLedger;
                        $parent3Ledger->user_id = $parent3->id;
                        $parent3Ledger->product_id = $req->product_id;
                        $parent3Ledger->network_user_id = $sessionUserId;
                        $parent3Ledger->debit = 0;
                        $parent3Ledger->credit = 0;
                        $parent3Ledger->balance = 0;
                        $parent3Ledger->bv = $bv * 5 / 100;
                        $parent3Ledger->week_id = $week_id;
                        $parent3Ledger->save();
                    }
                    $parent3Bv = UserLedger::where([['week_id', $week_id], ['user_id', $parent3->id]])->sum('bv');

                    //----------------------------------if user reached  to autonet points-----------------
                    $autonetUser = AutonetUser::where([['user_id', $parent3->id], ['week_id', $week_id]])->first();
                    if ($parent3Bv >= $autonets[0]->bv  && $parent3Bv < $autonets[1]->bv) {
                        if (!$autonetUser) {
                            $AutonetUser = new AutonetUser;
                            $AutonetUser->week_id = $week_id;
                            $AutonetUser->user_id = $parent3->id;
                            $AutonetUser->autonet_id = $autonets[0]->id;
                            $AutonetUser->save();
                        }
                    } elseif ($parent3Bv >= $autonets[1]->bv  && $parent3Bv < $autonets[2]->bv) {
                        if ($parent3->is_free_user == 0) {
                            if (!$autonetUser) {
                                $AutonetUser = new AutonetUser;
                                $AutonetUser->week_id = $week_id;
                                $AutonetUser->user_id = $parent3->id;
                                $AutonetUser->autonet_id = $autonets[1]->id;
                                $AutonetUser->save();
                            } else {
                                $autonetUser->autonet_id = $autonets[1]->id;
                                $autonetUser->save();
                            }
                        }
                    } elseif ($parent3Bv >= $autonets[2]->bv  && $parent3Bv < $autonets[0]->bv +  $autonets[2]->bv) {
                        if ($parent3->is_free_user == 0) {
                            if (!$autonetUser) {
                                $AutonetUser = new AutonetUser;
                                $AutonetUser->week_id = $week_id;
                                $AutonetUser->user_id = $parent3->id;
                                $AutonetUser->autonet_id = $autonets[2]->id;
                                $AutonetUser->save();
                            } else {
                                $autonetUser->autonet_id = $autonets[2]->id;
                                $autonetUser->save();
                            }
                        }
                    }
                }
            }
        });

        return redirect('getProducts')->with(['status' => 'success', 'message' => 'Product purchased successfully']);
    }
}
