<?php

namespace App\Http\Controllers;

use App\Models\SchoolType;

class SchoolTypeController extends Controller
{
    /**
     * Afficher la page d'accueil avec les types d'établissement.
     */
    public function index()
    {
        $schoolTypes = SchoolType::orderBy('id')->get();

        return view('welcome', compact('schoolTypes'));
    }
}