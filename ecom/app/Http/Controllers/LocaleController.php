<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    protected array $supported = ['en', 'bn'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, $this->supported)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
