<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function Home(){
        return view('front.home.index');
    }

    public function Contact(){
        return view('front.contact.index');
    }

    public function About(){
        return view('front.about.index');
    }

    public function LittleSchool(){
        return view('front.littleSchool.index');

    }

    public function tournament(){
        return view('front.tournament.index');

    }
}
