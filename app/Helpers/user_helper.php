<?php

if (!function_exists('current_user')) {

    function current_user()
    {
        $auth = service('auth');

        return $auth->users()->row();
    }
}

if (!function_exists('has_akses')) {

    function has_akses($controller, $crud)
    {
        $auth = service('auth');

        // belum selesai
        return $auth->users()->row();
    }
}
