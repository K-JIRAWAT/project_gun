@extends( $layout )
@section('body')
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">

                <!-- รายการที่เลือก -->
                <div id="selectedItemsContainer" class="mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">แบบฟอร์มขอเบิกจ่าย ยุทโธปกรณ์</h5>
                                <div class="d-flex align-items-center">
                                    <span class="me-2" style="font-size: 15px;">เลขที่อ้างอิง: {{ $list->request_no }}</span>
                                    <span style="font-size: 15px" class="badge 
                                        @if($list->status_name == 'รอการอนุมัติ') bg-warning 
                                        @elseif($list->status_name == 'อนุมัติ') bg-success 
                                        @elseif($list->status_name == 'ไม่อนุมัติ') bg-danger 
                                        @else bg-secondary 
                                        @endif">
                                        {{ $list->status_name }}
                                    </span>
                                </div>
                            </div><br>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="borrowDate" class="form-label">วันที่ยืม:</label>
                                    <input type="date" id="borrowDate" value="{{ $list->borrow_date }}" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="returnDate" class="form-label">วันที่คืน:</label>
                                    <input type="date" id="returnDate" value="{{ $list->return_date }}" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="name_user" class="form-label">ชื่อผู้ยืม:</label>
                                    <input type="text" id="fname_user" value="{{ $user->firstname }}" class="form-control" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label for="name_user" class="form-label">นามสกุล:</label>
                                    <input type="text" id="lname_user" value="{{ $user->lastname }}" class="form-control" disabled>
                                    <input type="hidden" id="user_id" value="{{ $user->id }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="name_user" class="form-label">อีเมลล์ติดต่อ:</label>
                                    <input type="text" id="lname_user" value="{{ Auth::user()->email }}" class="form-control" disabled>
                                </div>
                             
                            </div>

                            {{-- <ul id="selectedItemsList" class="list-group">
                                <!-- รายการที่เลือกจะถูกแสดงที่นี่ -->
                            </ul> --}}
                            <ul id="selectedItemsList" class="list-group">
                                @foreach($selectedItems as $selectedItem)
                                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $selectedItem->id }}">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $selectedItem->images ?? '/path/to/default/image.jpg' }}" alt="{{ $selectedItem->item_name }}" class="item-image me-3">
                                        <div>
                                            <h6 class="mb-1">{{ $selectedItem->item_name }}</h6>
                                            <small class="text-muted">ประเภท: {{ $selectedItem->type_name ?? 'ไม่ระบุ' }}</small>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-muted">Stock: {{ $selectedItem->stock }}</span>
                                        <input type="number" class="form-control form-control-sm quantity-input" value="{{ $selectedItem->borrow_num ?? 1 }}" min="1" max="{{ $selectedItem->stock }}">
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="remarks" class="form-label">หมายเหตุ:</label>
                                    <textarea id="remarks" class="form-control">{{ $list->remark }}</textarea>
                                </div>
                                <!-- ปุ่มเคลียร์ข้อมูล -->
                                @if(Auth::user()->role_id == 3)
                                    @if( $list->status_name  == 'แบบร่าง' ||  $list->status_name  == 'แก้ไข')
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button class="btn btn-secondary w-100" onclick="clearSelectedItems()">Clear</button>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button class="btn btn-success w-100" onclick="submitRequest(1)">Save</button>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button class="btn btn-primary w-100" onclick="submitRequest(2)">Send</button>
                                        </div>
                                    @endif
                                    @if( $list->status_name  == 'ผ่านการอนุมัติ')
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-danger w-100" onclick="check_btn()">ส่งคืนเช็คสภาพ</button>
                                        </div>
                                    @endif
                                    @if( $list->status_name == 'รอการอนุมัติ')
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-danger w-100" onclick="cancel()">ยกเลิกคำร้อง</button>
                                        </div>
                                    @endif
                                @endif
                                @if((Auth::user()->role_id == 1))
                                    @if( $list->status_name == 'รอการอนุมัติ')
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button class="btn btn-warning w-100" onclick="fix()">แก้ไข</button>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button class="btn btn-success w-100" onclick="approve()">ผ่าน</button>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button class="btn btn-danger w-100" onclick="reject()">ไม่ผ่าน</button>
                                        </div>
                                    @endif
                                    @if( $list->status_name == 'ส่งคืนเช็คสภาพ')
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-success w-100" onclick="close_list()">ปิดคำร้อง</button>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                @if(Auth::user()->role_id == 3)

                    @if( $list->status_name  == 'แบบร่าง' ||  $list->status_name  == 'แก้ไข')
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
                                    <th>code</th>
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
                    @endif
                @endif
            </div>
            <div class="container">
                <div class="card">
                    <div class="card-header card-header-custom">
                        <h5 class="card-title mb-0">ประวัติการดำเนินรายการ</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="userRequestsTable">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>สถานะ</th>
                                    <th>ความคิดเห็น</th>
                                    <th>เมื่อ</th>
                                    <th>ดำเนินการโดย</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="LogTableBody">
                            
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation" style="float: left">
                            <ul class="pagination" id="paginationLinks"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="reject_Modal" tabindex="-1" aria-labelledby="reject_Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reject_Label">กรุณาใส่หมายเหตุ ที่ไม่อนุมัติ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="reject_Text" class="form-control" rows="3" placeholder="หมายเหตุ..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="confirm_reject()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="FixModal" tabindex="-1" aria-labelledby="fixLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fixLabel">กรุณาใส่หมายเหตุ ส่งแก้ไข</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="fixText" class="form-control" rows="3" placeholder="หมายเหตุ..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="confirmFix()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="close_Modal" tabindex="-1" aria-labelledby="close_Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="close_Label">กรุณาใส่ผลการตรวจเช็ค</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="close_Text" class="form-control" rows="3" placeholder="ผมการตรวจ..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="confirm_close()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

      <!-- Modal HTML -->
    <div class="modal fade" id="cancel_Modal" tabindex="-1" aria-labelledby="cancel_Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancel_Label">กรุณาใส่เหตุผลที่ยกเลิก</h5>
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="cancel_Text" class="form-control" rows="3" placeholder="เหตุผล..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="confirm_cancel()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <script>
       

        var userRoleId = @json(Auth::user()->role_id);
        var selectedItems = [];
            $(document).ready(function() {
                var statusName = '{{ $list->status_name }}';
                if (statusName !== 'แบบร่าง' && statusName !== 'แก้ไข') {
                    disableAllInputs();
                }
                if (statusName == 'รอการอนุมัติ') {
                    $('#cancel_Text').removeAttr('disabled');
                }
                if (userRoleId === 1) {
                    $('input').attr('disabled', true);
                    $('select').attr('disabled', true);
                    $('textarea').attr('disabled', true);
                    $('#fixText').removeAttr('disabled');
                    $('#reject_Text').removeAttr('disabled');
                    $('#close_Text').removeAttr('disabled');
                }
                log_search();
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
                document.getElementById('logout-button').addEventListener('click', function(e) {
                    e.preventDefault(); // ป้องกันการโหลดใหม่อัตโนมัติ
                    document.getElementById('logout-form').submit();
                });
            });

            function disableAllInputs() {
                // ปิดใช้งาน input ทั้งหมดภายใน form
                $('input').attr('disabled', true);
                $('select').attr('disabled', true);
                $('textarea').attr('disabled', true);
                // $('button').attr('disabled', true);
                console.log(userRoleId);
                if (userRoleId === 1) {
                    $('#fixText').removeAttr('disabled');
                    $('#reject_Text').removeAttr('disabled');
                }
            }

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
                            html += '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
                        } else {
                            items.forEach(function(item) {
                                var itemImage = item.images ? item.images : image_url;
                                html += '<tr>';
                                html += '<td><input type="checkbox" class="item-checkbox" data-id="' + item.id + '" data-name="' + item.name + '" data-stock="' + item.stock + '" data-images="' + item.images + '" data-type-name="' + item.type_name + '"></td>';
                                html += '<td>' + item.id + '</td>';
                                html += '<td><img src="' + itemImage + '" alt="Item Image" class="item-image"></td>';
                                html += '<td>' + item.name + '</td>';
                                html += '<td>' + item.code + '</td>';
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


        function submitRequest(check) {
            var borrowDate = $('#borrowDate').val();
            var returnDate = $('#returnDate').val();
            var remarks = $('#remarks').val();
            var user_id = $('#user_id').val();
            var requestNo = '{{ $list->request_no }}';

            var selectedItems = [];
            $('#selectedItemsList li').each(function() { // ใช้ li เพื่อค้นหา data-id
                var quantity = $(this).find('.quantity-input').val() || 0; // ดึงค่า quantity
                var id = $(this).data('id'); // ดึง id จาก data-id
                selectedItems.push({
                    id: id,
                    quantity: quantity,
                });
            });

            $.ajax({
                url: '{{ route("edit.save") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: selectedItems,
                    user_id: user_id,
                    borrow_date: borrowDate,
                    remarks: remarks,
                    return_date: returnDate,
                    request_no: requestNo,
                    check: check
                },
                success: function(response) {
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
                            location.reload(); // รีเฟรชหน้าเว็บ
                        }
                    });
                    // $('#selectedItemsList').html('');
                    $('.item-checkbox').prop('checked', false);
                    // clearSelectedItems();
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

        function log_search() {
            var requestNo = '{{ $list->request_no }}';
            $.ajax({
                url: '{{ route("log.search") }}',
                type: 'POST', 
                data: {
                    _token: '{{ csrf_token() }}',
                    request_no: requestNo
                },
                success: function(response) {
                    var items = response.log; // Get paginated data
                    var html = '';
                    var index = 1;
                    
                    if (items.length === 0) {
                        html += '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
                    } else {
                        items.forEach(function(item) {
                            html += '<tr>';
                            html += '<td>' + index + '</td>';
                            html += '<td>' + item.status_name + '</td>';
                            html += '<td>' + (item.remarks ? item.remarks : '-') + '</td>';
                            html += '<td>' + item.created_at + '</td>';
                            html += '<td>' + item.role_name + ': ' + item.user_fname + ' ' + item.user_lname +'</td>';
                            html += '</tr>';
                            index++
                        });

                    }

                    $('#LogTableBody').html(html);

                },
                error: function(xhr, status, error) {
                    console.error(error); // Debugging: Log the error
                }
            });
        }

        function fix() {
            var FixModal = new bootstrap.Modal(document.getElementById('FixModal'));
            FixModal.show();
        }

        function confirmFix() {
            var Fix = document.getElementById('fixText').value;
            if (!Fix) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาใส่หมายเหตุ',
                });
                return;
            }
            var FixModal = bootstrap.Modal.getInstance(document.getElementById('FixModal'));
            FixModal.hide();
            submitWithFix(Fix);
        }

        function submitWithFix(Fix) {
            var requestNo = '{{ $list->request_no }}';
            $.ajax({
                url: '{{ route("dashboard.list_fix") }}', 
                type: 'GET',
                data: {
                    remarks: Fix,
                    request_no: requestNo,
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ดำเนินการสำเร็จ',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function reject() {
            var reject_Modal = new bootstrap.Modal(document.getElementById('reject_Modal'));
            reject_Modal.show();
        }

        function confirm_reject() {
            var reject = document.getElementById('reject_Text').value;
            if (!reject) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาใส่หมายเหตุ',
                });
                return;
            }
            var reject_Modal = bootstrap.Modal.getInstance(document.getElementById('reject_Modal'));
            reject_Modal.hide();
            submitWithReject(reject);
        }

        function submitWithReject(Fix) {
            var requestNo = '{{ $list->request_no }}';
            $.ajax({
                url: '{{ route("dashboard.list_reject") }}', 
                type: 'GET',
                data: {
                    remarks: Fix,
                    request_no: requestNo,
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ดำเนินการสำเร็จ',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function close_list() {
            var close_Modal = new bootstrap.Modal(document.getElementById('close_Modal'));
            close_Modal.show();
        }

        function confirm_close() {
            var close = document.getElementById('close_Text').value;
            if (!close) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาใส่หมายเหตุ',
                });
                return;
            }
            var close_Modal = bootstrap.Modal.getInstance(document.getElementById('close_Modal'));
            close_Modal.hide();
            submitWithclose(close);
        }

        function submitWithclose(Fix) {
            var requestNo = '{{ $list->request_no }}';
            $.ajax({
                url: '{{ route("dashboard.close") }}', 
                type: 'GET',
                data: {
                    remarks: Fix,
                    request_no: requestNo,
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ดำเนินการสำเร็จ',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        

        function approve() {
            var requestNo = '{{ $list->request_no }}';
            $.ajax({
                url: '{{ route("dashboard.list_accept") }}', 
                type: 'GET',
                data: {
                    request_no: requestNo,
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ดำเนินการสำเร็จ',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function check_btn() {
            var requestNo = '{{ $list->request_no }}';
            $.ajax({
                url: '{{ route("edit.check") }}', 
                type: 'GET',
                data: {
                    request_no: requestNo,
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ส่งคืนสำเร็จ',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function cancel() {
            var cancel_Modal = new bootstrap.Modal(document.getElementById('cancel_Modal'));
            cancel_Modal.show();
        }

        function confirm_cancel() {
            var cancel = document.getElementById('cancel_Text').value;
            if (!cancel) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาใส่หมายเหตุ',
                });
                return;
            }
            var cancel_Modal = bootstrap.Modal.getInstance(document.getElementById('cancel_Modal'));
            cancel_Modal.hide();
            submitWithcancel(cancel);
        }

        function submitWithcancel(Fix) {
            var requestNo = '{{ $list->request_no }}';
            $.ajax({
                url: '{{ route("edit.cancel") }}', 
                type: 'GET',
                data: {
                    remarks: Fix,
                    request_no: requestNo,
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ยกเลิกสำเร็จ',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
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