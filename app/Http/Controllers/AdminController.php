<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Obtén todos los usuarios (asegúrate de que tienes datos en la base)
        $users = User::all();
        
        // Pasa los usuarios a la vista
        return view('admin.index', compact('users'));
    }
}
