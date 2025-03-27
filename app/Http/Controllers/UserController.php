<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use Illuminate\Pagination\LengthAwarePaginator;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // ユーザー自身の情報を$userに保存、compact関数でビューへ渡している
    public function mypage()
    {
        $user = Auth::user();

        return view('users.mypage', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // ユーザーの情報をAuth::user()で取得し、ビューへと渡
        $user = Auth::user();

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = Auth::user();

        // $requestから各種情報を取得して、ユーザー情報を更新
        $user->name = $request->input('name') ? $request->input('name') : $user->name;
        $user->email = $request->input('email') ? $request->input('email') : $user->email;
        $user->postal_code = $request->input('postal_code') ? $request->input('postal_code') : $user->postal_code;
        $user->address = $request->input('address') ? $request->input('address') : $user->address;
        $user->phone = $request->input('phone') ? $request->input('phone') : $user->phone;
        $user->update();

        return to_route('mypage');
    }

    // パスワード変更アクション
    public function update_password(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        if ($request->input('password') == $request->input('password_confirmation')) {
            $user->password = bcrypt($request->input('password'));
            $user->update();
        } else {
            return to_route('mypage.edit_password');
        }

        return to_route('mypage');
    }
    public function edit_password()
    {
        return view('users.edit_password');
    }
    // お気に入りアクション
    public function favorite()
    {
        $user = Auth::user();

        $favorite_products = $user->favorite_products;

        return view('users.favorite', compact('favorite_products'));
    }

    //論理削除
    public function destroy(Request $request)
    {
        Auth::user()->delete();
        return redirect('/');
    }
    // 注文履歴一覧
    public function cart_history_index(Request $request)
    {
        $page = $request->page != null ? $request->page : 1;
        $user_id = Auth::user()->id;
        $billings = ShoppingCart::getCurrentUserOrders($user_id);
        $total = count($billings);
        $billings = new LengthAwarePaginator(array_slice($billings, ($page - 1) * 15, 15), $total, 15, $page, array('path' => $request->url()));

        return view('users.cart_history_index', compact('billings', 'total'));
    }

    // 注文履歴詳細
    public function cart_history_show(Request $request)
    {
        $num = $request->num;
        $user_id = Auth::user()->id;
        $cart_info = DB::table('shoppingcart')->where('instance', $user_id)->where('number', $num)->get()->first();
        Cart::instance($user_id)->restore($cart_info->identifier);
        $cart_contents = Cart::content();
        Cart::instance($user_id)->store($cart_info->identifier);
        Cart::destroy();

        DB::table('shoppingcart')->where('instance', $user_id)
            ->where('number', null)
            ->update(
                [
                    'code' => $cart_info->code,
                    'number' => $num,
                    'price_total' => $cart_info->price_total,
                    'qty' => $cart_info->qty,
                    'buy_flag' => $cart_info->buy_flag,
                    'updated_at' => $cart_info->updated_at
                ]
            );

        return view('users.cart_history_show', compact('cart_contents', 'cart_info'));
    }
}
