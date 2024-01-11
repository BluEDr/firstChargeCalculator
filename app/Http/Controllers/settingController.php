<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Sallary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class settingController extends Controller
{
    public function index(Request $request) {
        // $sal = sallary::all();
        $username = Auth::user()->name;
        $userid = Auth::user()->id;
        // $sal = sallary::find($userid);  
        // $sal = sallary::where('user_id',$userid)->first(); 
        $sal = sallary::where('user_id', $userid)->latest('created_at')->first();

        $l = user::find($userid);
        $reqLang = $request->input('lang');
        $l->update(['language' => $reqLang,]); //FIXME: den kanei update tin glossa ston pinaka users
        $request->validate(['sallary' => 'numeric'],['sallary.numeric' => 'The entered value must be a number!']);
        if ($request->sallary) {
            $ssal = new Sallary();
            $ssal->sallary = $request->sallary;
            $ssal->user_id = $userid;
            $ssal->save();
            return view('settings')->with('username',$username)->with('sallary',$ssal->sallary); 
        }
        if ($sal){
            return view('settings')->with('username',$username)->with('sallary',$sal->sallary);
        } else
            return view('settings')->with('username',$username);
    }
}
