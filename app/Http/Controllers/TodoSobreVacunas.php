<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoSobreVacunas extends Controller
{
    public function web()
    {
        return Inertia::render('SobreVacunasWeb');
    }

    public function admin()
    {
        return Inertia::render('Paciente/SobreVacunas');
    }
}
