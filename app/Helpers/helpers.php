<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        return ucfirst($f->format($number)) . ' Taka';
    }
}

if(!function_exists('designation')){
    function designation()
    {
        $user=Auth::user();
        if (!$user || !$user->designation_id) return null;
        return $user->designation?->name;
    }
}