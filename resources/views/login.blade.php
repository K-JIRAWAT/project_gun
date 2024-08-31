<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.27/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #e3f2fd;
            /* background-image: url('/image/soldier2.jpg'); */
            background-size: cover;
            /* background-position: center;  */
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            /* height: 100vh; */
            margin: 0;
            opacity: 0.8;
        }
        .login-container {
            position: relative;
            /* max-width: 400px; */
            /* padding: 20px; */
            /* border: 1px solid #6c757d; */
            border-radius: 8px;
            background-color: #ffffff;
            background-size: cover;
            background-position: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            margin-top: 20%;
            transform: translateY(-50px);
            transition: transform 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-55px);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2da1ff;
            text-shadow: 2px 2px 2px rgba(194, 221, 255, 0.5);
        }
        .login-form .form-group {
            margin-bottom: 20px;
            animation: slideInRight 0.5s ease;
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .login-form .form-control {
            height: 45px;
        }
        .login-form .btn-primary {
            width: 100%;
            height: 45px;
            animation: slideInLeft 0.5s ease;
            background-color: #2da1ff;
            border-color: #2da1ff;
        }
        .login-form .btn-primary:hover {
            background-color: #87c9ff;
            border-color: #87c9ff;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #222223;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    
<section class="text-center text-lg-start">
    <style>
      .cascading-right {
        margin-right: -50px;
      }
  
      @media (max-width: 991.98px) {
        .cascading-right {
          margin-right: 0;
        }
      }
    </style>
  
    <!-- Jumbotron -->
    <div class="container py-4" style="margin-top: 50%;">
      <div class="row g-0 align-items-center">
        <div class="col-lg-12 mb-10 mb-lg-0">
          <div class="card cascading-right bg-body-tertiary login-container" style="
              backdrop-filter: blur(30px);
              ">
            <div class="card-body p-5 shadow-5 text-center ">
              <h2 class="fw-bold mb-5">เข้าสู่ระบบ</h2>
                <form action="{{ route('login.post') }}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="ชื่อผู้ใช้งาน" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
  
                <div class="register-link">
                    <p>ยังไม่มีบัญชี? <a href="{{ url('/register') }}">สมัครเข้าใช้งาน</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
{{--   
        <div class="col-lg-6 mb-5 mb-lg-0">
          <img src="/image/soldier5.jpg" class="w-100 rounded-4 shadow-4"
            alt="" />
        </div> --}}
      </div>
    </div>
    <!-- Jumbotron -->
  </section>
  <!-- Section: Design Block -->
    <!-- Bootstrap 5 JS (ถ้าคุณต้องการใช้ JavaScript ของ Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.27/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                    // text: '{{ session('error') }}',
                });
            @endif
        });

        document.addEventListener('DOMContentLoaded', function() {
            // ตรวจสอบว่า session มีการตั้งค่าหรือไม่
            @if (session('status'))
                Swal.fire({
                    icon: '{{ session('status') }}',
                    title: 'สำเร็จ',
                    text: "{{ session('message') }}",
                    confirmButtonText: 'ตกลง'
                });
            @endif
        });
    </script>
</body>
</html>
