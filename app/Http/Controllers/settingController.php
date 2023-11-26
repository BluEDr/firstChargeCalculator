<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Sallary;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class settingController extends Controller
{
    public function index(Request $request) {
        // $sal = sallary::all();
        $username = Auth::user()->name;
        $userid = Auth::user()->id;
        // $sal = sallary::find($userid);  
        $sal = sallary::where('user_id',$userid)->first(); 
        echo $sal; 
        // if(!empty($sal)) {
        if ($sal) { //todo na do pos tha kano to update ston pinaka
            
            // $sal->update(['sallary'=>$request->input('sallary')]);  
            return view('settings')->with('username',$username)->with('sallary',$sal->sallary); 
        } else {
            return view('settings')->with('username',$username);
        }  
    }
}
