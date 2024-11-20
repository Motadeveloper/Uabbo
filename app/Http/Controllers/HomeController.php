<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']); // Permite acesso ao feed sem autenticação
    }

    /**
     * Exibe a página inicial.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home', [
            'isAuthenticated' => auth()->check(), // Passa o status de autenticação para a view
        ]);
    }
}
