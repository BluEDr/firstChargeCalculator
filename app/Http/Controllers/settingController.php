<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Sallary;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class settingController extends Controller
{
    public function index(Request $request) {
        $sal = sallary::all();
        $username = Auth::user()->name;
        $userid = Auth::user()->id;
        $sal = sallary::find($userid);  
        if(!empty($sal)) 
            return view('settings')->with('username',$username)->with('sallary',$sal->sallary); 
        return view('settings')->with('username',$username);  
    }
}
