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
        $request->validate(['sallary' => 'numeric'],['sallary.numeric' => 'The entered value must be a number!']);
        // if(!empty($sal)) {
        if ($sal && $request->sallary) { //todo na do pos tha kano to update ston pinaka
            
            // $sal->update(['sallary'=>$request->input('sallary')]);  
            $sal->sallary = $request->sallary;
            $sal->save();
            return view('settings')->with('username',$username)->with('sallary',$sal->sallary); 
        } else {
            if ($request->sallary) {
                $ssal = new Sallary();
                $ssal->sallary = $request->sallary;
                $ssal->user_id = $userid;
                $ssal->save();
                return view('settings')->with('username',$username)->with('sallary',$ssal->sallary); 
            }
            if ($sal)
                return view('settings')->with('username',$username)->with('sallary',$sal->sallary);
            else
                return view('settings')->with('username',$username);
        }  
    }
}
