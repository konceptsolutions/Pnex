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

                //--------------------------Storing user in autonets according to his own bv-----------------------------
                Product::storeAutonetInfo($parent1, $req->product_id, $sessionUserId, 40, $bv, $week_id, $autonets);

                $parent2 = User::find($parent1->reference_id);
                if ($parent2) {

                    //--------------------------Storing user in autonets according to his own bv-----------------------------
                    Product::storeAutonetInfo($parent2, $req->product_id, $sessionUserId, 5, $bv, $week_id, $autonets);

                    $parent3 = User::find($parent2->reference_id);
                    if ($parent3) {

                        //--------------------------Storing user in autonets according to his own bv-----------------------------
                        Product::storeAutonetInfo($parent3, $req->product_id, $sessionUserId, 5, $bv, $week_id, $autonets);
                    }
                }
            }
        });

        return redirect('getProducts')->with(['status' => 'success', 'message' => 'Product purchased successfully']);
    }
}
