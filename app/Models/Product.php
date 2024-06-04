<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Product extends Model
{

    public function getProductAll()
    {
        $result = DB::table('products')->get();;
        return $result;
    }
    public function getProductType($request)
    {
        $result = DB::table('products')->where('type', '=', $request->typeProduct)->get();
        return $result;
    }
    public function getProductOne($request)
    {
        $result = DB::table('products')->where('id', '=', $request->id)->first();
        return $result;
    }
    public function getCart($request)
    {
        $result = DB::table('cart')->where('idUser', '=', $request->idUser)->get();
        return $result;
    }
    public function addCart($request)
    {
        $result = DB::table('cart')->insert([
            'idProduct' => $request->id,
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'idUser' => $request->idUser,
        ]);
        return $result;
    }
    public function checkCart($request)
    {
        $result = DB::table('cart')->where('idProduct', '=', $request->id)->where('idUser', '=', $request->idUser)->first();
        return $result;
    }
    public function deleteCart($request)
    {
        $result = DB::table('cart')->where('idProduct', '=', $request->idProduct)->where('idUser', '=', $request->idUser)->delete();
        // $result->delete();
        return $result;
    }
    public function updateCart($request)
    {
        $price = DB::table('products')->where('id', '=', $request->id)->first(['products.currentPrice']);
        $quantity = DB::table('cart')->where('idProduct', '=', $request->id)->where('idUser', '=', $request->idUser)->first(['cart.quantity']);
        $resultQ = DB::table('cart')->where('idProduct', '=', $request->id)->where('idUser', '=', $request->idUser)->update(['quantity' => $request->type == '+' ? $request->quantity + $quantity->quantity : ($quantity->quantity > 1 ? $quantity->quantity - 1 : 1)]);
        $quantity2 = DB::table('cart')->where('idProduct', '=', $request->id)->where('idUser', '=', $request->idUser)->first(['cart.quantity']);

        $resultP = DB::table('cart')->where('idProduct', '=', $request->id)->where('idUser', '=', $request->idUser)->update(['price' => $price->currentPrice * $quantity2->quantity]);
        $result = DB::table('cart')->where('idProduct', '=', $request->id)->where('idUser', '=', $request->idUser)->first(['cart.quantity', 'cart.price', 'cart.idProduct']);

        return $result;
    }
    public function addProduct($request)
    {
        $request->validate(['image' => 'required|mimes:jpg,png,jpeg,webp|max:5000']);
        $file = $request->image;
        $image =  $file->getClientOriginalName();
        $file->move(public_path('assets/upLoad'), $image);

        $result =  DB::table('products')->insert([
            'name' => $request->name,
            'priceBefore' => $request->priceBefore,
            'currentPrice' => $request->currentPrice,
            'type' => $request->type,
            'image' => $image,
            'inventoryNumber' => $request->inventory,
            'sale' => $request->sale,
            'description' => $request->description
        ]);
        return $result;
    }
    public function upDateProduct($request)
    {
        if (!empty($request->image)) {
            $request->validate(['image' => 'required|mimes:jpg,png,jpeg,webp|max:5000']);
            $file = $request->image;
            $image =  $file->getClientOriginalName();
            $file->move(public_path('assets/upLoad'), $image);

            DB::table('products')->where('id', '=', $request->id)->update(['image' => $image]);
        }


        $resultP = DB::table('products')->where('id', '=', $request->id)->update(['name' => $request->name, 'currentPrice' => $request->currentPrice, 'priceBefore' => $request->priceBefore, 'type' => $request->type, 'description' => $request->description, 'inventoryNumber' => $request->inventory]);

        return $resultP;
    }
    public function deleteProducts($request)
    {

        $resultCart =  DB::table('cart')->where('idProduct', '=', $request->id)->delete();
        $resultP = DB::table('products')->delete($request->id);
        return $resultP;
    }
    public function searchProduct($request)
    {
        if ($request->value != '') {
            $result = DB::table('products')->where('name', 'like',  $request->value . '%')->get();
            return $result;
        }
    }
    public function contact($request)
    {
        $result = DB::table('contact')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'content' => $request->content,
            'created_at' =>  Carbon::now()->toDateTimeString()

        ]);
        return $result;
    }
    public function order($request)
    {
        $result = DB::table('order')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->adress,
            'note' => $request->note,
            'idProduct' => $request->id,
            'price' => $request->price,
            'quantity' => $request->quantitySold,
            'created_at' =>  Carbon::now()->toDateTimeString()
        ]);
        if (!empty($result) && $result > 0) {
            $product = DB::table('products')->where('id', '=', $request->id)->first();
            if (!empty($product)) {
                DB::table('products')->where('id', '=', $request->id)->update(['quantitySold' => $product->quantitySold != null ? $product->quantitySold + $request->quantitySold : $request->quantitySold]);
            }
            DB::table('cart')->where('idProduct', '=', $request->id)->where('idUser', '=', $request->idUser)->delete();
            return
                $result;
        }
    }
    public function getOrder()
    {

        $result = DB::table('order')->get();

        return $result;
    }
    public function deleteOrder($request)
    {

        $result = DB::table('order')->where('id', '=', $request->id)->delete();
        return $result;
    }

    public function quantitySold($request)
    {
        $result = DB::table('products')->where('quantitySold', '>', 0)->orderBy('quantitySold', 'DESC')->get();
        return $result;
    }
    public function upDateAvatar($request)
    {
        $request->validate(['images' => 'required|mimes:jpg,png,jpeg,webp|max:5000']);
        $file = $request->images;
        $image =  $file->getClientOriginalName();
        $file->move(public_path('assets/avatar'), $image);
        $result = DB::table('users')->where('id', '=', $request->id)->update(['avatar' => $image]);
        return $result;
    }
    public function getUser($request)
    {
        $result = DB::table('users')->where('id', '=', $request->id)->first();
        return $result;
    }
    public function getComment($request)
    {
        $result = DB::table('feedback')->select('users.name', 'users.avatar', 'feedback.content')->join('users', 'feedback.idUser', '=', 'users.id')->where('idProduct', '=', $request->id)->get();
        return $result;
    }
    public function sendComment($request)
    {
        $result = DB::table('feedback')->insert([
            'idUser' => $request->idUser,
            'idProduct' => $request->idProduct,
            'content' => $request->content
        ]);
        return $result;
    }
    public function discount($request)
    {
        $result = DB::table('products')->where('sale', '!=', null)->get();
        return $result;
    }
    public function getContact()
    {
        $result = DB::table('contact')->get();
        return $result;
    }
}