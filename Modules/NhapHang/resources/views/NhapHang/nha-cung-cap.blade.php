@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="content">
                <div class="container-fluid">

                    <div class="d-flex justify-content-between align-items-center py-3 w-100">
                        <h4 class="mb-0 fw-bold">Nhà cung cấp</h4>
                        <button class="btn-nh btn-nh-primary" id="btn-open-ncc">
                            <i class="fas fa-plus"></i> Thêm mới
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-body p-2">
                            <table id="ncc-table" class="table table-bordered table-hover table-sm mb-0"
                                style="width:100%; font-size:13px;">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Tên NCC</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Nguồn</th>
                                        <th>Hàng cung cấp</th>
                                        <th>Tình trạng</th>
                                        <th>Quy mô</th>
                                        <th>Tiềm năng</th>
                                        <th>Số đơn</th>
                                        <th>Ghi chú</th>
                                        <th>Tác vụ</th>
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

{{-- MODAL THÊM / SỬA NHÀ CUNG CẤP --}}
<div class="modal fade" id="modalNCC" tabindex="-1" aria-labelledby="modalNCCLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNCCLabel">Thêm nhà cung cấp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-ncc" novalidate>
                    @csrf
                    <input type="hidden" id="ncc_id" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên NCC <span class="text-danger">*</span></label>
                            <input type="text" name="fullname" id="ncc_fullname" class="form-control form-control-sm" placeholder="VD: Công ty TNHH Cà phê ABC">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" id="ncc_phone" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nguồn</label>
                            <input type="text" name="source" id="ncc_source" class="form-control form-control-sm" placeholder="VD: Zalo, Shopee...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" id="ncc_address" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hàng cung cấp</label>
                            <input type="text" name="product_sale" id="ncc_product_sale" class="form-control form-control-sm" placeholder="VD: Cà phê, Sữa, Đường...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tình trạng hàng</label>
                            <select name="classify" id="ncc_classify" class="form-select form-select-sm">
                                <option value="1">Còn hàng</option>
                                <option value="0">Hết hàng</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quy mô</label>
                            <select name="scale" id="ncc_scale" class="form-select form-select-sm">
                                <option value="0">Nhỏ</option>
                                <option value="1">Vừa</option>
                                <option value="2">Lớn</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tiềm năng</label>
                            <select name="potentical" id="ncc_potentical" class="form-select form-select-sm">
                                <option value="0">Thấp</option>
                                <option value="1">Trung bình</option>
                                <option value="2">Cao</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" id="ncc_note" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                    <div id="ncc-errors" class="mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-ncc">
                    <i class="fas fa-save"></i> Lưu
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.btn-nh { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 13px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: all 0.2s; }
.btn-nh-primary { background: #4f46e5; color: #fff; }
.btn-nh-primary:hover { background: #4338ca; }
.content-wrapper { overflow-x: hidden; }
#ncc-table thead th { white-space: nowrap; vertical-align: middle; font-size: 12px; padding: 6px 8px; }
#ncc-table tbody td { vertical-align: middle; white-space: nowrap; padding: 5px 8px; font-size: 13px; }
.badge-result { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-hoanthanh { background: #dcfce7; color: #15803d; }
.badge-fail      { background: #fee2e2; color: #dc2626; }
.btn-action { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; transition: all 0.15s; }
.btn-edit { background: #fef3c7; color: #d97706; }
.btn-edit:hover { background: #fcd34d; }
.btn-del  { background: #fee2e2; color: #dc2626; }
.btn-del:hover { background: #fca5a5; }
</style>

@include('layouts/parts/footer')

<script>
let NCC_DT = null;

$(function () {
    NCC_DT = $('#ncc-table').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: { url: '{!! route('nhaphang.nha-cung-cap.data') !!}' },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',  orderable: false, searchable: false, width: '40px' },
            { data: 'fullname',      name: 'fullname' },
            { data: 'phone',         name: 'phone' },
            { data: 'address',       name: 'address' },
            { data: 'source',        name: 'source' },
            { data: 'product_sale',  name: 'product_sale' },
            { data: 'classify',      name: 'classify',      searchable: false },
            { data: 'scale',         name: 'scale',         searchable: false },
            { data: 'potentical',    name: 'potentical',    searchable: false },
            { data: 'shipments_count', name: 'shipments_count', searchable: false },
            { data: 'note',          name: 'note',          searchable: false },
            { data: 'action',        name: 'action',        orderable: false, searchable: false },
        ],
        language: {
            processing: 'Đang tải...', search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ dòng',
            info: 'Hiển thị _START_ - _END_ / _TOTAL_ NCC',
            infoEmpty: 'Không có dữ liệu', zeroRecords: 'Không tìm thấy kết quả',
            paginate: { first: 'Đầu', last: 'Cuối', next: 'Sau', previous: 'Trước' }
        }
    });

    // Mở modal thêm
    $('#btn-open-ncc').on('click', function () {
        resetNCC();
        $('#modalNCCLabel').text('Thêm nhà cung cấp');
        $('#modalNCC').modal('show');
    });

    // Lưu
    $('#btn-save-ncc').on('click', function () {
        const id  = $('#ncc_id').val();
        const url = id
            ? '{!! url('nhap-hang/nha-cung-cap/update') !!}/' + id
            : '{!! route('nhaphang.nha-cung-cap.store') !!}';

        $.ajax({
            url, method: 'POST',
            data: $('#form-ncc').serialize(),
            success: function (res) {
                if (res.success) {
                    $('#modalNCC').modal('hide');
                    NCC_DT.ajax.reload();
                    Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let html = '<div class="alert-error-wrap">';
                Object.values(errors).forEach(msgs => msgs.forEach(m => { html += `<div class="alert-error">${m}</div>`; }));
                html += '</div>';
                $('#ncc-errors').html(html);
            }
        });
    });

    // Xóa
    $(document).on('click', '.btn-delete', function () {
        const $form = $(this).closest('form.form-delete');
        Swal.fire({ title: 'Xóa nhà cung cấp này?', icon: 'question', confirmButtonText: 'Xóa', cancelButtonText: 'Hủy', showCancelButton: true })
            .then(r => { if (r.isConfirmed) $form.submit(); });
    });
});

window.openEditNCC = function (id) {
    resetNCC();
    $.getJSON('/nhap-hang/nha-cung-cap/get/' + id).done(function (d) {
        $('#ncc_id').val(d.id);
        $('#ncc_fullname').val(d.fullname);
        $('#ncc_phone').val(d.phone);
        $('#ncc_address').val(d.address);
        $('#ncc_source').val(d.source);
        $('#ncc_product_sale').val(d.product_sale);
        $('#ncc_classify').val(d.classify);
        $('#ncc_scale').val(d.scale);
        $('#ncc_potentical').val(d.potentical);
        $('#ncc_note').val(d.note);
        $('#modalNCCLabel').text('Sửa: ' + d.fullname);
        $('#modalNCC').modal('show');
    });
};

function resetNCC() {
    $('#form-ncc')[0].reset();
    $('#ncc_id').val('');
    $('#ncc-errors').html('');
}
</script>
