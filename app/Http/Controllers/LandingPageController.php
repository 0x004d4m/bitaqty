<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Backpack\LangFileManager\app\Models\Language;

class LandingPageController extends Controller
{
    public function setLanguage(Request $request, $locale)
    {
        if (in_array($locale, Language::where('active', 1)->pluck('abbr')->toArray())) {
            Session::put('locale', $locale);
        }
        return redirect()->back();
    }
    public function setCurrency(Request $request, $currency)
    {
        if (in_array($currency, Currency::pluck('id')->toArray())) {
            Session::put('currency', $currency);
        }
        return redirect()->back();
    }
}
