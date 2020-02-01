<?php
namespace app\helpers;

use Countable;

class SessionBasket implements Countable {

    public function __construct() {
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function set($index, $value) {
        $_SESSION['cart'][$index] = $value;
    }

    public function get($index) {
        return $this->exists($index) ? $_SESSION['cart'][$index] : null;
    }

    public function exists($index) {
        return isset($_SESSION['cart'][$index]);
    }

    public function all() {
        return $_SESSION['cart'];
    }

    public function unset($index) {
        if($this->exists($index)) {
            unset($_SESSION['cart'][$index]);
        }
    }

    public function clear() {
        unset($_SESSION['cart']);
    }

    /**
     * @inheritDoc
     */
    public function count() {
        return count($this->all());
    }
}