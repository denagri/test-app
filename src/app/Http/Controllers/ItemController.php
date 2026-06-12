<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Purchase;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\StripeClient;


class ItemController extends Controller
{
    public function show($product_id)
    {
        $product = Product::with(['condition','categories','comments.user'])
        ->withCount('comments')
        ->withCount('likes')
        ->findOrFail($product_id);
        return view('detail' , compact('product'));
    }
    public function showPurchase($item_id)
    {
        $product = Product::with(['condition','categories'])->findOrFail($item_id);
        $payments = Payment::all();
        $user = Auth::user()->load('address');
        return view('purchase',compact('product','payments'));
    }
    public function toggleLike($product_id)
    {
        $user_id = Auth::id();
        $like = Like::where('user_id',$user_id)->where('product_id',$product_id)->first();
        if($like){
            $like->delete();
        }else{
            Like::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ]);
        }
        return back();
    }
    public function storeComment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $item_id,
            'content' => $request->content,
        ]);
        return back();
    }
    public function purchase(PurchaseRequest $request,$item_id)
    {
        $product=Product::findOrFail($item_id);
        $sessionKey ='shipping_address_'.$item_id;
        $addressId =session($sessionKey,Auth::user()->address_id);
        $payment =Payment::findOrFail($request->payment_id);
        $paymentTypes =['card'];
        if($payment->method ==='カード払い'){
            $paymentTypes =['card'];
        }elseif($payment->method ==='コンビニ払い'){
            $paymentTypes =['konbini'];
        }
        $stripe =new StripeClient(config('services.stripe.secret'));
        try{
            $session =$stripe->checkout->sessions->create([
                'payment_method_types'=>$paymentTypes,
                'customer_email'=>Auth::user()->email,
                'line_items' =>[[
                    'price_data'=>[
                        'currency'=>'jpy',
                        'product_data'=>[
                            'name'=>$product->name,
                        ],
                        'unit_amount'=>$product->price,
                    ],
                    'quantity'=>1,
                ]],
                'mode'=>'payment',
                'success_url'=>route('index'),
                'cancel_url'=>route('purchase',['item_id'=>$item_id]),
            ]);
            Purchase::create([
                'user_id' =>Auth::id(),
                'product_id' =>$item_id,
                'payment' =>$request->payment_id,
                'address_id' =>$addressId,
            ]);
            session()->forget($sessionKey);
            return redirect()->away($session->url);
        }catch (\Stripe\Exception\ApiErrorException $e){
        \Log::error('Stripe決済エラー:'.$e->getMessage());
        return back()->withErrors(['payment_id' =>'決済処理の初期化に失敗しました']);
        }
    }
}