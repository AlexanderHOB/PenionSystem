<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(){
        $rols = Rol::orderBy('nombre', 'desc')->get();
        return  $rols;
    }
    
}
