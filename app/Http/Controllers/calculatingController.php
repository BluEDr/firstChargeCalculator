<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class calculatingController extends Controller
{
    public function index($d) {
        return $d;
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
            $post->save();
            $postSelects = Post::all();
            return view('insertValue', ['l'=> $request->method(), 't'=>5122, 'db' => $postSelects]);
        }
        return view('insertValue', ['l'=> $request->method(), 't'=>5122, 'db' => null]);
    }
}
