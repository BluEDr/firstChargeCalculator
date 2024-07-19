@extends('layouts.app2',['activePage'=>''])
@section('content')
<div class="invoice-div">
    <table>
        <tr>
            <th>Category</th>
            <th>Price</th>
            <th>Reason</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        <tr>
            <td>{{$invoice->category->name}}</td>
            @if ($invoice->is_negative)
                <td>{{$invoice->price}}</td>
            @else
                <td>-{{$invoice->price}}</td>
            @endif

            <td>{{$invoice->reason}}</td>
            <td>{{$invoice->created_at->format('d-m-Y')}}</td>
            <td>{{$invoice->created_at->format('H:i')}}</td>
        </tr>
    </table>
    <br>
    <img src="{{ asset('storage/uploaded_photos/'.$invoice->image) }}" alt="Invoice Image" width="100%">
    <br>
    <a href="{{route('index')}}">Return to home.</a>

</div>
@endsection
