<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\payed_amound;
use App\Models\category;
use App\Models\currency;
use Auth;
class calculatingController extends Controller
{
    public function showIndex(Request $request) {
        // echo $request->method();
        $options = category::all();
        $pAmound = payed_amound::all();
        $currency_options = currency::all();
        if ($request->method() == 'POST') {
            // echo $request->input('price');
            
            echo $request->input('defaultCheck11');
            $number = $request->input('price');
            if (is_numeric($number) && is_float($number + 0.0)) {
                $payed_amound = new payed_amound();
                $payed_amound->price = $number+0.0;
                $payed_amound->reason = $request->get('reason');
                if (count($options) > 0)
                    $payed_amound->category_id = $request->get('selected_option');
                else
                    $payed_amound->category_id = -1;
                $payed_amound->user_id = Auth::user()->id;     //todo an den eisai loged in crasharei
                if(count($currency_options)>0) 
                    $payed_amound->currency_id = $request->get('dropdown_currency');
                else
                    $payed_amound->currency_id = -1;    

                if ($request->has('defaultCheck11')) {
                    $payed_amound->is_negative = 1;
                } else {
                    $payed_amound->is_negative = 0;
                }
                $payed_amound->save();
                $a = $request->input('defaultCheck11');
                $pAmound = payed_amound::select('id','price','reason','user_id','category_id')->with('user','category')->where('price','>',350)->orderBy('price','desc')->get();
                return view('index')->with('errorCheck',$a)->with('tiramisou', $a+0.0)->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'));
            } else {
                $a = 'errorCheck';
                return view('index')->with('errorCheck','This is not a correct price value.')->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'));            
            }
            
        } else {
            return view('index')->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'));
        }
    }
    public function index(Request $request) {
        $a1 = 1;
        $a2 = 2;
        $pAmound = payed_amound::all();
        if($request->method()=='POST') {
            $a1 = $request->get('price');
            $a2 = $request->get('reason');
        }
        return view('index', ['l'=>$request->method(), 'a' => $a1, 'b'=> $a2, compact('pAmound')]); //todo na to do me to pAmound
    }

    public function pyli() {
        $akis = (100+4)/2;
        return "Hello world " . $akis;
    }
    public function pyliTtr($a) {
        $posts = Post::all();
        return view('ttr',['q' => $posts]);
    }

    public function insertValue(Request $request){
        if ($request->method()=='POST') {
            $post = new Post();
            $post->title = $request->get('value');
            $post->content = $request->get('value');
            $post->user_id = 0; 
            $post->save();
            $postSelects = Post::where('content', 'like', '%' . '88' . '%')->get();
            return view('insertValue', ['l'=> $request->method(), 't'=>5122, 'db' => $postSelects]);
        }
        return view('insertValue', ['l' => $request->method(), 't'=>5122, 'db' => null]);
    }
    public function postDelete(Post $post){
        echo "$post";
        $post->delete();
        return redirect('insert');
    }
    
    public function processForm(Request $request) {
        $request->validate([
            'price' => 'required|numeric',
        ]);

        $number = $request->input('price');

        if (is_numeric($number)) {
            return redirect()->route('home2'); // Redirect to success page
        } else {
            $a = 'errorCheck';
            return redirect()->route('index')->with($a, 'Please enter a valid real number.'); // Redirect back to the form with an error message
        }
    }
}
