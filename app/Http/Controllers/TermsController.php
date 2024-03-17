<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function show(Request $request, $id){
        return view('terms', ['term' => Term::where('id', $id)->first()]);
    }
}
