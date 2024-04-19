<?php

class CreateAccount {
    use Controller;

    public function index() {
        $user = new User();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $post['username'] = htmlspecialchars($_POST['username']);
            $post['password'] = htmlspecialchars($_POST['password']);
            $post['confirm_password'] = htmlspecialchars($_POST['confirm_password']);
            $post['email'] = $_SESSION['email'];
            if ($user->validateAccount($post)) {
                unset($_SESSION['email']);
                redirect('login');
            } else {
                $user->errors['username'] = "Username already exists.";
            }
        }
        $data['errors'] = $user->errors;
        if ($user->errors) {
            $this->view('user/create_account', false, $data);
        } else {
            $this->view('user/create_account', false);
        }
    }
}
