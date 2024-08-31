<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/user') ? 'active' : '' }}" href="{{ url('/admin/user') }}">ตั้งค่าผู้ใช้งาน</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/item') ? 'active' : '' }}" href="{{ url('/admin/item') }}">ตั้งค่ายุทโธปกรณ์</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse d-flex justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item d-flex align-items-center">
                    {{-- <a class="navbar-brand" href="#">
                        <img src="/image/soldier6.jpg" alt="Avatar Logo" style="width:40px;" class="rounded-pill"> 
                    </a> --}}
                    <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">
                        {{ Auth::user()->username }}
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                        <span id="currentTime"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    @yield('body')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.27/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.27/dist/sweetalert2.min.js"></script>
    <!-- Bootstrap 5 JS (ถ้าคุณต้องการใช้ JavaScript ของ Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
<script>
    function updateTime() {
        var now = new Date();
        var formattedTime = now.toLocaleTimeString();
        document.getElementById('currentTime').textContent = formattedTime;
    }

    setInterval(updateTime, 1000); // อัปเดตทุก 1 วินาที
</script>
</html>
