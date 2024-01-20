@extends('layouts.app2',['activePage' => 'settings']) 
@section('content')
<div style="display: flex; margin:5px">
    <div class="custom-div">
        <p>{{__('messages.hello')}} {{$username}}</p>
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
            <p>{{__('messages.sallaryAmound')}} <span style="color:red; font-weight:bold; text-decoration:underline;">{{$sallary}}</span></p>
        @endif
        <form method="POST" action="">
            @csrf
            <div class="form-group">
                <label for="lang">{{__('messages.chooseLang')}}</label>
                <div>
                    <input type="radio" name="lang" id="en" value="en" checked>
                    <label for="en">English</label>
                </div>
                <div>
                    <input type="radio" name="lang" id="el" value="el">
                    <label for="el">Ελληνικά</label>
                </div>
                <label for="sallary">{{__('messages.writeSallaryHere')}}</label>
                <input type="text" class="form-control" name="sallary" id="sallary" placeholder="{{__('messages.yourSallaryHere')}}">
                <input type="submit" value="{{__('messages.submit')}}" class="btn btn-primary" style="margin-top: 10px">
            </div>
        </form>
    </div>
</div>
@endsection