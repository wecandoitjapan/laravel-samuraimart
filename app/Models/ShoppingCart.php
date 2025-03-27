<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $table = 'shoppingcart';

    // 該当ユーザーの注文一覧を取得するgetCurrentUserOrders()を追加
    public static function getCurrentUserOrders($user_id)
    {
        $shoppingcarts = DB::table('shoppingcart')->where("instance", "{$user_id}")->get();

        $orders = [];

        // 注文一覧として、注文ID、購入日時、金額、ユーザー名、注文番号を取得
        foreach ($shoppingcarts as $order) {
            $orders[] = [
                'id' => $order->number,
                'created_at' => $order->updated_at,
                'total' => $order->price_total,
                'user_name' => User::find($order->instance)->name,
                'code' => $order->code
            ];
        }

        return $orders;
    }
}
