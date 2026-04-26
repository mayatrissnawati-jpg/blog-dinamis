<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Page</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>

    <!-- HEADER -->
    <header style="background:#333;color:#fff;padding:10px;">
        <h2>My Blog</h2>
        <nav>
            <a href="/blog/user" style="color:white;">Home</a>
            <a href="/about" style="color:white;">About</a>
        </nav>
    </header>

    <!-- CONTENT -->
    <main style="padding:20px;">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer style="background:#333;color:#fff;padding:10px;text-align:center;">
        <p>© 2026 My Blog</p>
    </footer>

</body>
</html>