@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="content">
                <div class="container-fluid">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-center py-3 w-100">
                        <h4 class="mb-0 fw-bold">Nhập hàng</h4>
                        <div class="d-flex gap-2">
                            <button class="btn-nh btn-nh-primary" id="btn-open-create">
                                <i class="fas fa-plus"></i> Nhập lô mới
                            </button>
                            <button class="btn-nh btn-nh-success" id="btn-export">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </button>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- TABLE --}}
                    <div class="card shadow-sm">
                        <div class="card-body p-2">
                            <table id="nhaphang-table" class="table table-bordered table-hover table-sm mb-0"
                                style="width:100%; font-size:13px;">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Ngày</th>
                                        <th>Tên lô hàng</th>
                                        <th>Tên khách hàng</th>
                                        <th>Địa chỉ</th>
                                        <th>SĐT Khách</th>
                                        <th>Người chốt</th>
                                        <th>Bộ phận</th>
                                        <th>Đội nhóm</th>
                                        <th>Tài khoản chốt</th>
                                        <th>Người bốc</th>
                                        <th>Người phụ bốc</th>
                                        <th>Loại lô</th>
                                        <th>Kết quả</th>
                                        <th>Lý do fail</th>
                                        <th>Tổng giá trị</th>
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

{{-- MODAL THÊM / SỬA LÔ HÀNG --}}
<div class="modal fade" id="modalNhapHang" tabindex="-1" aria-labelledby="modalNhapHangLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNhapHangLabel">Thêm lô hàng mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-nhaphang" novalidate>
                    @csrf
                    <input type="hidden" id="arrange_id" name="arrange_id" value="">

                    <div class="row g-3">
                        {{-- Hàng 1 --}}
                        <div class="col-md-3">
                            <label class="form-label">Ngày <span class="text-danger">*</span></label>
                            <input type="date" name="day" id="f_day" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tên lô hàng <span class="text-danger">*</span></label>
                            <input type="text" name="name_arrange" id="f_name_arrange" class="form-control form-control-sm" placeholder="VD: Lô QB-001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tên khách hàng</label>
                            <input type="text" name="name_customer" id="f_name_customer" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SĐT Khách</label>
                            <input type="text" name="phone_customer" id="f_phone_customer" class="form-control form-control-sm">
                        </div>

                        {{-- Hàng 2 --}}
                        <div class="col-md-6">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" id="f_address" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tài khoản chốt</label>
                            <input type="text" name="account_social" id="f_account_social" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tổng giá trị</label>
                            <input type="number" name="total_arrange" id="f_total_arrange" class="form-control form-control-sm" placeholder="0">
                        </div>

                        {{-- Hàng 3 --}}
                        <div class="col-md-3">
                            <label class="form-label">Bộ phận</label>
                            <select name="part_id" id="f_part_id" class="form-select form-select-sm">
                                <option value="">-- Chọn bộ phận --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Đội nhóm</label>
                            <select name="team_id" id="f_team_id" class="form-select form-select-sm">
                                <option value="">-- Chọn đội nhóm --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Người chốt</label>
                            <select name="sale_user_id" id="f_sale_user_id" class="form-select form-select-sm">
                                <option value="">-- Chọn --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Người bốc</label>
                            <select name="user_id" id="f_user_id" class="form-select form-select-sm">
                                <option value="">-- Chọn --</option>
                            </select>
                        </div>

                        {{-- Hàng 4 --}}
                        <div class="col-md-3">
                            <label class="form-label">Người phụ bốc</label>
                            <select name="support_user_id" id="f_support_user_id" class="form-select form-select-sm">
                                <option value="">-- Chọn --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Loại lô</label>
                            <select name="type_arrange" id="f_type_arrange" class="form-select form-select-sm">
                                <option value="">-- Chọn --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kết quả</label>
                            <select name="result" id="f_result" class="form-select form-select-sm">
                                <option value="">-- Chọn --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Lý do fail</label>
                            <input type="text" name="reason_fail" id="f_reason_fail" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div id="form-errors" class="mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save">
                    <i class="fas fa-save"></i> Lưu
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== BUTTON HEADER ===== */
.btn-nh {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-nh-primary { background: #4f46e5; color: #fff; }
.btn-nh-primary:hover { background: #4338ca; }
.btn-nh-success { background: #16a34a; color: #fff; }
.btn-nh-success:hover { background: #15803d; }

/* ===== TABLE ===== */
.content-wrapper { overflow-x: hidden; }
#nhaphang-table thead th {
    white-space: nowrap;
    vertical-align: middle;
    font-size: 12px;
    padding: 6px 8px;
}
#nhaphang-table tbody td {
    vertical-align: middle;
    white-space: nowrap;
    padding: 5px 8px;
    font-size: 13px;
}

/* ===== BADGES KẾT QUẢ ===== */
.badge-result {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-hoanthanh { background: #dcfce7; color: #15803d; }
.badge-nhaplieu  { background: #fef9c3; color: #854d0e; }
.badge-fail      { background: #fee2e2; color: #dc2626; }
.badge-moi       { background: #dbeafe; color: #1d4ed8; font-size: 11px; border-radius: 20px; padding: 2px 8px; }
.badge-cu        { background: #f3f4f6; color: #374151; font-size: 11px; border-radius: 20px; padding: 2px 8px; }

/* ===== ACTION BUTTONS ===== */
.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.15s;
}
.btn-edit { background: #fef3c7; color: #d97706; }
.btn-edit:hover { background: #fcd34d; }
.btn-del  { background: #fee2e2; color: #dc2626; }
.btn-del:hover { background: #fca5a5; }
</style>

@include('layouts/parts/footer')

<script>
let DT = null;
let formOptions = {};

// Setup CSRF cho tất cả AJAX
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
});

$(function () {
    // ===== INIT DATATABLE =====
    DT = $('#nhaphang-table').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: '{!! route('nhaphang.data') !!}',
            data: function (d) {
                d.part_id = $('#ft_part').val() || '';
                d.team_id = $('#ft_team').val() || '';
                d.result  = $('#ft_result').val() ?? '';
            }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',   orderable: false, searchable: false, width: '40px' },
            { data: 'day',             name: 'day' },
            { data: 'name_arrange',    name: 'name_arrange' },
            { data: 'name_customer',   name: 'name_customer' },
            { data: 'address',         name: 'address' },
            { data: 'phone_customer',  name: 'phone_customer' },
            { data: 'sale_user_id',    name: 'sale_user_id' },
            { data: 'part_id',         name: 'part_id' },
            { data: 'team_id',         name: 'team_id' },
            { data: 'account_social',  name: 'account_social' },
            { data: 'user_id',         name: 'user_id' },
            { data: 'support_user_id', name: 'support_user_id' },
            { data: 'type_arrange',    name: 'type_arrange' },
            { data: 'result',          name: 'result' },
            { data: 'reason_fail',     name: 'reason_fail' },
            { data: 'total_arrange',   name: 'total_arrange' },
            { data: 'action',          name: 'action', orderable: false, searchable: false },
        ],
        language: {
            processing: 'Đang tải...',
            search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ dòng',
            info: 'Hiển thị _START_ - _END_ / _TOTAL_ lô',
            infoEmpty: 'Không có dữ liệu',
            zeroRecords: 'Không tìm thấy kết quả',
            paginate: { first: 'Đầu', last: 'Cuối', next: 'Sau', previous: 'Trước' }
        },
        dom: `<'row mb-3 align-items-center'
                <'col-md-2' l>
                <'col-md-10 d-flex justify-content-end align-items-center flex-wrap'
                    f <'dt-toolbar d-flex ms-2'>
                >
              >
              rt
              <'row mt-2 justify-content-between'
                <'col-md-auto' i>
                <'col-md-auto' p>
              >`,
        initComplete: function () {
            const $bar = $('.dt-toolbar', this.api().table().container());
            if (!$bar.children().length) {
                $bar.html(`
                    <select id="ft_part"   class="form-select form-select-sm ms-1" style="width:130px"><option value="">Bộ phận</option></select>
                    <select id="ft_team"   class="form-select form-select-sm ms-1" style="width:130px"><option value="">Đội nhóm</option></select>
                    <select id="ft_result" class="form-select form-select-sm ms-1" style="width:140px"><option value="">Kết quả</option></select>
                    <button id="btn-clear-ft" class="btn btn-sm btn-outline-secondary ms-1">Xóa lọc</button>
                `);
            }

            $.getJSON('{!! route('nhaphang.filters') !!}').done(res => {
                fillSelect('#ft_part',   res.part_f);
                fillSelect('#ft_team',   res.team_f);
                fillSelect('#ft_result', res.result_f);
            });

            $(document).on('change', '#ft_part, #ft_team, #ft_result', () => DT.ajax.reload());
            $('#btn-clear-ft').on('click', function () {
                $('#ft_part, #ft_team, #ft_result').val('');
                DT.ajax.reload();
            });
        }
    });

    // ===== LOAD FORM OPTIONS =====
    $.getJSON('{!! route('nhaphang.form-options') !!}').done(res => {
        formOptions = res;
        fillSelect('#f_part_id',        res.parts);
        fillSelect('#f_team_id',        res.teams);
        fillSelect('#f_sale_user_id',   res.users);
        fillSelect('#f_user_id',        res.users);
        fillSelect('#f_support_user_id',res.users);
        fillSelect('#f_type_arrange',   res.type_arranges);
        fillSelect('#f_result',         res.results);
    });

    // ===== OPEN CREATE MODAL =====
    $('#btn-open-create').on('click', function () {
        resetForm();
        $('#modalNhapHangLabel').text('Thêm lô hàng mới');
        $('#modalNhapHang').modal('show');
    });

    // ===== SAVE (store / update) =====
    $('#btn-save').on('click', function () {
        const id = $('#arrange_id').val();
        const url = id
            ? '{!! url('nhap-hang/update') !!}/' + id
            : '{!! route('nhaphang.store') !!}';

        // Convert empty string -> null cho các field integer
        const formData = $('#form-nhaphang').serializeArray();
        const intFields = ['sale_user_id','part_id','team_id','user_id','support_user_id','type_arrange','result','total_arrange'];
        const params = {};
        formData.forEach(f => params[f.name] = f.value);
        intFields.forEach(f => { if (params[f] === '') params[f] = null; });

        $.ajax({
            url: url,
            method: 'POST',
            data: params,
            success: function (res) {
                if (res.success) {
                    $('#modalNhapHang').modal('hide');
                    DT.ajax.reload();
                    Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let html = '<div class="alert-error-wrap">';
                Object.values(errors).forEach(msgs => msgs.forEach(m => {
                    html += `<div class="alert-error">${m}</div>`;
                }));
                html += '</div>';
                $('#form-errors').html(html);
            }
        });
    });

    // ===== OPEN EDIT MODAL =====
    window.openEdit = function (id) {
        resetForm();
        $.getJSON('{!! url('nhap-hang/get') !!}/' + id).done(function (data) {
            $('#arrange_id').val(data.id);
            $('#f_day').val(data.day);
            $('#f_name_arrange').val(data.name_arrange);
            $('#f_name_customer').val(data.name_customer);
            $('#f_phone_customer').val(data.phone_customer);
            $('#f_address').val(data.address);
            $('#f_account_social').val(data.account_social);
            $('#f_total_arrange').val(data.total_arrange);
            $('#f_part_id').val(data.part_id);
            $('#f_team_id').val(data.team_id);
            $('#f_sale_user_id').val(data.sale_user_id);
            $('#f_user_id').val(data.user_id);
            $('#f_support_user_id').val(data.support_user_id);
            $('#f_type_arrange').val(data.type_arrange);
            $('#f_result').val(data.result);
            $('#f_reason_fail').val(data.reason_fail);
            $('#modalNhapHangLabel').text('Sửa lô hàng: ' + data.name_arrange);
            $('#modalNhapHang').modal('show');
        });
    };

    // ===== DELETE =====
    $(document).on('click', '.btn-delete', function () {
        const $form = $(this).closest('form.form-delete');
        Swal.fire({
            title: 'Bạn có chắc muốn xóa lô hàng này?',
            icon: 'question',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            showCancelButton: true,
        }).then(result => {
            if (result.isConfirmed) $form.submit();
        });
    });

    // ===== HELPERS =====
    function fillSelect(selector, items) {
        const el = $(selector);
        (items || []).forEach(item => el.append(new Option(item.text, item.id)));
    }

    function resetForm() {
        $('#form-nhaphang')[0].reset();
        $('#arrange_id').val('');
        $('#form-errors').html('');
    }
});
</script>
