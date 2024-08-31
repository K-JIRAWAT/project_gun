<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .sidebar {
            background-color: #e3f2fd; 
            padding: 15px;
            height: 100vh;
            position: fixed;
            width: 250px;
            transition: width 0.3s ease;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); 
        }

        .sidebar .nav-link {
            color: #333;
            transition: background-color 0.3s ease, color 0.3s ease; 
        }

        .sidebar .nav-link:hover {
            background-color: #bbdefb; 
            color: #333; 
        }

        .sidebar .nav-link.active {
            color: #333; 
            background-color: #bbdefb; 
            border-radius: 0.25rem; 
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }


        .sidebar.collapsed {
            width: 80px; 
        }

        .sidebar.collapsed .nav-link > span {
            display: none;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .content.collapsed {
            margin-left: 80px;
        }

        .toggle-btn {
            background-color: #e3f2fd; 
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px; 
            transition: transform 0.3s ease; 
        }

        /* สีของปุ่ม Toggle เมื่อ hover */
        .sidebar .toggle-btn:hover {
            background-color: #bbdefb;
            transform: scale(1.1);
        }

        .sidebar.collapsed .toggle-btn {
            transform: rotate(180deg); 
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Toggle Button inside Sidebar -->
        <button class="btn toggle-btn" id="toggleSidebar">
            <i class="bi bi-list"></i> <!-- ไอคอนสำหรับแสดง/ซ่อน -->
        </button>
        
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a class="nav-link {{ Request::is('user/home') ? 'active' : '' }}" href="{{ url('/user/home') }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>สร้างแบบฟอร์ม</span>
                </a>
            </li>
            <li>
                <a class="nav-link {{ Request::is('user/list') ? 'active' : '' }}" href="{{ url('/user/list') }}">
                    <i class="bi bi-folder-fill"></i>
                    <span>คำร้องของท่าน</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link link-dark" onclick="confirmLogout(event)">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    

    <!-- Main Content -->
    <div class="content" id="content">
        @yield('body')
    </div>

    <!-- JavaScript to toggle the sidebar -->
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebar').classList.add('collapsed');
            document.getElementById('content').classList.add('collapsed');
        });
    
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('content').classList.toggle('collapsed');
        });

        function confirmLogout(event) {
            // ป้องกันการทำงานของลิงก์
            event.preventDefault();

            // แสดง SweetAlert สำหรับยืนยันการออกจากระบบ
            Swal.fire({
                title: 'ต้องการออกจากระบบ?',
                // text: "คุณต้องการออกจากระบบหรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ออกจากระบบ!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ถ้าผู้ใช้ยืนยันการออกจากระบบ, ทำการส่งฟอร์ม
                    document.getElementById('logout-form').submit();
                }
            });
        }

    </script>
   <!-- SweetAlert2 CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.27/dist/sweetalert2.min.css">
   <!-- SweetAlert2 JS -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.27/dist/sweetalert2.min.js"></script>
</body>
</html>
