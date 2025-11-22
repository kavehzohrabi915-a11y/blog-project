<!DOCTYPE html>
<html>
<head>
    <title>پنل ادمین</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @if (auth()->check())
        <nav>منو ادمین (مثلاً پست‌ها، دسته‌ها)</nav>
    @endif
    @yield('content')
</body>
</html>