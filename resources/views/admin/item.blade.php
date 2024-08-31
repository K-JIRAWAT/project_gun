@extends('layout.admin')
@section('body')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                @if(Auth::user()->role_id == 1)
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">เพิ่มข้อมูล</button>
                    </div>
                @endif
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
                    <div class="col-md-4">
                        <select id="roleFilter" class="form-select">
                            <option value="">เลือกประเภท</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
             
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>รูป</th>
                            <th>ชื่อ</th>
                            {{-- <th>code</th> --}}
                            <th>stock</th>
                            <th>ประเภท</th>
                            <th>แก้ไขล่าสุด</th>
                            @if(Auth::user()->role_id == 1)
                                <th></th>
                            @endif
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


    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">เพิ่มข้อมูลใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code" required>
                            </div> --}}
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" required>
                            </div>
                            <div class="col-md-6">
                                <label for="type" class="form-label">ประเภท</label>
                                <select id="type" class="form-select" name="type" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="image" class="form-label">รูป</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addItem()">เพิ่มข้อมูล</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Edit User Modal -->
    <div class="modal fade" id="edititemModal" tabindex="-1" aria-labelledby="edititemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edititemModalLabel">แก้ไขข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="edititemId">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="editname" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="editname" required>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="editcode" class="form-label">code</label>
                                <input type="text" class="form-control" id="editcode" required>
                            </div> --}}
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editstock" class="form-label">stock</label>
                                <input type="number" class="form-control" id="editstock" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edittype" class="form-label">ประเภท</label>
                                <select id="edittype" class="form-select" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="image" class="form-label">รูป</label>
                                <input type="file" class="form-control" id="edit_image" name="edit_image">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveItem()">บันทึกการแก้ไข</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var userRoleId = @json(Auth::user()->role_id);
        $(document).ready(function() {
            search(); 
            $('#searchInput').on('keyup', function() {
                search(); 
            });

            $('#roleFilter').on('change', function() {
                search(); 
            });

            $('#row_num').on('change', function() {
                search(); 
            });
        });

            $(document).on('click', '.page-link', function(event) {
                event.preventDefault();
                var page = $(this).text(); 
                search(page);
            });


            function search(page = 1) {
                var searchInput = $('#searchInput').val();
                var rolesInput = $('#roleFilter').val();
                var row_num = $('#row_num').val();

                $.ajax({
                    url: '{{ route("item.search") }}',
                    type: 'POST', 
                    data: {
                        _token: '{{ csrf_token() }}',
                        search: searchInput,
                        type: rolesInput,
                        page: page,
                        row_num: row_num
                    },
                    success: function(response) {
                        var items = response.item.data; // Get paginated data
                        var html = '';
                        var image_url = '/image/soldier5.jpg';
                        
                        if (items.length === 0) {
                            if(userRoleId == 1){
                                html += '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
                            }else{
                                html += '<tr><td colspan="7" class="text-center">ไม่พบข้อมูล</td></tr>';
                            }
                        } else {
                            items.forEach(function(item) {
                                var itemImage = item.images ? item.images : image_url;
                                html += '<tr>';
                                html += '<td>' + item.id + '</td>';
                                html += '<td><img src="' + itemImage + '" alt="Item Image" class="item-image"></td>';
                                html += '<td>' + item.name + '</td>';
                                // html += '<td>' + item.code + '</td>';
                                html += '<td>' + item.stock + '</td>';
                                html += '<td>' + item.type_name + '</td>';
                                html += '<td>' + item.updated_at + '</td>';
                                if(userRoleId == 1){
                                    html += '<td>';
                                    html += '<button class="btn btn-sm btn-warning" onclick="editItem(' + item.id + ')"><i class="bi bi-pen"></i></button> ';
                                    html += '<button class="btn btn-sm btn-danger" onclick="deleteItem(' + item.id + ')"><i class="bi bi-trash"></i></button>';
                                    html += '</td>';
                                }
                                html += '</tr>';
                            });
                        }

                        $('#itemTableBody').html(html);

                        // Generate pagination links
                        var paginationHtml = '';

                        if (response.item.links) {
                            console.log(response.item.links); // Log links for debugging
                            response.item.links.forEach(function(link) {
                                // แสดงเฉพาะปุ่มหมายเลขหน้า ไม่รวมปุ่ม Previous และ Next
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


        function editItem(itemId) {
            $.ajax({
                url: '{{ route("item.edit") }}',
                type: 'GET',
                data: {
                    item_id: itemId 
                },
                success: function(response) {
                    var item = response.item;
                    $('#edititemId').val(item.id);
                    $('#editname').val(item.name);
                    // $('#editcode').val(item.code);
                    $('#edittype').val(item.type);
                    $('#editstock').val(item.stock);
                    $('#edititemModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function addItem() {
            var formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('stock', $('#stock').val());
            formData.append('type', $('#type').val());
            formData.append('image', $('#image')[0].files[0]); // เพิ่มรูปภาพ

            $.ajax({
                url: '{{ route("item.add") }}',
                type: 'POST',
                data: formData,
                contentType: false,  // ตั้งค่าเป็น false เมื่อใช้ FormData
                processData: false,  // ตั้งค่าเป็น false เมื่อใช้ FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addItemModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มข้อมูลสำเร็จ',
                    });
                    document.getElementById('addItemForm').reset();
                    search(); // Refresh the item list
                    // location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'การเพิ่มข้อมูลไม่สำเร็จ',
                    });
                }
            });
        }

        function saveItem() {
            var formData = new FormData();
            formData.append('name', $('#editname').val());
            formData.append('stock', $('#editstock').val());
            formData.append('type', $('#edittype').val());
            formData.append('itemId', $('#edititemId').val());
            formData.append('image', $('#edit_image')[0].files[0]); // เพิ่มรูปภาพ
            $.ajax({
                url: '{{ route("item.save") }}',
                type: 'POST',
                data: formData,
                contentType: false,  // ตั้งค่าเป็น false เมื่อใช้ FormData
                processData: false,  // ตั้งค่าเป็น false เมื่อใช้ FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#editItemModal').modal('hide');
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

        function deleteItem(itemId) {
            Swal.fire({
                title: 'คุณต้องการลบข้อมูลนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("item.delete") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            itemId: itemId
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบเรียบร้อยแล้ว',
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
                                text: 'ไม่สามารถลบได้'
                            });
                        }
                    });
                }
            });
        }

    </script>

    <style>
        .item-image {
            width: 50px; /* ขนาดปกติของรูปภาพ */
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
            transition: all 0.3s ease; /* Smooth transition */
        }

        .table-hover tbody tr:hover .item-image {
            cursor: pointer; /* แสดง cursor เป็น pointer เมื่อ hover */
        }

        .table-hover tbody tr:hover .item-image {
            width: 100px; /* ขยายขนาดรูปเมื่อ hover ที่แถว */
            height: 100px;
        }

        .table-hover tbody tr .item-image:hover {
            width: 100%; /* ขยายขนาดรูปเป็น 100% เมื่อ hover ที่รูป */
            height: 100%; /* ทำให้ความสูงเป็นอัตโนมัติ */
            z-index: 1; /* ให้รูปที่ขยายอยู่ด้านบน */
            cursor: pointer; /* แสดง cursor เป็น pointer เมื่อ hover */
        }

        .table-hover tbody tr {
            position: relative; /* ทำให้ parent element เป็น relative */
        }

        .table-hover tbody tr .item-image {
            transition: all 0.3s ease; /* Smooth transition */
        }
    </style>
@endsection