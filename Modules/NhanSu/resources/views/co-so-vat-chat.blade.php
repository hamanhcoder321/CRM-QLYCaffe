@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold"><i class="fas fa-building text-primary mr-2"></i>Quản lý Cơ Sở Vật Chất</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
          <ol class="breadcrumb mr-3 mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Nhân sự</a></li>
            <li class="breadcrumb-item active">Cơ sở vật chất</li>
          </ol>
          <button class="btn btn-primary btn-sm pl-3 pr-3" onclick="openAddModal()">
            <i class="fas fa-plus mr-1"></i>Thêm tài sản
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-primary"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng tài sản</span>
              <span class="info-box-number" id="stat-total">{{ $facilities->count() }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Đang sử dụng</span>
              <span class="info-box-number" id="stat-active">{{ $facilities->where('status', 'Đang sử dụng')->count() }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fas fa-tools"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Cần bảo trì</span>
              <span class="info-box-number" id="stat-maintenance">{{ $facilities->where('status', 'Cần bảo trì')->count() }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-list mr-2 text-primary"></i>Danh sách cơ sở vật chất</h3>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-light">
              <tr>
                <th class="pl-3">#</th>
                <th>Hình ảnh</th>
                <th>Tên tài sản</th>
                <th>Loại</th>
                <th>Số lượng</th>
                <th>Tình trạng</th>
                <th>Ngày mua</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($facilities as $index => $fac)
              <tr>
                <td class="pl-3">{{ $index + 1 }}</td>
                <td>
                  @if($fac->image)
                    <img src="{{ asset('uploads/' . $fac->image) }}" alt="{{ $fac->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                  @else
                    <div style="width: 50px; height: 50px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                      <i class="fas fa-image"></i>
                    </div>
                  @endif
                </td>
                <td>{{ $fac->name }}</td>
                <td>{{ $fac->description }}</td>
                <td>{{ $fac->number }}</td>
                <td>
                  @if($fac->status == 'Đang sử dụng')
                    <span class="badge badge-success">{{ $fac->status }}</span>
                  @elseif($fac->status == 'Cần bảo trì')
                    <span class="badge badge-warning">{{ $fac->status }}</span>
                  @else
                    <span class="badge badge-danger">{{ $fac->status }}</span>
                  @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($fac->day)->format('d/m/Y') }}</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-info" onclick="editFacility({{ $fac->id }})"><i class="fas fa-edit"></i></button>
                  <form action="{{ route('nhansu.facilities.delete', $fac->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xoá tài sản này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-3">Chưa có dữ liệu...</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL THÊM/SỬA --}}
<div class="modal fade" id="facilityModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Thêm Cơ Sở Vật Chất</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="fac-id" value="">
        <div class="form-group text-center">
            <label for="fac-image" style="cursor: pointer;">
                <div id="image-preview" style="width: 120px; height: 120px; border: 2px dashed #ccc; border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center; background: #f8f9fa; overflow: hidden; position: relative;">
                    <i class="fas fa-camera text-muted fa-2x"></i>
                </div>
                <div class="mt-2 text-primary"><i class="fas fa-upload mr-1"></i>Chọn hình ảnh</div>
            </label>
            <input type="file" id="fac-image" class="d-none" accept="image/*">
        </div>
        <div class="form-group">
          <label>Tên tài sản <span class="text-danger">*</span></label>
          <input type="text" id="fac-name" class="form-control" placeholder="Nhập tên tài sản">
        </div>
        <div class="form-group">
          <label>Loại tài sản</label>
          <select id="fac-type" class="form-control">
            <option value="Trang thiết bị nội thất">Trang thiết bị nội thất</option>
            <option value="Máy móc thiết bị">Máy móc thiết bị</option>
            <option value="Công cụ dụng cụ">Công cụ dụng cụ</option>
          </select>
        </div>
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Số lượng</label>
            <input type="number" id="fac-number" class="form-control" value="1" min="1">
          </div>
          <div class="col-md-6 form-group">
            <label>Tình trạng</label>
            <select id="fac-status" class="form-control">
              <option value="Đang sử dụng">Đang sử dụng</option>
              <option value="Cần bảo trì">Cần bảo trì</option>
              <option value="Hư hỏng">Hư hỏng</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Ngày mua</label>
          <input type="date" id="fac-day" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group">
          <label>Ghi chú</label>
          <textarea id="fac-note" class="form-control" rows="3" placeholder="Nhập ghi chú..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="button" id="btn-save-fac" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Lưu</button>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $('#fac-image').change(function(e) {
        if(e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html(`<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    window.openAddModal = function() {
        $('.modal-title').html('<i class="fas fa-plus-circle mr-2"></i>Thêm Cơ Sở Vật Chất');
        $('#fac-id').val('');
        $('#fac-name').val('');
        $('#fac-number').val('1');
        $('#fac-day').val('{{ date('Y-m-d') }}');
        $('#fac-note').val('');
        $('#fac-image').val('');
        $('#image-preview').html('<i class="fas fa-camera text-muted fa-2x"></i>');
        $('#facilityModal').modal('show');
    };

    window.editFacility = function(id) {
        $('.modal-title').html('<i class="fas fa-edit mr-2"></i>Cập nhật Cơ Sở Vật Chất');
        $.get('{{ route("nhansu.facilities.get", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), function(res) {
            $('#fac-id').val(res.id);
            $('#fac-name').val(res.name);
            $('#fac-type').val(res.description);
            $('#fac-number').val(res.number);
            $('#fac-status').val(res.status);
            $('#fac-day').val(res.day ? res.day.split(' ')[0] : '');
            $('#fac-note').val(res.note);
            if (res.image) {
                $('#image-preview').html(`<img src="/uploads/${res.image}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">`);
            } else {
                $('#image-preview').html('<i class="fas fa-camera text-muted fa-2x"></i>');
            }
            $('#facilityModal').modal('show');
        });
    };

    $('#btn-save-fac').click(function() {
        const name = $('#fac-name').val().trim();
        if(!name) {
            Swal.fire('Lỗi', 'Vui lòng nhập tên tài sản!', 'warning');
            return;
        }

        let formData = new FormData();
        formData.append('name', name);
        formData.append('description', $('#fac-type').val());
        formData.append('number', $('#fac-number').val());
        formData.append('status', $('#fac-status').val());
        formData.append('day', $('#fac-day').val());
        formData.append('note', $('#fac-note').val().trim());
        
        let imageFile = $('#fac-image')[0].files[0];
        if(imageFile) {
            formData.append('image', imageFile);
        }

        const id = $('#fac-id').val();
        const url = id ? '{{ route("nhansu.facilities.update", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id) : '{{ route("nhansu.facilities.store") }}';

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Đang lưu...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                $('#btn-save-fac').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Lưu');
                let err = 'Đã có lỗi xảy ra!';
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    err = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                Swal.fire('Lỗi', err, 'error');
            }
        });
    });
});
</script>
