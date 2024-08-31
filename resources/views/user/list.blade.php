@extends('layout.user')
@section('body')
<div class="container mt-4">
    <div class="card">
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
                <div class="col-md-4">
                    <select id="statusFilter" class="form-select">
                        <option value="">สถานะ</option>
                        @foreach($status as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>No.</th>
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

<script>
    $(document).ready(function() {
        search(); 
        $('#searchInput').on('keyup', function() {
            search(); 
        });

        $('#statusFilter').on('change', function() {
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
        var StatusInput = $('#statusFilter').val();
        var row_num = $('#row_num').val();

        $.ajax({
            url: '{{ route("list.search") }}',
            type: 'POST', 
            data: {
                _token: '{{ csrf_token() }}',
                search: searchInput,
                status: StatusInput,
                page: page,
                row_num: row_num
            },
            success: function(response) {
                var list = response.list.data; // Get paginated data
                var html = '';
                var index = 1;

                if (list.length === 0) {
                    html += '<tr><td colspan="5" class="text-center">ไม่พบข้อมูล</td></tr>';
                } else {
                    list.forEach(function(item, i) {
                        var index = (page - 1) * row_num + (i + 1);
                        html += '<tr onclick="goToEditPage(\'' + item.request_no + '\')">';
                        html += '<td>' + index + '</td>';
                        html += '<td>' + item.request_no + '</td>';
                        html += '<td>' + item.status_name + '</td>';
                        html += '<td>' + item.created_at + '</td>';
                        html += '<td>' + item.updated_at + '</td>';
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