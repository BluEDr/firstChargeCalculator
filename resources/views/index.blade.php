@extends('layouts.app2')
@section('content')

{{-- @if (Auth::check())
    {{Auth::user()->name}}
@endif

@if (is_integer(55.2))
    <p>float</p>
    
@else
    
    <p>not float</p>
@endif --}}


{{-- @if(session('errorCheck')) --}}
@if(!empty($errorCheck))
    <div class="alert alert-danger">
        {{ $errorCheck }} 
    </div>
@endif
<div style="border:dashed green; margin:5px; display: flex" class="divbd">
    <div class="custom-div-form">
<div class="custom-div">
    <form method="POST" action="">
        @csrf
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="defaultCheck11">
            <label class="form-check-label" for="defaultCheck1">
                Add Money
            </label>
        </div>
        <div class="form-group">
        <label for="price">Insert the price *</label>
        <input type="text" class="form-control" name="price" id="price" placeholder="Default input">
        </div>
        <div class="form-group">
            <label for="reason">Insert the reason (Not required)</label>
            <input type="text" class="form-control" name="reason" id="reason" placeholder="Default input">
        </div>
        
        @if (count($options) > 0)
            <div class="form-group">Category:</div>
            @foreach ($options as $option)
                <label>
                    <input class="form-check-input" type="radio" name="selected_option" value="{{ $option->id }}" {{ $loop->first ? 'checked' : '' }} >
                    {{ $option->name }}
                </label><br>
            @endforeach
        @endif


        @if (count($currency_options) > 0)
            <div class="form-group col-md-4">
                <label for="inputState">Currency</label>
                <select id="inputState" name='dropdown_currency' class="form-control">

                
                @foreach ($currency_options as $option)
                    <label>
                        <option class="form-control" name="dropdown_currency" value="{{ $option->id }}" {{ $loop->first ? 'selected' : '' }} >
                        {{ $option->name }}
                    </label><br>
                @endforeach
                

                </select>
            </div>
        @endif

        <input type="submit" value="Submit" class="btn btn-primary">
    </form>
</div>
</div>
    <div class="custom-div2">
        <div class="custom-div-70">
        @if (count($pAmound)>0)
            <table>
                <tr>
                    <th>Price</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Delete</th>
                </tr>
                @foreach($pAmound as $pA)
                    {{-- @if($pA->created_at==) --}}
                    <tr>
                        @if ($pA->is_negative)
                            <td>+{{$pA->price}}</td>
                        @else
                            <td>-{{$pA->price}}</td>
                        @endif
                        <td>{{$pA->reason}}</td>
                        @if($pA->created_at->format('Y-m-d') == $todayDate)
                            <td style="color:brown">Today</td>
                        @else
                            <td>{{$pA->created_at->format('Y-m-d')}}</td>
                        @endif
                        {{-- <td>{{$pA->user->name}}</td> --}}
                        <td>{{ optional($pA->category)->name }}</td> 
                        {{-- <td><a href="{{route('delete.row',$pA->id)}}">del</a></td> --}}
                        <td><a href="#" onclick='confirmAndDelete("{{route("delete.row",$pA->id)}}")'>del</a></td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>There is no data added. Buy and save here your spended money data ;) </p>
        @endif
        </div>
        <div class="custom-div-30">
            <table>
            @if(!empty($pMonthsSum))
                <tr><td>Total spend from this month:</td><td> {{$pMonthsSum}}</td></tr>
            @endif
            @if(!empty($perDay))
                <tr><td>You can spend each single day</td><td> {{$perDay}}</td></tr>
            @endif
            @if(!empty($spentToday))
                <tr><td>Today, you spent:</td><td> {{$spentToday}}</td></tr>
            @endif
            @if(!empty($summWhileNow) && (count($pAmound) > 0) && (!empty($perDay)))
                <tr><td>Status Now:</td><td style="background-color: black ; color: red; text-decoration-line: underline; font-weight:bold"> {{$summWhileNow}}</td></tr>
            @endif
            </table>
            
        </div>
    </div>
</div>

<script>
    function confirmAndDelete(rowId) {
        if(confirm('Are you sure, you want to delete this item?')) {
            // window.location.href = "/delete-row/";
            window.location.href = rowId;
        }

    }
</script>
@endsection


