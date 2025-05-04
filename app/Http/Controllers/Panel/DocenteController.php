<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function dashboard()
    {
        if (!Auth::user()->hasRole('Docente')) {
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return redirect()->route('filament.docente.pages.dashboard');
    }
} 