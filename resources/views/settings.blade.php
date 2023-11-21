@extends('layouts.app2')
@section('content')
<div style="display: flex; border:dashed green; margin:5px">
    <div class="custom-div">
        <p>Hello {{$username}}</p>
        @if (!empty($sallary))
            <p>Your sallary amound is: {{$sallary}}</p>
        @endif
        <form method="POST" action="">
            @csrf
            <div class="form-group">
                <label for="sallary">Write your sallary here.</label>
                <input type="text" class="form-control" name="sallary" id="sallary" placeholder="Your sallary here!">
            </div>
            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>
</div>
@endsection