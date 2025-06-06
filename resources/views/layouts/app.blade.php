<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Quindy Music Services')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body data-bs-theme="dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="/">Quindy</a>
            </div>

            <div class="d-none d-lg-block position-absolute start-50 translate-middle-x">
                <ul class="navbar-nav flex-row gap-3">
                    <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="/services" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="/packages" class="nav-link">Packages</a></li>
                    <li class="nav-item"><a href="/portfolio" class="nav-link">Portfolio</a></li>
                    @auth
                    <li class="nav-item"><a href="/order" class="nav-link">Order</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ auth()->user()->role === 'admin' ? '/admin/orders' : '/dashboard' }}">Dashboard</a>
                    </li>
                    @endauth
                </ul>
            </div>

            <div class="d-flex align-items-center">
                <ul class="navbar-nav flex-row gap-3 align-items-center">
                    @auth
                    <li class="nav-item">
                        <span class="nav-link">Hello, {{ auth()->user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link p-0" style="line-height: 1.5;">Logout</button>
                        </form>
                    </li>
                    @endauth

                    @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <footer class="bg-dark text-white pt-4">
        <div class="container text-center pb-4">
            <p>&copy; {{ date('Y') }} Quindy Music Services. All rights reserved.</p>
            <a href="https://push.fm/fl/quindy" target="_blank">Follow me</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
