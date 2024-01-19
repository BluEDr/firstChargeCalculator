@extends('layouts.app2',['activePage' => 'home'])
@section('content')

@if(session('errorCheck'))
    <div class="alert alert-danger">
        {{ session('errorCheck') }} 
    </div>
@endif
<div style="margin:5px; display: flex" class="divbd">
    <div class="custom-div-form">
<div class="custom-div">
    <form method="POST" action="{{route('index')}}" enctype="multipart/form-data"> 
        @csrf
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="defaultCheck11">
            <label class="form-check-label" for="defaultCheck1">
                {{__('messages.add_money')}}
            </label>
        </div>
        <div class="form-group">
        <label for="price">{{__('messages.insert_price')}} *</label>
        {{-- <input type="text" class="form-control" name="pricec" id="price" pattern="[0-9.]*" inputmode="numeric" > --}}
        <input type="text" class="form-control" name="price" id="price" placeholder="{{__('messages.insert_numeric_number')}}">
        </div>
        <div class="form-group">
            <label for="reason">{{__('messages.insert_the_reason')}}</label>
            <input type="text" class="form-control" name="reason" id="reason" placeholder="{{__('messages.give_me_some_info')}}">
        </div>
        
        @if (count($options) > 0)
            <div class="form-group">{{__('messages.category')}}:</div>
            @foreach ($options as $option)
                <label>
                    <input class="form-check-input" type="radio" name="selected_option" value="{{ $option->id }}" {{ $loop->first ? 'checked' : '' }} >
                    {{ $option->name }}
                </label><br>
            @endforeach
        @endif
        <input type="file" name="photo">

        @if (count($currency_options) > 0)
            <div class="form-group col-md-4">
                <label for="inputState" style="display: none;">{{__('messages.currency')}}</label>
                <select id="inputState" name='dropdown_currency' class="form-control" style="display: none;">

                
                @foreach ($currency_options as $option)
                    <label>
                        <option class="form-control" name="dropdown_currency" value="{{ $option->id }}" {{ $loop->first ? 'selected' : '' }} >
                        {{ $option->name }}
                    </label><br>
                @endforeach
                

                </select>
            </div>
        @endif
        <input type="submit" style="margin-top: 5px" value="{{__('messages.submit_btn')}}" class="btn btn-primary">
    </form>
</div>
</div>
    <div class="custom-div2">
        <div class="custom-div-70">
            <div style=" align-items: center; margin-right: 20px;" class="divbd">
                <form method="GET" action="{{route('special.search')}}"> 
                    <label for="specialSearch">Special Search:</label>
                    <hr>
                    <select id="dayDropdown" name="day" class="form-control">
                        <?php
                            $days = array_merge(['Days'], range(1, 31));
                            foreach ($days as $day) {
                                $selected = ($day === 'Days') ? 'selected' : '';
                                echo "<option value='$day' $selected>$day</option>";
                            }
                        ?>
                    </select>
            
                    <select id="monthDropdown" name="month" class="form-control">
                        <?php
                            $months = array_merge(['Months'],
                                ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                                
                            );
                            $i=0;
                            foreach ($months as $month) {
                                $selected = ($month === 'Months') ? 'selected' : '';
                                $j = ($i === 0) ? 'Months' : $i;
                                echo "<option value='$j' $selected>$month</option>";
                                $i++;
                            }
                        ?>
                    </select>
            
                    <select id="yearDropdown" name="year" class="form-control">
                        <option value="Years">Years</option>

                        <?php
                            if (isset($yearsInDb)) {
                                if (count($yearsInDb) > 0) {
                                    $i = 0;
                                    foreach ($yearsInDb as $year) {
                                        $selected = ($i === count($yearsInDb)-1) ? 'selected' : '';
                                        echo "<option value='$year' $selected>$year</option>";
                                        $i++;
                                    }
                                }
                            }

                        ?>
                    </select>
            
                    <button type="submit" class="btn btn-primary">{{__('messages.search')}}</button>
                </form>
            </div>
        @if (count($pAmound)>0)
            <table>
                <tr>
                    <th>{{__('messages.price')}}</th>
                    <th>{{__('messages.reason')}}</th>
                    <th>{{__('messages.date')}}</th>
                    <th>{{__('messages.category')}}</th>
                    <th>{{__('messages.invoice')}}</th>
                    <th>{{__('messages.delete')}}</th>
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
                        <td>
                            @if($pA->image)
                                <a href="/invoice/{{$pA->id}}">invoice</a>{{-- <a href="./storage/uploaded_photos/{{$pA->image}}">invoice</a> --}}
                            @endif
                        </td>
                        <td><a href="#" onclick='confirmAndDelete("{{route("delete.row",$pA->id)}}")'>del</a></td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>There is no data. </p>
        @endif
        </div>
        <div class="custom-div-30">
            <table>
            @if(!empty($pMonthsSum))
                <tr><td>{{__('messages.total_spend')}}</td><td> {{$pMonthsSum}}</td></tr>
            @endif
            @if(!empty($perDay))
                <tr><td>{{__('messages.how_much_can_spend_each_day')}}</td><td> {{$perDay}}</td></tr>
            @endif
            @if(!empty($spentToday))
                <tr><td>{{__('messages.today_spent')}}</td><td> {{$spentToday}}</td></tr>
            @endif
            @if(!empty($summWhileNow) && (count($pAmound) > 0) && (!empty($perDay)))
                <tr><td>{{__('messages.status_now')}}</td><td style="background-color: black ; color: red; text-decoration-line: underline; font-weight:bold"> {{$summWhileNow}}</td></tr>
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


