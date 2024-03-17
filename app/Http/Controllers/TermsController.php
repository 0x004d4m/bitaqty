<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function show(Request $request, $id){
        return view('terms', ['term' => Term::where('id', $id)->first()]);
    }
    public function terms(Request $request){
        return view('terms_and_conditions',);
    }
    public function privacy(Request $request){
        return view('privacy_policy',);
    }
}
