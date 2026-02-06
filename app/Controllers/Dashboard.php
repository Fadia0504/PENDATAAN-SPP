<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function __construct()
    {
        if (!session()->get('isLogin')) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    public function index()
    {
        return view('dashboard/index', [
            'title' => 'Dashboard'
        ]);
    }
}