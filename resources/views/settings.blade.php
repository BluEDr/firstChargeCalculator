@extends('layouts.app2',['activePage' => 'settings']) 
@section('content')
<div style="display: flex; margin:5px">
    <div class="custom-div">
        <p>Hello {{$username}}</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (!empty($sallary))
            <p>Your sallary amound is: <span style="color:red; font-weight:bold; text-decoration:underline;">{{$sallary}}</span></p>
        @endif
        <form method="POST" action="">
            @csrf
            <div class="form-group">
                <label for="lang">Choose a language here</label>
                <div>
                    <input type="radio" name="lang" id="en" value="en" checked>
                    <label for="en">English</label>
                </div>
                <div>
                    <input type="radio" name="lang" id="el" value="el">
                    <label for="el">Ελληνικά</label>
                </div>
                <label for="sallary">Write your sallary here.</label>
                <input type="text" class="form-control" name="sallary" id="sallary" placeholder="Your sallary here!">
                <input type="submit" value="Submit" class="btn btn-primary" style="margin-top: 10px">
            </div>
        </form>
    </div>
</div>
@endsection