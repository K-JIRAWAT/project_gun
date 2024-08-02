@extends('layout.admin')
@section('body')

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">เพิ่มผู้ใช้งาน</button>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="searchInput" class="form-control" placeholder="ค้นหา...">
                    </div>
                    <div class="col-md-4">
                        <select id="roleFilter" class="form-select">
                            <option value="">เลือกสิทธิ์การใช้งาน</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
     
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อจริง</th>
                            <th>นามสกุล</th>
                            <th>ชื่อผู้ใช้งาน</th>
                            <th>อีเมลล์</th>
                            <th>หน่วยงาน</th>
                            <th>สิทธิ์การใช้งาน</th>
                            <th>แก้ไขล่าสุด</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">เพิ่มผู้ใช้งาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="addFirstname" class="form-label">ชื่อจริง</label>
                                <input type="text" class="form-control" id="addFirstname" required>
                            </div>
                            <div class="col-md-6">
                                <label for="addLastname" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="addLastname" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="addUsername" class="form-label">ชื่อผู้ใช้งาน</label>
                                <input type="text" class="form-control" id="addUsername" required>
                            </div>
                            <div class="col-md-6">
                                <label for="addRole" class="form-label">สิทธิ์การใช้งาน</label>
                                <select id="addRole" class="form-select" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="addEmail" class="form-label">อีเมลล์</label>
                                <input type="email" class="form-control" id="addEmail" required>
                            </div>
                            <div class="col-md-6">
                                <label for="addPassword" class="form-label">Password</label>
                                <input type="email" class="form-control" id="addPassword" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addUser()">บันทึกข้อมูล</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editFirstname" class="form-label">ชื่อจริง</label>
                                <input type="text" class="form-control" id="editFirstname" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editLastname" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="editLastname" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editUsername" class="form-label">ชื่อผู้ใช้งาน</label>
                                <input type="text" class="form-control" id="editUsername" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editRole" class="form-label">สิทธิ์การใช้งาน</label>
                                <select id="editRole" class="form-select" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">อีเมลล์</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveUser()">บันทึกการแก้ไข</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            search(); 
            $('#searchInput').on('keyup', function() {
                search(); 
            });

            $('#roleFilter').on('change', function() {
                search(); 
            });
        });

        function search() {
            var searchInput = $('#searchInput').val();
            var rolesInput = $('#roleFilter').val();

            $.ajax({
                url: '{{ route("user.search") }}',
                type: 'POST', 
                data: {
                    _token: '{{ csrf_token() }}',
                    search: searchInput,
                    role: rolesInput 
                },
                success: function(response) {
                    var users = response.user;
                    var html = '';

                    if (users.length === 0) {
                        html += '<tr><td colspan="6" class="text-center">ไม่พบข้อมูล</td></tr>';
                    } else {
                        users.forEach(function(user) {
                            html += '<tr>';
                            html += '<td>' + user.id + '</td>';
                            html += '<td>' + user.firstname + '</td>';
                            html += '<td>' + user.lastname + '</td>';
                            html += '<td>' + user.username + '</td>';
                            html += '<td>' + user.email + '</td>';
                            html += '<td>' + user.sector_name + '</td>';
                            html += '<td>' + user.role_name + '</td>';
                            html += '<td>' + user.updated_at + '</td>';
                            html += '<td>';
                            html += '<button class="btn btn-sm btn-warning" onclick="editUser(' + user.id + ')">แก้ไข</button> ';
                            html += '<button class="btn btn-sm btn-danger" onclick="deleteUser(' + user.id + ')">ลบ</button>';
                            html += '</td>';
                            html += '</tr>';
                        });
                    }

                    $('#userTableBody').html(html);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function addUser() {
            $.ajax({
                url: '{{ route("user.add") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    firstname: $('#addFirstname').val(),
                    lastname: $('#addLastname').val(),
                    username: $('#addUsername').val(),
                    email: $('#addEmail').val(),
                    password: $('#addPassword').val(),
                    role: $('#addRole').val()
                },
                success: function(response) {
                    $('#addUserModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มผู้ใช้งานสำเร็จ',
                    });
                    search(); 
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'การเพิ่มไม่สำเร็จ',
                    });
                }
            });
        }

        function editUser(userId) {
            $.ajax({
                url: '{{ route("user.edit") }}',
                type: 'GET',
                data: {
                    user_id: userId 
                },
                success: function(response) {
                    var user = response.user;
                    $('#editUserId').val(user.id);
                    $('#editFirstname').val(user.firstname);
                    $('#editLastname').val(user.lastname);
                    $('#editUsername').val(user.username);
                    $('#editEmail').val(user.email);
                    $('#editRole').val(user.role_id);
                    $('#editUserModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function saveUser() {
            $.ajax({
                url: '{{ route("user.save") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    firstname: $('#editFirstname').val(),
                    lastname: $('#editLastname').val(),
                    username: $('#editUsername').val(),
                    email: $('#editEmail').val(),
                    userId: $('#editUserId').val(),
                    role: $('#editRole').val()
                },
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขสำเร็จ',
                    });
                    search(); 
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'การแก้ไขไม่สำเร็จ',
                    });
                }
            });
        }

        function deleteUser(userId) {
            Swal.fire({
                title: 'คุณต้องการลบผู้ใช้งานนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("user.delete") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            userId: userId
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบผู้ใช้งานเรียบร้อยแล้ว',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            search();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถลบผู้ใช้งานได้'
                            });
                        }
                    });
                }
            });
        }

    </script>
@endsection
