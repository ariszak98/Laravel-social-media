<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Home Page</h1>
    <hr>

    <p>Logged in as user: <b>{{ $name }}</b> and his/her favorite animals are:<p>
    <ul>
        @foreach ($animals as $animal)
            <li>{{ $animal }}</li>
        @endforeach
    </ul>
    <p>Current year: <i>{{ $year }}</i></p>
    <a href="/about">Go to About page</a>
</body>
</html>