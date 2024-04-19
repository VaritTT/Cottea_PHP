<?php

class Logout {
    use Controller;

    public function index() {
        $user = new User();
        if (!empty($_SESSION['USER'])) {
            unset($_SESSION['USER']);
        }
        if (!empty($_SESSION['token'])) {
            unset($_SESSION['token']);
        }
        if (!empty($_SESSION['access_token'])) {
            unset($_SESSION['access_token']);
        }
        if (!empty($_SESSION['GUEST'])) {
            unset($_SESSION['GUEST']);
        }
        $user->googleLogout();
        session_destroy();
        setcookie("token", "", time() - 3600, "/", "", true, true);
        redirect('home');
    }
}
