<?php

namespace app\helpers;

use app\models\Vinyle;

class Basket {

    private $session;

    public function __construct(SessionBasket $session) {
        $this->session = $session;
    }

    public function get(Vinyle $vinyle) {
        return $this->session->get($vinyle);
    }

    public function add(Vinyle $vinyle, int $quantity) {
        if($this->session->exists($vinyle)) {
            $this->session->set($vinyle, $quantity);
        }
    }

    public function remove(Vinyle $vinyle) {
        $this->session->unset($vinyle);
    }

    public function clear() {
        $this->session->clear();
    }

    public function all() {
        return $this->session->all();
    }

    public function count() {
        return $this->session->count();
    }
}