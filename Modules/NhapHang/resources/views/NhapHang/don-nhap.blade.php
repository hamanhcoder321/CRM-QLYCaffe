@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="content">
                <div class="container-fluid">

                    <div class="d-flex justify-content-between align-items-center py-3 w-100">
                        <h4 class="mb-0 fw-bold">Quản lý đơn nhập</h4>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-2">
                            <table id="donnhap-table" class="table table-bordered table-hover table-sm mb-0"
                                style="width:100%; font-size:13px;">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Ngày nhập</th>
                                        <th>Tên lô hàng</th>
                                        <th>Nhà cung cấp</th>
                                        <th>Phí vận chuyển</th>
                                        <th>Tổng giá trị lô</th>
                                        <th>Kết quả</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-wrapper { overflow-x: hidden; }
#donnhap-table thead th { white-space: nowrap; vertical-align: middle; font-size: 12px; padding: 6px 8px; }
#donnhap-table tbody td { vertical-align: middle; white-space: nowrap; padding: 5px 8px; font-size: 13px; }
.badge-result { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-hoanthanh { background: #dcfce7; color: #15803d; }
.badge-nhaplieu  { background: #fef9c3; color: #854d0e; }
.badge-fail      { background: #fee2e2; color: #dc2626; }
</style>

@include('layouts/parts/footer')

<script>
$(function () {
    $('#donnhap-table').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: { url: '{!! route('nhaphang.don-nhap.data') !!}' },
        columns: [
            { data: 'DT_RowIndex',  name: 'DT_RowIndex', orderable: false, searchable: false, width: '40px' },
            { data: 'ngay',         name: 'ngay',         searchable: false },
            { data: 'arrange_id',   name: 'arrange_id' },
            { data: 'customer_id',  name: 'customer_id' },
            { data: 'car_money',    name: 'car_money' },
            { data: 'tong_gia_tri', name: 'tong_gia_tri', searchable: false },
            { data: 'result',       name: 'result',       searchable: false },
        ],
        language: {
            processing: 'Đang tải...', search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ dòng',
            info: 'Hiển thị _START_ - _END_ / _TOTAL_ đơn',
            infoEmpty: 'Không có dữ liệu', zeroRecords: 'Không tìm thấy kết quả',
            paginate: { first: 'Đầu', last: 'Cuối', next: 'Sau', previous: 'Trước' }
        }
    });
});
</script>
