<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Sallary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App;

class settingController extends Controller
{
    public function index(Request $request) {
        // $sal = sallary::all();
        $username = Auth::user()->name;
        $userid = Auth::user()->id;
        // $sal = sallary::find($userid);  
        // $sal = sallary::where('user_id',$userid)->first(); 
        $sal = sallary::where('user_id', $userid)->latest('created_at')->first();


        $reqLang = $request->input('lang');
        if($reqLang) {
            $l = user::find($userid);
            $l->update(['language' => $reqLang,]);
            App::setlocale($reqLang); //gia na ananaiosei aytomata tin glossa giati xoris ayto tha fanei sto epomeno refresh poy tha treksei kai to middleware
        }
        if ($request->sallary) {
            $request->validate(['sallary' => 'numeric'],['sallary.numeric' => 'The entered value must be a number!']);
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
