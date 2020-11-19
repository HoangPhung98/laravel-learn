<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    //
    public function getHomePage(){
        $slides = Slide::all();
        $new_products = Product::where('new',1)->paginate(4);
        $promotion_products = Product::where('promotion_price','<>',0)->paginate(8);
        return view('page\homepage',['slides'=>$slides, 'new_products'=>$new_products, 'promotion_products'=>$promotion_products]);
    }

    public function getProductType($type){
        $products_of_type = Product::where('id_type', $type)->get();
        return view('page\producttype',['products_of_type'=>$products_of_type]);
    }

    public function getProduct($id){
        $product = Product::where('id', $id)->first();
        $related_products = Product::where('id_type', $product->id_type)->paginate(3);
        // var_dump($product);
        return view('page\product', ['product'=>$product, 'related_products'=>$related_products]);
    }

    public function getContact(){
        return view('page\contact');
    }

    public function addToCart(Request $req, $id){
        $product = Product::find($id);
        $oldCart = $req->session()->has('cart')?$req->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $req->session()->put('cart', $cart);
        return redirect()->back();
    }

    public function deleteItemFromCart(Request $req, $id){
        $oldCart = $req->session()->has('cart')?$req->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        $req->session()->put('cart', $cart);
        return redirect()->back();
    }

    public function checkout(){
        $oldCart = session()->has('cart')?session()->get('cart'):null;
        $cart = new Cart($oldCart);
        return view('page\checkout',['cart'=>$cart]);
    }

    public function chotdon(Request $req){
        $customer = new Customer();
        $customer->name = $req->name;
        $customer->gender = 'bede';
        $customer->email = $req->email;
        $customer->address = $req->address;
        $customer->phone_number = $req->phone_number;
        $customer->note = $req->notes;

        $customer->save();

        $cart = session('cart');

        $bill = new Bill();
        $bill->id_customer = $customer->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $req->payment_method;
        $bill->note = $req->notes;
        $bill->save();

        foreach ($cart->items as $item) {
            $billDetail = new BillDetail();
            $billDetail->id_bill = $bill->id;
            $billDetail->id_product = $item['item']->id;
            $billDetail->quantity = $item['qty'];
            $billDetail->unit_price = $item['item']->unit_price;

            $billDetail->save();
        }

        session()->forget('cart');
        return redirect('/index');
    }
}
