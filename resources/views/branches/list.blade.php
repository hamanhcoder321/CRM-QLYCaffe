@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">
            <i class="fas fa-store-alt text-primary mr-2"></i>Quản lý chi nhánh
          </h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
          <ol class="breadcrumb mr-3 mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Chi nhánh</li>
          </ol>
          @if(auth()->user()?->isSuperAdminOrAdmin())
          <button class="btn btn-primary btn-sm pl-3 pr-3" id="btn-add-branch" data-toggle="modal" data-target="#branchModal">
            <i class="fas fa-plus mr-1"></i> Thêm chi nhánh
          </button>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      {{-- STAT CARDS --}}
      <div class="row mb-4" id="branch-stats">
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info"><i class="fas fa-store-alt"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng chi nhánh</span>
              <span class="info-box-number" id="stat-total">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Đang hoạt động</span>
              <span class="info-box-number" id="stat-active">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fas fa-pause-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tạm đóng</span>
              <span class="info-box-number" id="stat-closed">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng nhân viên</span>
              <span class="info-box-number" id="stat-users">—</span>
            </div>
          </div>
        </div>
      </div>

      {{-- TABLE --}}
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-list mr-2"></i>Danh sách chi nhánh
          </h3>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="branch-table" class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-gradient-dark text-white">
              <tr>
                <th class="pl-3">#</th>
                <th>Tên chi nhánh</th>
                <th>Địa chỉ</th>
                <th>Điện thoại</th>
                <th>Quản lý</th>
                <th>Nhân viên</th>
                <th>Trạng thái</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- MODAL THÊM / SỬA --}}
<div class="modal fade" id="branchModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title font-weight-bold" id="modal-title-branch">
          <i class="fas fa-store-alt mr-2"></i>Thêm chi nhánh mới
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="branch-id" value="">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Tên chi nhánh <span class="text-danger">*</span></label>
              <input type="text" id="branch-name" class="form-control" placeholder="VD: Chi nhánh Hà Nội">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Số điện thoại</label>
              <input type="text" id="branch-phone" class="form-control" placeholder="VD: 0901234567">
            </div>
          </div>
        </div>
        <div class="form-group">
          <label>Địa chỉ</label>
          <input type="text" id="branch-address" class="form-control" placeholder="VD: 12 Lê Lợi, Q.1, TP.HCM">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Quản lý chi nhánh</label>
              <select id="branch-manager" class="form-control">
                <option value="">-- Chọn quản lý --</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Trạng thái</label>
              <select id="branch-status" class="form-control">
                <option value="0">Đang hoạt động</option>
                <option value="1">Tạm đóng</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i>Hủy
        </button>
        <button type="button" class="btn btn-primary" id="btn-save-branch">
          <i class="fas fa-save mr-1"></i>Lưu
        </button>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')

<style>
.badge-result { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
.badge-hoanthanh { color: #15803d; }
.badge-fail      { color: #b91c1c; }
.btn-action { border: none; border-radius: 6px; padding: 4px 8px; font-size: 12px; cursor: pointer; margin: 0 2px; }
.btn-edit   { color: #1d4ed8; }
.btn-del    { color: #b91c1c; }
.btn-edit:hover { background: #bfdbfe; }
.btn-del:hover  { background: #fecaca; }
</style>

<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

// ===== DATATABLE =====
const branchTable = $('#branch-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: '{{ route("branches.data") }}', type: 'GET' },
    columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'pl-3' },
        { data: 'name' },
        { data: 'address', defaultContent: '—' },
        { data: 'phone',   defaultContent: '—' },
        { data: 'manager_name', defaultContent: '—', orderable: false },
        { data: null, orderable: false, searchable: false, render: (_, __, row) => {
            return '<a href="/chi-nhanh?branch=' + row.id + '" class="text-primary">' + (row.users_count ?? 0) + ' NV</a>';
        }},
        { data: 'status', orderable: false },
        { data: 'action',  orderable: false, className: 'text-center' },
    ],
    language: {
        processing: 'Đang tải...', search: 'Tìm kiếm:',
        lengthMenu: 'Hiển thị _MENU_ dòng',
        info: 'Hiển thị _START_ - _END_ / _TOTAL_ chi nhánh',
        emptyTable: 'Chưa có dữ liệu chi nhánh',
        paginate: { first: '«', last: '»', next: '›', previous: '‹' }
    },
    drawCallback: updateStats,
});

// ===== LOAD USERS CHO DROPDOWN MANAGER =====
function loadManagers(selectedId = '') {
    $.get('/api/users-list', function(data) {
        let opts = '<option value="">-- Chọn quản lý --</option>';
        data.forEach(u => {
            opts += `<option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>${u.name} (${u.email})</option>`;
        });
        $('#branch-manager').html(opts);
    }).fail(() => {
        // Nếu không có API, load qua Ajax đơn giản
        $.get('{{ url("/chi-nhanh/managers") }}', function(data) {
            let opts = '<option value="">-- Chọn quản lý --</option>';
            data.forEach(u => {
                opts += `<option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>${u.name}</option>`;
            });
            $('#branch-manager').html(opts);
        });
    });
}

// Load users trực tiếp từ bảng users
$.get('{{ url("/chi-nhanh/managers") }}', function(data) {
    let opts = '<option value="">-- Chọn quản lý --</option>';
    (data || []).forEach(u => {
        opts += `<option value="${u.id}">${u.name}${u.email ? ' ('+u.email+')' : ''}</option>`;
    });
    $('#branch-manager').html(opts);
});

// ===== THÊM MỚI =====
$('#btn-add-branch').click(function() {
    $('#modal-title-branch').html('<i class="fas fa-plus-circle mr-2"></i>Thêm chi nhánh mới');
    $('#branch-id').val('');
    $('#branch-name, #branch-phone, #branch-address').val('');
    $('#branch-status').val('0');
    $('#branch-manager').val('');
    $('#branchModal').modal('show');
});

// ===== SỬA =====
window.openEdit = function(id) {
    $('#modal-title-branch').html('<i class="fas fa-edit mr-2"></i>Cập nhật chi nhánh');
    $.get('/chi-nhanh/get/' + id, function(res) {
        $('#branch-id').val(res.id);
        $('#branch-name').val(res.name);
        $('#branch-phone').val(res.phone);
        $('#branch-address').val(res.address);
        $('#branch-status').val(res.status);
        $('#branch-manager').val(res.manager_id ?? '');
        $('#branchModal').modal('show');
    });
};

// ===== LƯU =====
$('#btn-save-branch').click(function() {
    const id   = $('#branch-id').val();
    const url  = id ? '/chi-nhanh/update/' + id : '/chi-nhanh/store';
    const data = {
        name:       $('#branch-name').val().trim(),
        phone:      $('#branch-phone').val().trim(),
        address:    $('#branch-address').val().trim(),
        status:     $('#branch-status').val(),
        manager_id: $('#branch-manager').val() || null,
    };
    if (!data.name) { Swal.fire('Lỗi', 'Tên chi nhánh là bắt buộc!', 'warning'); return; }

    $.post(url, data)
        .done(res => {
            if (res.success) {
                $('#branchModal').modal('hide');
                branchTable.ajax.reload();
                Swal.fire({ icon: 'success', title: res.message, timer: 1800, showConfirmButton: false });
            }
        })
        .fail(xhr => {
            const err = xhr.responseJSON?.errors;
            const msg = err ? Object.values(err).flat().join('<br>') : 'Có lỗi xảy ra!';
            Swal.fire({ icon: 'error', title: 'Lỗi', html: msg });
        });
});

// ===== XÓA =====
$(document).on('click', '.btn-delete-branch', function() {
    const form = $(this).closest('.form-delete-branch');
    Swal.fire({
        title: 'Xác nhận xóa?', text: 'Chi nhánh và dữ liệu liên quan sẽ bị xóa!',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#d33', cancelButtonText: 'Hủy', confirmButtonText: 'Xóa'
    }).then(r => { if (r.isConfirmed) form.submit(); });
});

// ===== STATS =====
function updateStats() {
    const info = branchTable.page.info();
    $('#stat-total').text(info.recordsTotal ?? '—');
}

// Stat NV và đang hoạt động
$.get('{{ route("branches.data") }}', { draw:1, start:0, length:1000, columns: [{data:'status'}], search:{value:''} }, function(res) {
    const data = res.data || [];
    $('#stat-total').text(data.length);
    $('#stat-active').text(data.filter(d => d.status && d.status.includes('hoat-dong') || d.status && d.status.includes('Đang')).length);
    $('#stat-closed').text(data.filter(d => d.status && d.status.includes('Tạm')).length);
});

// Stat NV
$.get('{{ url("/") }}', {}, function() {}).always(function() {
    $('#stat-users').text('{{ \App\Models\User::where("status",0)->count() }}');
});
</script>
