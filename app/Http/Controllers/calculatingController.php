<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class calculatingController extends Controller
{
    public function showIndex() {
        return view('index');
    }
    public function index(Request $request) {
        $a1 = 1;
        $a2 = 2;
        if($request->method()=='POST') {
            $a1 = $request->get('price');
            $a2 = $request->get('reason');
        }
        return view('index', ['l'=>$request->method(), 'a' => $a1, 'b'=> $a2]);
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
        echo "all goooooooood";
        $request->validate([
            'price' => 'required|numeric',
        ]);

        $number = $request->input('price');

        if (is_numeric($number)) {
            return redirect()->route('home2'); // Redirect to success page
        } else {
            return redirect()->route('index')->with('error', 'Please enter a valid real number.'); // Redirect back to the form with an error message
        }
    }
}
