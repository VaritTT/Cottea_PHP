<?php

class Invoice {
    use Controller;

    public function index() {
        $this->view('user/invoice', true);
    }
}
