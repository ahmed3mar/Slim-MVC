<?php namespace App\Controllers;

use App\Models\User;

class IndexController extends Controller {

    function index() {
        return view('home');
    }

    function users() {
        $users = User::all();
        return view('users', compact('users'));
    }

}