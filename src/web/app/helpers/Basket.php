<?php

namespace app\helpers;

use app\models\Vinyle;

class Basket {

    private static function init() {
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public static function get(Vinyle $vinyle) {
        self::init();
        return isset($_SESSION['cart'][$vinyle->id]) ? Vinyle::where(['id' => $vinyle->id]) : null;
    }

    public static function add(Vinyle $vinyle, int $quantity) {
        self::init();
        if(isset($_SESSION['cart'][$vinyle->id])) {
            $_SESSION['cart'][$vinyle->id] += $quantity;
        } else {
            $_SESSION['cart'][$vinyle->id] = $quantity;
        }
    }

    public static function remove(Vinyle $vinyle) {
        self::init();
        unset($_SESSION['cart'][$vinyle->id]);
    }

    public static function clear() {
        self::init();
        unset($_SESSION['cart']);
    }

    public static function all() {
        self::init();

        $ids = [];
        $items = [];

        foreach ($_SESSION['cart'] as $k => $v) {
            $ids[] = $k;
        }

        $vinyles = Vinyle::find($ids);

        foreach ($vinyles as $vinyle) {
            $vinyle->quantity = $_SESSION['cart'][$vinyle->id];
            $items[] = $vinyle;
        }

        return $items;
    }

    public static function subtotal() {
        $total = 0;

        foreach(Basket::all() as $item) {
            $total += $item->prix * $item->quantity;
        }

        return $total;
    }

    public static function update(Vinyle $vinyle, int $quantity) {
        if(isset($_SESSION['cart'][$vinyle->id])) {
            if($quantity <= 0 ) {
                self::remove($vinyle);
            } else {
                $_SESSION['cart'][$vinyle->id] = $quantity;
            }
        }
    }

    public static function count() {
        self::init();
        return count($_SESSION['cart']);
    }
}