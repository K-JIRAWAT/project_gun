@extends('layout.admin')
@section('body')

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">เพิ่มข้อมูล</button>
                </div>
                <div class="row mb-3">
                    <div class="col-md-8">
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
                            <th>code</th>
                            <th>stock</th>
                            <th>ประเภท</th>
                            <th>แก้ไขล่าสุด</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody">
                    
                    </tbody>
                </table>
                <div id="pagination"></div>
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
                            <div class="col-md-6">
                                <label for="name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code" required>
                            </div>
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
                                <input type="file" class="form-control" id="image" name="image" required disabled>
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
                            <div class="col-md-6">
                                <label for="editname" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="editname" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editcode" class="form-label">code</label>
                                <input type="text" class="form-control" id="editcode" required>
                            </div>
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
                url: '{{ route("item.search") }}',
                type: 'POST', 
                data: {
                    _token: '{{ csrf_token() }}',
                    search: searchInput,
                    type: rolesInput 
                },
                success: function(response) {
                    var items = response.item;
                    var html = '';
                    var image_url = '/image/soldier5.jpg';
                    if (items.length === 0) {
                        html += '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
                    } else {
                        items.forEach(function(item) {
                            var itemImage = item.images ? item.images : image_url; // Use placeholder image if item.images is empty
                            html += '<tr>';
                            html += '<td>' + item.id + '</td>';
                            html += '<td><img src="' + itemImage + '" alt="Item Image" class="item-image"></td>';
                            html += '<td>' + item.name + '</td>';
                            html += '<td>' + item.code + '</td>';
                            html += '<td>' + item.stock + '</td>';
                            html += '<td>' + item.type_name + '</td>';
                            html += '<td>' + item.updated_at + '</td>';
                            html += '<td>';
                            html += '<button class="btn btn-sm btn-warning" onclick="editItem(' + item.id + ')">แก้ไข</button> ';
                            html += '<button class="btn btn-sm btn-danger" onclick="deleteItem(' + item.id + ')">ลบ</button>';
                            html += '</td>';
                            html += '</tr>';
                        });
                    }

                    $('#itemTableBody').html(html);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // function search(page = 1) {
        //     var searchInput = $('#searchInput').val();
        //     var rolesInput = $('#roleFilter').val();
        //     var perPage = 10; // Number of items per page

        //     $.ajax({
        //         url: '{{ route("item.search") }}',
        //         type: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}',
        //             search: searchInput,
        //             type: rolesInput,
        //             page: page,
        //             perPage: perPage
        //         },
        //         success: function(response) {
        //             var items = response.data; // 'data' contains the paginated items
        //             var html = '';
        //             console.log(response.last_page);
        //             if (items.length === 0) {
        //                 html += '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
        //             } else {
        //                 items.forEach(function(item) {
        //                     html += '<tr>';
        //                     html += '<td>' + item.id + '</td>';
        //                     html += '<td><img src="' + item.images + '" alt="Item Image" class="item-image"></td>';
        //                     html += '<td>' + item.name + '</td>';
        //                     html += '<td>' + item.code + '</td>';
        //                     html += '<td>' + item.stock + '</td>';
        //                     html += '<td>' + item.type_name + '</td>';
        //                     html += '<td>' + item.updated_at + '</td>';
        //                     html += '<td>';
        //                     html += '<button class="btn btn-sm btn-warning" onclick="editItem(' + item.id + ')">แก้ไข</button> ';
        //                     html += '<button class="btn btn-sm btn-danger" onclick="deleteItem(' + item.id + ')">ลบ</button>';
        //                     html += '</td>';
        //                     html += '</tr>';
        //                 });
        //             }

        //             $('#itemTableBody').html(html);

        //             // Handle pagination links
        //             var paginationHtml = '';
        //             for (var i = 1; i <= response.last_page; i++) {
        //                 paginationHtml += '<a href="#" class="page-link" onclick="search(' + i + ')">' + i + '</a> ';
        //             }
        //             $('#pagination').html(paginationHtml);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error(error);
        //         }
        //     });
        // }


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
                    $('#editcode').val(item.code);
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
            var formData = new FormData(document.getElementById('addItemForm'));

            $.ajax({
                url: '{{ route("item.add") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#addItemModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มข้อมูลสำเร็จ',
                    });
                    document.getElementById('addItemForm').reset();
                    // search(); // Refresh the item list
                    location.reload(); // Refresh the entire page
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
            $.ajax({
                url: '{{ route("item.save") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#editname').val(),
                    code: $('#editcode').val(),
                    stock: $('#editstock').val(),
                    type: $('#edittype').val(),
                    itemId: $('#edititemId').val()
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
                                title: 'ลบข้อมูลเรียบร้อยแล้ว',
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

    <style>
        .item-image {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }
    </style>
@endsection