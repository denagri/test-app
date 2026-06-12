<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Address;
use App\Http\Requests\ListingRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword =$request->query('keyword');
        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query = Product::whereHas('usersWhoLiked', function($q) {
                $q->where('user_id', Auth::id()); })->with('purchase');
                if(!empty($keyword)){
                    $query->where('products.name','like','%'.$keyword.'%');
                }
                $products =$query->get();
            } else {
                $products = collect();
            }
        } else {
            $query = Product::with('purchase');
            if(Auth::check()){
                $query->where('sell_user_id','!=',Auth::id());
            }
            if(!empty($keyword)){
                $query->where('name','like','%'.$keyword.'%');
            }
            $products = $query->get();
        }
        return view('index', compact('products', 'tab','keyword'));
    }
    public function showMypage(request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab','sell');
        if($tab === 'buy'){
            $products = $user->purchasedProducts;
        }else{
            $products = Product::where('sell_user_id',$user->id)->get();
        }
        return view('profile',compact('user','products','tab'));
    }
    public function showListing()
    {
        $product = new Product();
        $categories = Category::all();
        $conditions = Condition::all();
        return view('listing',compact('product','categories','conditions'));
    }
    public function store(ListingRequest $request)
    {
        $imagePath = $request->file('image')->store('products', 'public');
        $product = Product::create([
        'sell_user_id' => Auth::id(),
        'condition_id' => $request->condition_id,
        'name' => $request->name,
        'brand' => $request->brand,
        'explanation' => $request->explanation,
        'price' => $request->price,
        'image_path' => $imagePath,
        ]);
        if($request->has('category_ids')){
            $product->categories()->sync($request->category_ids);
        }
        return redirect()->route('index');
    }
    private function getSearchQuery($request,$query)
    {
        if(!empty($request->keyword)){
            $query->where(function($q)use($request){
                $q->where('name','like','%'.$request->keyword .'%');
            });
        }
        if(Auth::check()){
            $query->where('sell_user_id','!=',Auth::id());
        }
        return $query;
    }
    public function search(Request $request)
    {
        return $this->index($request);
    }
    public function show($id)
    {
        $product = Product::withCount('likes')->findOrFail($id);
        return view('item_detail',compact('product'));
    }
    public function editAddress($item_id)
    {
        return view('address',['item_id' =>$item_id]);
    }
    public function updateAddress(Request $request,$item_id)
    {
        $newAddress =Address::create([
            'code' =>$request->code,
            'address' =>$request->address,
            'building' =>$request->building,
        ]);
        session(['shipping_address_'.$item_id =>$newAddress->id]);
        return redirect()->route('purchase',['item_id'=>$item_id]);
    }
}
