<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach ($q as $posts)
        to apotelesma einai {{$posts->content}} {{env('APP_NAME')}} <br>
    @endforeach
</body>
</html>