<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insert the value</title>
</head>
<body>
    <p>{{$l}}</p>
    <p>{{$t}}</p>
    <p>{{$db}}</p>
    @if ($db != null)
        @foreach ($db as $d)
            <p>{{ $d->content }}</p>
        @endforeach
    @else
        <p>Value = Null!</p>
    @endif
    <div>
        <form action="" method="post">
            @csrf
            <p>Insert the coast amount.</p> <br>
            <input type="text" name="value" id="value"> <br>
            <input type="submit" value="Submit">
        </form>       
    </div>
</body>
</html>