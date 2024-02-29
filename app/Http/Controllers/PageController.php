<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PageController extends Controller
{
   /* public function index(){
        return 'Index';
    }*/

   public function main(){
        
        return view('welcome');      //1.return about page
    }

    public function index(){
        $title = 'Welcome To Laravel!'; //2.create to passing value
        return view('index', ['title' => $title]);     //return  index page //passing value
    }

    public function services(){
        $data = array(
            'title' => 'Services',
            'services' => ['Web Design', 'Programming', 'SEO'] //create to passing value with array
        );
        return view('service')->with($data);
    }
}
