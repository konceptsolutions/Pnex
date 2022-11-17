<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $products = Product::where('is_sold',0)->get();
        return view('products.products',['products'=>$products]);

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
            return redirect('addProduct')->withInput()->with(['status'=>'danger','message'=>$validator->errors()->first()]);
        }
        try{
            $product = new Product();
            $product->title = $request->title;
            $product->product_type_id = $request->product_type_id;
            $product->product_sub_type_id = $request->product_sub_type_id;
            $product->bv = $request->bv;
            $product->description = $request->description;
            $product->save();
            return redirect('products')->with(['status'=>'success','message'=>'Product stored successfully']);
        }catch(Exception $e){
            return redirect('addProduct')->withInput()->with(['status'=>'danger','message'=>$e->getMessage()]);
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
        $product = Product::where([['id',$req->product_id],['is_sold',0]]);
        if (!$product) {
            return back()->with(['status'=>'danger','message'=>'Product not found']);
        }

        DB::transaction(function () use($req, $product) {
            $bv = $product->bv;
            $product->is_sold = 1;
            $product->save();

            $getWeek = Week::where('is_distributed',0)->orderBy('id','desc')->first();
            if (!$getWeek) {
                $oldWeekNo = Week::orderBy('id','desc')->first();
                $week = new Week;
                $week->week_no = $oldWeekNo ? $oldWeekNo->week_no + 1 : 1;
                $week->save();
                $week_id = $week->id;
            }else{
                $week_id = $getWeek->id;
            }
            $sessionUserId = Session::get('user_id');

            //---------------------updating user status from free ton paid user------------------
            $user = User::find($sessionUserId);
            $reference_id = $user->reference_id;
            $user->is_free_user = 0;
            $user->save();

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
                $parent1Ledger->bv = $bv;
                $parent1Ledger->week_id = $week_id;
                $parent1Ledger->save();

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
                        $parent2Ledger->bv = $bv;
                        $parent2Ledger->week_id = $week_id;
                        $parent2Ledger->save();
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
                        $parent3Ledger->bv = $bv;
                        $parent3Ledger->week_id = $week_id;
                        $parent3Ledger->save();
                    }
                    $parent3 = User::find($parent3->reference_id);
                }

            }
        });

    }
}
