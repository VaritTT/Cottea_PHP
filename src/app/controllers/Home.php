<?php

class Home {
    use Controller;

    public function index() {
        // if (isset($_SESSION['USER'])) {
        //     if ($_SESSION['USER']->user_type == 'admin') {
        //         redirect('admin/dashboard');
        //     }
        // }
        if (empty($_SESSION['USER'])) {
            $guest = new stdClass();
            $guest->name = "GUEST";
            $checkout = new stdClass();
            $guest->checkout = $checkout;
            $_SESSION['GUEST'] = $guest;
        } else {
            unset($_SESSION['GUEST']);
        }
        $category = new Category_process();
        $result = $category->findAll();
        $data['category'] = $result;
        $this->view('user/homepage_customer', true, $data);
    }
}
