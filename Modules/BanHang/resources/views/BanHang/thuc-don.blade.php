@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold"><i class="fas fa-mug-hot text-info mr-2"></i>Thực đơn / Menu</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
          <ol class="breadcrumb mr-3 mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Bán hàng</a></li>
            <li class="breadcrumb-item active">Thực đơn</li>
          </ol>
          <button class="btn btn-info btn-sm pl-3 pr-3" id="btn-add-drink" data-toggle="modal" data-target="#drinkModal">
            <i class="fas fa-plus mr-1"></i> Thêm món mới
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-list mr-2 text-info"></i>Danh sách thức uống</h3>
          <span class="text-muted small">Cấu hình định mức (Recipes) cho từng món</span>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="drink-table" class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-gradient-info">
              <tr>
                <th class="pl-3">#</th>
                <th>Tên món</th>
                <th>Giá bán</th>
                <th>Định mức nguyên liệu (Recipe)</th>
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

{{-- MODAL --}}
<div class="modal fade" id="drinkModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title font-weight-bold" id="modal-title-drink">
          <i class="fas fa-mug-hot mr-2"></i>Thêm món mới
        </h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="form-drink" enctype="multipart/form-data">
        <input type="hidden" id="drink-id">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Tên món <span class="text-danger">*</span></label>
              <input type="text" id="drink-name" name="name" class="form-control" placeholder="VD: Cà phê sữa đá">
            </div>
            <div class="form-group">
              <label>Giá bán (đ) <span class="text-danger">*</span></label>
              <input type="number" id="drink-price" name="price" class="form-control" placeholder="VD: 35000" min="0">
            </div>
            <div class="form-group">
              <label>Trạng thái</label>
              <select id="drink-status" class="form-control">
                  <option value="1">Đang bán</option>
                  <option value="0">Ngừng bán</option>
              </select>
            </div>
            <div class="form-group">
              <label>Ảnh minh họa (tùy chọn)</label>
              <input type="file" id="drink-image" name="image" class="form-control-file" accept="image/*">
            </div>
          </div>
          
          <div class="col-md-6 border-left">
            <label class="d-flex justify-content-between align-items-center">
                <span>Định mức nguyên liệu (Recipe)</span>
                <button type="button" class="btn btn-sm btn-outline-info" onclick="addRecipeRow()">
                    <i class="fas fa-plus"></i> Thêm NL
                </button>
            </label>
            <div id="recipe-list">
                <!-- Recipe rows will be appended here -->
            </div>
            <div class="text-muted small mt-2">
                * Khi bán 1 món này, hệ thống sẽ tự động trừ kho số lượng nguyên liệu tương ứng.
            </div>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Hủy</button>
        <button type="button" class="btn btn-info text-white" id="btn-save-drink"><i class="fas fa-save mr-1"></i>Lưu</button>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.badge-success { color:#15803d;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; background: transparent !important; }
.badge-warning { color:#854d0e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; background: transparent !important; }
.badge-danger  { color:#b91c1c;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; background: transparent !important; }
.btn-action { border:none;border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer;margin:0 2px; }
.btn-edit {color:#1d4ed8; background: transparent;}
.btn-del  {color:#b91c1c; background: transparent;}
.recipe-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
</style>

<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

let materials = [];
// Lấy danh sách nguyên liệu
$.get('{{ route("banhang.ton-kho.data") }}', {draw:1,start:0,length:1000}, function(res) {
    materials = res.data || [];
});

function getMaterialOptions(selectedId = '') {
    let opts = '<option value="">-- Chọn nguyên liệu --</option>';
    materials.forEach(m => {
        const sel = m.id == selectedId ? 'selected' : '';
        opts += `<option value="${m.id}" ${sel}>${m.name} (Kho: ${m.con_lai_raw || 0})</option>`;
    });
    return opts;
}

function addRecipeRow(productId = '', quantity = 1) {
    const rowId = Date.now() + Math.floor(Math.random() * 1000);
    const html = `
        <div class="recipe-row" id="row-${rowId}">
            <select class="form-control form-control-sm recipe-product" style="flex:2">
                ${getMaterialOptions(productId)}
            </select>
            <input type="number" class="form-control form-control-sm recipe-qty" value="${quantity}" min="1" style="flex:1" placeholder="SL">
            <button type="button" class="btn btn-sm btn-danger" onclick="$('#row-${rowId}').remove()"><i class="fas fa-times"></i></button>
        </div>
    `;
    $('#recipe-list').append(html);
}

// DataTable
const table = $('#drink-table').DataTable({
    processing: true, serverSide: true,
    ajax: { url: '{{ route("banhang.thuc-don.data") }}' },
    columns: [
        { data: 'DT_RowIndex', orderable: false, className: 'pl-3' },
        { data: 'name' },
        { data: 'price' },
        { data: 'recipes', orderable: false },
        { data: 'status' },
        { data: 'action', orderable: false, className: 'text-center' },
    ],
    language: { processing:'Đang tải...', search:'Tìm:', emptyTable:'Chưa có thực đơn nào', info:'_START_-_END_ / _TOTAL_ món', lengthMenu:'Hiển thị _MENU_ dòng', paginate:{next:'›',previous:'‹'} }
});

// Thêm mới
$('#btn-add-drink').click(function() {
    $('#modal-title-drink').html('<i class="fas fa-plus-circle mr-2"></i>Thêm món mới');
    $('#form-drink')[0].reset();
    $('#drink-id').val('');
    $('#drink-image').val('');
    $('#recipe-list').html('');
    addRecipeRow(); // Add 1 empty row
    $('#drinkModal').modal('show');
});

// Sửa
window.openEditDrink = function(id) {
    $.get('/ban-hang/thuc-don/get/' + id, function(res) {
        $('#modal-title-drink').html('<i class="fas fa-edit mr-2"></i>Cập nhật thực đơn');
        $('#drink-id').val(res.id);
        $('#drink-name').val(res.name);
        $('#drink-price').val(res.price);
        $('#drink-status').val(res.status);
        $('#drink-image').val(''); 
        
        $('#recipe-list').html('');
        if(res.recipes && res.recipes.length > 0) {
            res.recipes.forEach(rc => {
                addRecipeRow(rc.product_id, rc.quantity);
            });
        } else {
            addRecipeRow();
        }

        $('#drinkModal').modal('show');
    });
};

// Lưu
$('#btn-save-drink').click(function() {
    const id  = $('#drink-id').val();
    const url = id ? '/ban-hang/thuc-don/update/' + id : '/ban-hang/thuc-don/store';
    
    const recipes = [];
    $('.recipe-row').each(function() {
        const pId = $(this).find('.recipe-product').val();
        const qty = $(this).find('.recipe-qty').val();
        if(pId && qty > 0) {
            recipes.push({product_id: pId, quantity: qty});
        }
    });

    const data = new FormData();
    data.append('name', $('#drink-name').val().trim());
    data.append('price', $('#drink-price').val());
    data.append('status', $('#drink-status').val());
    data.append('recipes', JSON.stringify(recipes));
    
    const imageFile = $('#drink-image')[0].files[0];
    if (imageFile) {
        data.append('image', imageFile);
    }

    if (!data.get('name')) { Swal.fire('Lỗi','Tên món bắt buộc!','warning'); return; }

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        success: res => { if(res.success) { $('#drinkModal').modal('hide'); table.ajax.reload(); Swal.fire({icon:'success',title:'Thành công!', text:res.message, timer:1500,showConfirmButton:false}); } },
        error: xhr => { const err = xhr.responseJSON?.errors; Swal.fire({icon:'error',title:'Lỗi',html: err ? Object.values(err).flat().join('<br>') : 'Có lỗi!'}); }
    });
});

// Xóa
window.deleteDrink = function(id) {
    Swal.fire({title:'Xóa món này khỏi thực đơn?',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonText:'Hủy',confirmButtonText:'Xóa'})
        .then(r => {
            if(r.isConfirmed) {
                $.ajax({ url:'/ban-hang/thuc-don/delete/'+id, type:'DELETE' })
                    .done(res => { table.ajax.reload(); Swal.fire({icon:'success',title:'Thành công!', text:res.message,timer:1200,showConfirmButton:false}); });
            }
        });
};
</script>
