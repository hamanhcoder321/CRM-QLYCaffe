@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold"><i class="fas fa-mug-hot text-warning mr-2"></i>Menu Thức Uống</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
          <ol class="breadcrumb mr-3 mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Bán hàng</a></li>
            <li class="breadcrumb-item active">Thức uống</li>
          </ol>
          <button class="btn btn-warning btn-sm pl-3 pr-3" id="btn-add-product" data-toggle="modal" data-target="#productModal">
            <i class="fas fa-plus mr-1"></i> Thêm thức uống
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-list mr-2 text-warning"></i>Danh sách thức uống</h3>
          <span class="text-muted small">Dữ liệu từ nhập hàng</span>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="product-table" class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-gradient-warning">
              <tr>
                <th class="pl-3">#</th>
                <th>Tên thức uống</th>
                <th>Lô hàng nguồn</th>
                <th>Đơn giá</th>
                <th>Nhập về</th>
                <th>Đã bán</th>
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
      <div class="modal-header bg-gradient-warning">
        <h5 class="modal-title font-weight-bold" id="modal-title-product">
          <i class="fas fa-mug-hot mr-2"></i>Thêm thức uống mới
        </h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="product-id">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Tên thức uống <span class="text-danger">*</span></label>
              <input type="text" id="product-name" class="form-control" placeholder="VD: Cà phê sữa đá">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Giá vốn / giá nhập (đ) <span class="text-muted small">(tùy chọn)</span></label>
              <input type="number" id="product-cost-price" class="form-control" placeholder="VD: 20000" min="0">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Giá bán (đ) <span class="text-danger">*</span></label>
              <input type="number" id="product-price" class="form-control" placeholder="VD: 35000" min="0">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Lô hàng nhập (nguồn)</label>
              <select id="product-shipment" class="form-control">
                <option value="">-- Chọn lô hàng --</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Số lượng nhập <span class="text-danger">*</span></label>
              <input type="number" id="product-number-in" class="form-control" placeholder="VD: 100" min="0">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Hủy</button>
        <button type="button" class="btn btn-warning text-white" id="btn-save-product"><i class="fas fa-save mr-1"></i>Lưu</button>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')

<style>
.badge-danger {color:#b91c1c; }
.badge-success {color:#15803d; }
.btn-action { border:none;border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer;margin:0 2px; }
.btn-edit {color:#1d4ed8; }
.btn-del  {color:#b91c1c; }
</style>

<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

// Load shipments
$.get('{{ url("/ban-hang/shipments") }}', function(data) {
    let opts = '<option value="">-- Chọn lô hàng --</option>';
    (data||[]).forEach(s => opts += `<option value="${s.id}">${s.name}</option>`);
    $('#product-shipment').html(opts);
});

// DataTable
const table = $('#product-table').DataTable({
    processing: true, serverSide: true,
    ajax: { url: '{{ route("banhang.thuc-uong.data") }}' },
    columns: [
        { data: 'DT_RowIndex', orderable: false, className: 'pl-3' },
        { data: 'name' },
        { data: 'shipment_name', orderable: false, defaultContent: '—' },
        { data: 'price' },
        { data: 'number_in' },
        { data: 'number_out' },
        { data: 'ton_kho', orderable: false },
        { data: 'action', orderable: false, className: 'text-center' },
    ],
    language: { processing:'Đang tải...', search:'Tìm:', emptyTable:'Chưa có thức uống nào', info:'_START_-_END_ / _TOTAL_ sản phẩm', lengthMenu:'Hiển thị _MENU_ dòng', paginate:{next:'›',previous:'‹'} }
});

// Thêm mới
$('#btn-add-product').click(function() {
    $('#modal-title-product').html('<i class="fas fa-plus-circle mr-2"></i>Thêm thức uống mới');
    $('#product-id, #product-name, #product-number-in, #product-price, #product-cost-price').val('');
    $('#product-shipment').val('');
    $('#productModal').modal('show');
});

// Sửa
window.openEditProduct = function(id) {
    $.get('/ban-hang/thuc-uong/get/' + id, function(res) {
        $('#modal-title-product').html('<i class="fas fa-edit mr-2"></i>Cập nhật thức uống');
        $('#product-id').val(res.id);
        $('#product-name').val(res.name);
        $('#product-price').val(res.price);
        $('#product-cost-price').val(res.cost_price ?? 0);
        $('#product-number-in').val(res.number_in);
        $('#product-shipment').val(res.shipment_id ?? '');
        $('#productModal').modal('show');
    });
};

// Lưu
$('#btn-save-product').click(function() {
    const id  = $('#product-id').val();
    const url = id ? '/ban-hang/thuc-uong/update/' + id : '/ban-hang/thuc-uong/store';
    const data = {
        name:        $('#product-name').val().trim(),
        price:       $('#product-price').val(),
        cost_price:  $('#product-cost-price').val() || 0,
        number_in:   $('#product-number-in').val(),
        shipment_id: $('#product-shipment').val() || null,
    };
    if (!data.name) { Swal.fire('Lỗi','Tên thức uống bắt buộc!','warning'); return; }

    $.post(url, data)
        .done(res => { if(res.success) { $('#productModal').modal('hide'); table.ajax.reload(); Swal.fire({icon:'success',title:res.message,timer:1500,showConfirmButton:false}); } })
        .fail(xhr => { const err = xhr.responseJSON?.errors; Swal.fire({icon:'error',title:'Lỗi',html: err ? Object.values(err).flat().join('<br>') : 'Có lỗi!'}); });
});

// Xóa
window.deleteProduct = function(id) {
    Swal.fire({title:'Xóa thức uống?',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonText:'Hủy',confirmButtonText:'Xóa'})
        .then(r => {
            if(r.isConfirmed) {
                $.ajax({ url:'/ban-hang/thuc-uong/delete/'+id, type:'DELETE' })
                    .done(res => { table.ajax.reload(); Swal.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}); });
            }
        });
};
</script>
