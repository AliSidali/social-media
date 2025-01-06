<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        if (in_array($request->lang, ['en', 'fr', 'ar'])) {
            app()->setLocale($request->lang);
            Session::put('locale', $request->lang);
        }
        return redirect()->back();
    }
}
