@extends('layout.user')
@section('body')

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">

                <!-- รายการที่เลือก -->
                <div id="selectedItemsContainer" class="mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">แบบฟอร์มขอเบิกจ่าย ยุทโธปกรณ์</h5><br>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="borrowDate" class="form-label">วันที่ยืม:</label>
                                    <input type="date" id="borrowDate" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="returnDate" class="form-label">วันที่คืน:</label>
                                    <input type="date" id="returnDate" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="name_user" class="form-label">ชื่อผู้ยืม:</label>
                                    <input type="text" id="fname_user" value="{{ Auth::user()->firstname }}" class="form-control" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label for="name_user" class="form-label">นามสกุล:</label>
                                    <input type="text" id="lname_user" value="{{ Auth::user()->lastname }}" class="form-control" disabled>
                                    <input type="hidden" id="user_id" value="{{ Auth::user()->id }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="name_user" class="form-label">อีเมลล์ติดต่อ:</label>
                                    <input type="text" id="lname_user" value="{{ Auth::user()->email }}" class="form-control" disabled>
                                </div>
                             
                            </div>

                            <ul id="selectedItemsList" class="list-group">
                                <!-- รายการที่เลือกจะถูกแสดงที่นี่ -->
                            </ul>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="remarks" class="form-label">หมายเหตุ:</label>
                                    <textarea type="text" id="remarks" class="form-control"></textarea>
                                </div>
                                   <!-- ปุ่มเคลียร์ข้อมูล -->
                                <div class="col-md-1 d-flex align-items-end">
                                    <button class="btn btn-secondary w-100" onclick="clearSelectedItems()">Clear</button>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button class="btn btn-success w-100" onclick="submitRequest(1)">Save</button>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button class="btn btn-primary w-100" onclick="submitRequest(2)">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
    
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
                            <th>เลือก</th>
                            <th>ID</th>
                            <th>รูป</th>
                            <th>ชื่อ</th>
                            {{-- <th>code</th> --}}
                            <th>stock</th>
                            <th>ประเภท</th>
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

    <script>
        var selectedItems = [];
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

            $(document).on('change', '.item-checkbox', function() {
                var itemId = $(this).data('id');

                if ($(this).is(':checked')) {
                    var item = {
                        id: itemId,
                        name: $(this).data('name'),
                        stock: $(this).data('stock'),
                        images: $(this).data('images'),
                        type_name: $(this).data('type-name')
                    };

                    selectedItems.push(item); // เพิ่มรายการที่เลือกเข้าไปในอาร์เรย์
                } else {
                    // เอารายการออกจากอาร์เรย์
                    selectedItems = selectedItems.filter(function(item) {
                        return item.id !== itemId;
                    });
                }

                updateSelectedItemsList(); // อัพเดตรายการที่แสดง
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
                            html += '<tr><td colspan="7" class="text-center">ไม่พบข้อมูล</td></tr>';
                        } else {
                            items.forEach(function(item) {
                                var itemImage = item.images ? item.images : image_url;
                                html += '<tr>';
                                html += '<td><input type="checkbox" class="item-checkbox" data-id="' + item.id + '" data-name="' + item.name + '" data-stock="' + item.stock + '" data-images="' + item.images + '" data-type-name="' + item.type_name + '"></td>';
                                html += '<td>' + item.id + '</td>';
                                html += '<td><img src="' + itemImage + '" alt="Item Image" class="item-image"></td>';
                                html += '<td>' + item.name + '</td>';
                                // html += '<td>' + item.code + '</td>';
                                html += '<td>' + item.stock + '</td>';
                                html += '<td>' + item.type_name + '</td>';
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

                        updateCheckboxes();
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Debugging: Log the error
                    }
                });
            }

        function updateCheckboxes() {   
            $('.item-checkbox').each(function() {
                var itemId = $(this).data('id');
                var isChecked = selectedItems.some(function(selectedItem) {
                    return selectedItem.id === itemId;
                });

                $(this).prop('checked', isChecked);
            });
        }

        function getPageFromUrl(url) {
            var urlParams = new URLSearchParams(new URL(url).search);
            return urlParams.get('page');
        }

        function updateSelectedItemsList() {
            var html = '';
            selectedItems.forEach(function(item) {
                var itemImage = item.images ? item.images : '/path/to/default/image.jpg'; // ใช้รูปภาพเริ่มต้นหากไม่มีรูปภาพ
                html += `
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${item.id}">
                    <div class="d-flex align-items-center">
                        <img src="${itemImage}" alt="${item.name}" class="item-image me-3">
                        <div>
                            <h6 class="mb-1">${item.name}</h6>
                            <small class="text-muted">ประเภท: ${item.type_name || 'ไม่ระบุ'}</small>
                        </div>
                    </div>
                    <div class="ms-3">
                        <span class="text-muted">Stock: ${item.stock}</span>
                        <input type="number" class="form-control form-control-sm quantity-input" value="" min="1" max="${item.stock}">
                    </div>
                </li>`;
            });

            $('#selectedItemsList').html(html);
        }


        // $(document).on('change', '.item-checkbox', function() {
        //     updateSelectedItems();
        // });

        // $(document).on('input', '.quantity-input', function() {
        //     updateSelectedItems();
        // });

        function submitRequest(check) {
            var borrowDate = $('#borrowDate').val();
            var returnDate = $('#returnDate').val();
            var remarks = $('#remarks').val();
            var user_id = $('#user_id').val();

            var selectedItems = [];
            $('#selectedItemsList li').each(function() { // ใช้ li เพื่อค้นหา data-id
                var quantity = $(this).find('.quantity-input').val() || 0; // ดึงค่า quantity
                var id = $(this).data('id'); // ดึง id จาก data-id
                selectedItems.push({
                    id: id,
                    quantity: quantity,
                });
            });

             // ตรวจสอบว่ามีค่าที่ต้องกรอกหรือไม่
             if (!borrowDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกวันที่ยืม',
                });
                return; // หยุดการทำงานถ้ายังไม่ได้กรอกข้อมูล
            }

            if (!returnDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกวันที่คืน',
                });
                return;
            }

            if (!remarks) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกหมายเหตุ',
                });
                return;
            }

            // ตรวจสอบว่า selectedItems มีค่าหรือไม่
            if (selectedItems.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่มีรายการที่เลือก',
                    text: 'กรุณาเลือกอุปกรณ์หรือรายการที่ต้องการเบิกก่อน',
                });
                return false; 
            }

            $.ajax({
                url: '{{ route("home.save") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: selectedItems,
                    user_id: user_id,
                    borrow_date: borrowDate,
                    remarks: remarks,
                    return_date: returnDate,
                    check: check
                },
                success: function(response) {
                    console.log(check);
                    if(check == 2){
                        var text = 'ส่งคำร้องสำเร็จ'
                    }else{
                        var text = 'บันทึกสำเร็จ'
                    }
                    Swal.fire({
                        icon: 'success',
                        title: text,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/user/edit/' + response; 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }


        function clearSelectedItems() {
            selectedItems = []; // เคลียร์รายการที่เลือกทั้งหมด
            $('#selectedItemsList').html(''); // เคลียร์รายการใน UI
            $('.item-checkbox').prop('checked', false); // ยกเลิกการเลือก checkbox ทั้งหมด
            $('#borrowDate').val('');
            $('#returnDate').val('');
            $('#remarks').val('');
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            var day = ('0' + date.getDate()).slice(-2);
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            return day + '/' + month + '/' + year;
        }

        function updateFormattedDate(inputId) {
            var dateInput = document.getElementById(inputId);
            var formattedDate = formatDate(dateInput.value);
            var formattedDateElement = document.getElementById('formatted' + inputId.charAt(0).toUpperCase() + inputId.slice(1));
            formattedDateElement.textContent = formattedDate ? `วันที่เลือก: ${formattedDate}` : '';
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