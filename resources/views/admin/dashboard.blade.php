@extends('layout.admin')
@section('body')
<style>
        /* CSS Animation สำหรับการสั่น */
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        .shake {
            animation: shake 0.5s infinite;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            opacity: 0.7;
            position: relative;
        }
        .card-custom::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 123, 255, 0.2);
            z-index: 0;
            transition: background 0.3s ease;
        }
        .card-custom:hover::before {
            background: rgba(0, 123, 255, 0.4);
        }
        .card-custom:hover {
            transform: translateY(-10px);
            opacity: 1;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        .card-header-custom {
            background-color: #2da1ff;
            color: white;
            border-bottom: none;
            padding: 1rem;
            position: relative;
        }
        .card-body-custom {
            background-color: #ffffff;
            text-align: center;
            padding: 2rem;
            position: relative;
        }
        .card-body-custom h2 {
            font-size: 3rem;
            margin: 0;
            font-weight: bold;
        }
        .card-footer-custom {
            background-color: #f8f9fa;
            border-top: none;
            padding: 1rem;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
</style>


    <div class="container mt-2">
        <div class="row">
            <div class="container mt-5">
                <div class="row justify-content-center">

                    <div class="col-md-4">
                        <div class="card card-custom" id="BorrowCount_new">
                            <div class="card-header card-header-custom">
                                <h5 class="card-title mb-0">คำร้อง รออนุมัติ</h5>
                            </div>
                            <div class="card-body card-body-custom">
                                <h2>-</h2>
                                <p>จำนวนคำร้องทั้งหมด</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-custom" id="BorrowCount_now">
                            <div class="card-header card-header-custom">
                                <h5 class="card-title mb-0">คำร้อง ที่กำลังดำเนินการ</h5>
                            </div>
                            <div class="card-body card-body-custom">
                                <h2>-</h2>
                                <p>จำนวนคำร้องทั้งหมด</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-custom"  id="userCountCard">
                            <div class="card-header card-header-custom">
                                <h5 class="card-title mb-0">คำร้องสมัครเข้าใช้งาน</h5>
                            </div>
                            <div class="card-body card-body-custom">
                                <h2>-</h2>
                                <p>จำนวนคำร้องทั้งหมด</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0">คำร้องเบิกจ่ายยุทโธปกรณ์</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-1">
                        <select id="row_num" class="form-select">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="text" id="searchInput" class="form-control" placeholder="ค้นหา...">
                    </div>
                    <div class="col-md-2">
                        <select id="statusFilter" class="form-select">
                            <option value="">สถานะ</option>
                            @foreach($status as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="monthFilter" class="form-select">
                            <option value="">เดือน</option>
                            <option value="1">มกราคม</option>
                            <option value="2">กุมภาพันธ์</option>
                            <option value="3">มีนาคม</option>
                            <option value="4">เมษายน</option>
                            <option value="5">พฤษภาคม</option>
                            <option value="6">มิถุนายน</option>
                            <option value="7">กรกฎาคม</option>
                            <option value="8">สิงหาคม</option>
                            <option value="9">กันยายน</option>
                            <option value="10">ตุลาคม</option>
                            <option value="11">พฤศจิกายน</option>
                            <option value="12">ธันวาคม</option>
                        </select>
                    </div>
                </div>
                <table class="table table-hover" id="itemRequestsTable">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เบิกโดย</th>
                            <th>เลขที่อ้างอิง</th>
                            <th>สถานะ</th>
                            <th>สร้างเมื่อ</th>
                            <th>Update ล่าสุด</th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody">
                    
                    </tbody>
                </table>
                <nav aria-label="Page navigation" style="float: left">
                    <ul class="pagination" id="paginationLinks"></ul>
                </nav>
            </div>
        </div>
    </div>


    <div class="container mt-4">
        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0">คำร้องสมัครเข้าใช้งาน</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="userRequestsTable">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อีเมล</th>
                            <th>หน่วยงาน</th>
                            <th>วันที่สมัคร</th>
                            @if(Auth::user()->role_id == 1)
                                <th>สิทธิ์การใช้งาน</th>
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                    
                    </tbody>
                </table>
                <nav aria-label="Page navigation" style="float: left">
                    <ul class="pagination" id="paginationLinks"></ul>
                </nav>
            </div>
        </div>
    </div>

   

    <script>
        var userRoleId = @json(Auth::user()->role_id);
        document.getElementById('userCountCard').addEventListener('click', function() {
            var table = document.getElementById('userRequestsTable');
            table.classList.add('shake');
            table.scrollIntoView({ behavior: 'smooth' });
        });

        // หยุดการสั่นเมื่อมีการชี้ที่ตาราง
        document.getElementById('userRequestsTable').addEventListener('mouseover', function() {
            this.classList.remove('shake');
        });

        $(document).ready(function() {
            user_search();
            updateCount();

            item_search(); 
            $('#searchInput').on('keyup', function() {
                item_search(); 
            });

            $('#statusFilter').on('change', function() {
                item_search(); 
            });

            $('#row_num').on('change', function() {
                item_search(); 
            });

            $('#monthFilter').on('change', function() {
                item_search();
            });
        });

        function updateCount() {
            $.ajax({
                url: '{{ route("dashboard.user_count") }}', // สมมติว่ามี route ที่ให้บริการจำนวนคำร้อง
                type: 'POST', 
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // อัปเดตการ์ดด้วยจำนวนคำร้องใหม่c
                    $('#userCountCard h2').text(response.count_user);
                    $('#BorrowCount_new h2').text(response.count_borrow_new);
                    $('#BorrowCount_now h2').text(response.count_borrow_now);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            var day = String(date.getDate()).padStart(2, '0');
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var year = date.getFullYear();

            return `${day}/${month}/${year}`;
        }

        function user_search() {
            $.ajax({
                url: '{{ route("dashboard.user_search") }}',
                type: 'POST', 
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var users = response.user;
                    var roles = response.roles;
                    var html = '';

                    if (users.length === 0) {
                        if(userRoleId == 1){
                            html += '<tr><td colspan="7" class="text-center">ไม่มีคำร้อง</td></tr>';
                        }else{
                            html += '<tr><td colspan="5" class="text-center">ไม่มีคำร้อง</td></tr>';
                        }
                    } else {
                        users.forEach(function(user) {
                            html += '<tr>';
                            html += '<td>' + user.id + '</td>';
                            html += '<td>' + user.firstname + ' ' + user.lastname +'</td>';
                            html += '<td>' + user.email + '</td>';
                            html += '<td>' + user.sector_name + '</td>';
                            html += '<td>' + formatDate(user.updated_at) + '</td>';
                            if(userRoleId == 1){
                                html += '<td>';
                                html += '<select id="userRoleDropdown-' + user.id + '" class="form-select form-select-sm">';
                                html += '<option value="" disabled selected>กรุณาเลือกสิทธ์การใช้งาน</option>';
                                roles.forEach(function(role) {
                                    html += '<option value="' + role.id + '"' + (user.role_id === role.id ? ' selected' : '') + '>' + role.name + '</option>';
                                });
                                html += '</select>';
                                html += '</td>';
                                html += '<td>';
                                html += '<button class="btn btn-sm btn-success" onclick="user_accept(' + user.id + ')"><i class="bi bi-check-lg"></i></button> ';
                                html += '<button class="btn btn-sm btn-danger" onclick="user_reject(' + user.id + ')"><i class="bi bi-x-lg"></i></button>';
                                html += '</td>';
                            }
                            html += '</tr>';
                        });
                    }

                    $('#userTableBody').html(html);

                    updateCount();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function user_accept(userId) {
            var userRole = $('#userRoleDropdown-' + userId).val();
            if (!userRole) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกสิทธิ์การใช้งาน!',
                    // text: 'กรุณาเลือกสิทธิ์การใช้งานก่อนทำการอนุมัติ',
                });
                return; // ยกเลิกฟังก์ชันถ้าไม่ได้เลือก role
            }
            Swal.fire({
                title: 'อนุมัตคำร้องหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, อนุมัต!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("dashboard.user_accept") }}',
                        type: 'GET',
                        data: {
                            user_id: userId,
                            role: userRole
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ยืนยันสำเร็จ!',
                            });
                            user_search(); 
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถดำเนินการได้',
                            });
                        }
                    });
                }
            })
        }

        function user_reject(userId) {
            Swal.fire({
                title: 'ปฎิเสธคำร้องหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ปฎิเสธ!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("dashboard.user_reject") }}',
                        type: 'GET',
                        data: {
                            user_id: userId 
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ปฎิเสธสำเร็จสำเร็จ!',
                            });
                            user_search(); 
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถดำเนินการได้',
                            });
                        }
                    });
                }
            })
        }

            
        $(document).on('click', '.page-link', function(event) {
            event.preventDefault();
            var page = $(this).text(); 
            item_search(page);
        });

        function item_search(page = 1) {
            var searchInput = $('#searchInput').val();
            var StatusInput = $('#statusFilter').val();
            var row_num = $('#row_num').val();
            var month = $('#monthFilter').val();

            $.ajax({
                url: '{{ route("admin_list.search") }}',
                type: 'POST', 
                data: {
                    _token: '{{ csrf_token() }}',
                    search: searchInput,
                    status: StatusInput,
                    page: page,
                    row_num: row_num,
                    month: month
                },
                success: function(response) {
                    var list = response.list.data; // Get paginated data
                    var html = '';
                    var perPage = $('#row_num').val()

                    if (list.length === 0) {
                        html += '<tr><td colspan="6" class="text-center">ไม่พบข้อมูล</td></tr>';
                    } else {
                        list.forEach(function(item, i) {
                            var index = (page - 1) * perPage + (i + 1);
                            html += '<tr onclick="goToEditPage(\'' + item.request_no + '\')">';
                            html += '<td>' + index + '</td>';
                            html += '<td>' + item.fname_borrow + ' ' + item.lname_borrow +'</td>';
                            html += '<td>' + item.request_no + '</td>';
                            html += '<td>' + item.status_name + '</td>';
                            html += '<td>' + formatDate(item.created_at) + '</td>';
                            html += '<td>' + formatDate(item.updated_at) + '</td>';
                            html += '</tr>';
                        });

                    }

                    $('#itemTableBody').html(html);

                    // Generate pagination links
                    var paginationHtml = '';

                    if (response.list.links) {
                        response.list.links.forEach(function(link) {
                            if (link.url && link.label !== '&laquo; Previous' && link.label !== 'Next &raquo;') {
                                var pageNum = getPageFromUrl(link.url);
                                paginationHtml += '<li class="page-item ' + (link.active ? 'active' : '') + '">';
                                paginationHtml += '<a class="page-link" href="#" onclick="search(' + pageNum + '); return false;">' + link.label + '</a>';
                                paginationHtml += '</li>';
                            }
                        });
                    }
                    $('#paginationLinks').html(paginationHtml);

                },
                error: function(xhr, status, error) {
                    console.error(error); // Debugging: Log the error
                }
            });
        }

        function getPageFromUrl(url) {
            var urlParams = new URLSearchParams(new URL(url).search);
            return urlParams.get('page');
        }

            
        function goToEditPage(requestNo) {
            window.location.href = '/user/edit/' + requestNo;
        }


    </script>
@endsection