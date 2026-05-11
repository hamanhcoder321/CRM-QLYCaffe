@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold"><i class="fas fa-boxes text-info mr-2"></i>Tồn Kho</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Bán hàng</a></li>
            <li class="breadcrumb-item active">Tồn kho</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      {{-- Stat cards --}}
      <div class="row mb-4" id="tonkho-stats">
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info"><i class="fas fa-cubes"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng loại nguyên liệu</span>
              <span class="info-box-number" id="stat-total-sp">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Còn hàng</span>
              <span class="info-box-number" id="stat-con-hang">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Hết hàng</span>
              <span class="info-box-number" id="stat-het-hang">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fas fa-exclamation"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Sắp hết (≤20)</span>
              <span class="info-box-number" id="stat-sap-het">—</span>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-warehouse mr-2 text-info"></i>Tình trạng tồn kho</h3>
          <button class="btn btn-sm btn-outline-warning" onclick="openAddProduct()">
            <i class="fas fa-plus mr-1"></i>Nhập thêm nguyên liệu
          </button>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="tonkho-table" class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-gradient-info">
              <tr>
                <th class="pl-3">#</th>
                <th>Tên nguyên liệu</th>
                <th>Lô hàng</th>
                <th>Loại lô</th>
                <th>Nhập về</th>
                <th>Đã xuất bán</th>
                <th>Tồn kho</th>
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

{{-- MODAL --}}
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title font-weight-bold" id="modal-title-product">
          <i class="fas fa-edit mr-2"></i>Cập nhật thông tin
        </h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="form-product" enctype="multipart/form-data">
        <input type="hidden" id="product-id">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Tên nguyên liệu <span class="text-danger">*</span></label>
              <input type="text" id="product-name" name="name" class="form-control" placeholder="VD: Hạt Caffe, Đường, Sữa">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Ảnh đại diện (tùy chọn)</label>
              <input type="file" id="product-image" name="image" class="form-control-file" accept="image/*">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Giá vốn / giá nhập (đ)</label>
              <input type="number" id="product-cost-price" name="cost_price" class="form-control" min="0">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Giá bán (đ) <span class="text-danger">*</span></label>
              <input type="number" id="product-price" name="price" class="form-control" min="0">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Lô hàng nhập</label>
              <select id="product-shipment" name="shipment_id" class="form-control">
                <option value="">-- Chọn lô hàng --</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Số lượng nhập ban đầu <span class="text-danger">*</span></label>
              <input type="number" id="product-number-in" name="number_in" class="form-control" min="0">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Loại lô (Mới / Cũ) <span class="text-muted small">(cập nhật lô)</span></label>
              <select id="product-type-arrange" name="type_arrange" class="form-control">
                <option value="">-- Theo lô hàng hiện tại --</option>
                <option value="0">Mới</option>
                <option value="1">Cũ</option>
              </select>
            </div>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Hủy</button>
        <button type="button" class="btn btn-info text-white" id="btn-save-product"><i class="fas fa-save mr-1"></i>Lưu</button>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')

<style>
.badge-success { color:#15803d;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; background: transparent !important; }
.badge-warning { color:#854d0e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; background: transparent !important; }
.badge-danger  { color:#b91c1c;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; background: transparent !important; }
.btn-action { border:none;border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer;margin:0 2px; }
.btn-edit {color:#1d4ed8; background:transparent;}
.btn-del  {color:#b91c1c; background:transparent;}
</style>

<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

const table = $('#tonkho-table').DataTable({
    processing: true, serverSide: true,
    ajax: { url: '{{ route("banhang.ton-kho.data") }}' },
    columns: [
        { data: 'DT_RowIndex', orderable: false, className: 'pl-3' },
        { data: 'name' },
        { data: 'shipment_name', orderable: false, defaultContent: '—' },
        { data: 'shipment_type', orderable: false, defaultContent: '—' },
        { data: 'number_in' },
        { data: 'number_out' },
        { data: 'con_lai', orderable: false },
        { data: 'action', orderable: false, className: 'text-center' },
    ],
    language: { processing:'Đang tải...', search:'Tìm:', emptyTable:'Chưa có dữ liệu tồn kho', info:'_START_-_END_ / _TOTAL_ nguyên liệu', lengthMenu:'Hiển thị _MENU_ dòng', paginate:{next:'›',previous:'‹'} },
    drawCallback: function() {
        const info = table.page.info();
        $('#stat-total-sp').text(info.recordsTotal);
    }
});

// Load stats chi tiết
function loadTonKhoStats() {
    $.get('{{ route("banhang.ton-kho.data") }}', {draw:1,start:0,length:10000}, function(res) {
        const data = res.data || [];
        $('#stat-total-sp').text(res.recordsTotal ?? data.length);
        $('#stat-con-hang').text(data.filter(d => (d.con_lai_raw ?? 0) > 20).length);
        $('#stat-sap-het').text(data.filter(d => (d.con_lai_raw ?? 0) > 0 && (d.con_lai_raw ?? 0) <= 20).length);
        $('#stat-het-hang').text(data.filter(d => (d.con_lai_raw ?? 0) <= 0).length);
    });
}
loadTonKhoStats();

let shipmentsData = [];
// Tải danh sách lô hàng cho form (modal)
$.get('{{ url("/ban-hang/shipments") }}', function(data) {
    shipmentsData = data || [];
    let opts = '<option value="">-- Chọn lô hàng --</option>';
    shipmentsData.forEach(s => opts += `<option value="${s.id}">${s.name}</option>`);
    $('#product-shipment').html(opts);
});

// Tự động điền loại lô hàng (Mới/Cũ) khi chọn một lô hàng
$('#product-shipment').change(function() {
    const selectedId = $(this).val();
    if (selectedId) {
        const shipment = shipmentsData.find(s => s.id == selectedId);
        if (shipment && shipment.type_arrange !== null) {
            $('#product-type-arrange').val(shipment.type_arrange);
        }
    }
});

// Sửa
window.openEditProduct = function(id) {
    $.get('/ban-hang/nguyen-lieu/get/' + id, function(res) {
        $('#product-id').val(res.id);
        $('#product-name').val(res.name);
        $('#product-price').val(res.price);
        $('#product-cost-price').val(res.cost_price ?? 0);
        $('#product-number-in').val(res.number_in);
        $('#product-shipment').val(res.shipment_id ?? '');
        $('#product-type-arrange').val(res.shipment?.arrange?.type_arrange ?? '');
        $('#product-image').val(''); 
        $('#productModal').modal('show');
    });
};

// Thêm
window.openAddProduct = function() {
    $('#form-product')[0].reset();
    $('#product-id').val('');
    $('#product-image').val('');
    $('#productModal').modal('show');
};

// Lưu
$('#btn-save-product').click(function() {
    const id  = $('#product-id').val();
    const url = id ? '/ban-hang/nguyen-lieu/update/' + id : '/ban-hang/nguyen-lieu/store';
    const data = new FormData();
    data.append('name', $('#product-name').val().trim());
    data.append('price', $('#product-price').val());
    data.append('cost_price', $('#product-cost-price').val() || 0);
    data.append('number_in', $('#product-number-in').val());
    if ($('#product-shipment').val()) data.append('shipment_id', $('#product-shipment').val());
    if ($('#product-type-arrange').val() !== '') data.append('type_arrange', $('#product-type-arrange').val());
    
    const imageFile = $('#product-image')[0].files[0];
    if (imageFile) {
        data.append('image', imageFile);
    }

    if (!data.get('name')) { Swal.fire('Lỗi','Tên nguyên liệu bắt buộc!','warning'); return; }

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        success: res => { if(res.success) { $('#productModal').modal('hide'); table.ajax.reload(); loadTonKhoStats(); Swal.fire({icon:'success',title:res.message,timer:1500,showConfirmButton:false}); } },
        error: xhr => { const err = xhr.responseJSON?.errors; Swal.fire({icon:'error',title:'Lỗi',html: err ? Object.values(err).flat().join('<br>') : 'Có lỗi!'}); }
    });
});

// Xóa
window.deleteProduct = function(id) {
    Swal.fire({title:'Xóa nguyên liệu này?',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonText:'Hủy',confirmButtonText:'Xóa'})
        .then(r => {
            if(r.isConfirmed) {
                $.ajax({ url:'/ban-hang/nguyen-lieu/delete/'+id, type:'DELETE' })
                    .done(res => { table.ajax.reload(); loadTonKhoStats(); Swal.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}); });
            }
        });
};
</script>
