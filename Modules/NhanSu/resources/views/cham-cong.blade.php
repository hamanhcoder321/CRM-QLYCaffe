@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold"><i class="fas fa-calendar-check text-primary mr-2"></i>Chấm Công & Giờ Làm</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
          <ol class="breadcrumb mr-3 mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Nhân sự</a></li>
            <li class="breadcrumb-item active">Chấm công</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-users mr-2 text-primary"></i>Danh sách Nhân sự (Ngày: {{ date('d/m/Y') }})</h3>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-light">
              <tr>
                <th class="pl-3">#</th>
                <th>Nhân sự</th>
                <th>Chức vụ / Vị trí</th>
                <th>Trạng thái chấm công (Hôm nay)</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $index => $u)
              <tr>
                <td class="pl-3">{{ $index + 1 }}</td>
                <td>
                  <strong>{{ $u->name }}</strong><br>
                  <small class="text-muted">{{ $u->email }}</small>
                </td>

                <td>{{ $u->getPositionName() ?: 'Chưa phân bổ' }}</td>
                <td>
                  @php
                      // Kiểm tra xem user này đã chấm công hôm nay chưa
                      // Nếu muốn lấy tất cả ca thì cần query phức tạp hơn, ở đây ta kiểm tra records hiện có
                      $timesheets = \App\Models\Timesheet::where('user_id', $u->id)->whereDate('day', date('Y-m-d'))->get();
                  @endphp

                  @if($timesheets->count() > 0)
                      @foreach($timesheets as $ts)
                          <span class="badge badge-success mb-1">
                              <i class="fas fa-check-circle"></i> {{ $ts->shift }} 
                              @if($ts->shift != 'Fulltime') ({{ $ts->hour }}h) @endif
                          </span><br>
                      @endforeach
                  @else
                      <span class="badge badge-warning"><i class="fas fa-clock"></i> Chưa chấm công</span>
                  @endif
                </td>
                <td class="text-center">
                  <button class="btn btn-sm btn-primary" onclick="openTimekeepingModal({{ $u->id }}, '{{ $u->name }}', {{ $u->isManagerSalary() ? 'true' : 'false' }})">
                    <i class="fas fa-fingerprint mr-1"></i> Chấm công
                  </button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-3">Không có nhân sự nào cần chấm công.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL CHẤM CÔNG --}}
<div class="modal fade" id="timekeepingModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-fingerprint mr-2"></i>Chấm Công: <span id="tk-user-name" class="text-warning"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="tk-user-id" value="">
        
        <div class="form-group">
          <label>Ngày chấm công</label>
          <input type="date" id="tk-day" class="form-control" value="{{ date('Y-m-d') }}">
        </div>

        <!-- Vùng dành cho Nhân Viên (Part-time) -->
        <div id="staff-section" style="display: none;">
            <div class="form-group">
                <label>Ca làm việc <span class="text-danger">*</span></label>
                <select id="tk-shift" class="form-control">
                    <option value="Sáng">Ca Sáng</option>
                    <option value="Chiều">Ca Chiều</option>
                    <option value="Tối">Ca Tối</option>
                </select>
            </div>
            <div class="form-group">
                <label>Số giờ làm thực tế <span class="text-danger">*</span></label>
                <input type="number" id="tk-hour" class="form-control" value="4" min="0.5" step="0.5" placeholder="Ví dụ: 4">
            </div>
            <div class="form-group">
                <label>Ghi chú thêm</label>
                <textarea id="tk-note-staff" class="form-control" rows="2" placeholder="Nhập ghi chú nếu có..."></textarea>
            </div>
        </div>

        <!-- Vùng dành cho Admin (Full-time & Trách nhiệm) -->
        <div id="admin-section" style="display: none;">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i> Quản lý được tính công <strong>Fulltime (8h/ngày)</strong> theo mặc định.
            </div>
            <div class="form-group">
                <label>Đánh giá / Trách nhiệm quản lý chi nhánh</label>
                <textarea id="tk-note-admin" class="form-control" rows="3" placeholder="Ghi nhận tình hình hoàn thành trách nhiệm quản lý..."></textarea>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="button" id="btn-save-tk" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Lưu chấm công</button>
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

    let currentUserIsAdmin = false;

    window.openTimekeepingModal = function(userId, userName, isAdmin) {
        $('#tk-user-id').val(userId);
        $('#tk-user-name').text(userName);
        $('#tk-day').val('{{ date('Y-m-d') }}');
        
        currentUserIsAdmin = isAdmin;

        if (isAdmin) {
            $('#staff-section').hide();
            $('#admin-section').show();
            $('#tk-note-admin').val('');
        } else {
            $('#admin-section').hide();
            $('#staff-section').show();
            $('#tk-shift').val('Sáng');
            $('#tk-hour').val('4');
            $('#tk-note-staff').val('');
        }

        $('#timekeepingModal').modal('show');
    };

    $('#btn-save-tk').click(function() {
        const userId = $('#tk-user-id').val();
        const day = $('#tk-day').val();
        let data = {
            user_id: userId,
            day: day
        };

        if (currentUserIsAdmin) {
            data.note = $('#tk-note-admin').val().trim();
        } else {
            data.shift = $('#tk-shift').val();
            data.hour = $('#tk-hour').val();
            data.note = $('#tk-note-staff').val().trim();

            if (!data.hour || data.hour < 0.5) {
                Swal.fire('Lỗi', 'Số giờ làm không hợp lệ!', 'warning');
                return;
            }
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Đang lưu...');

        $.ajax({
            url: '{{ route("nhansu.cham-cong.store") }}',
            type: 'POST',
            data: data,
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
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Lưu chấm công');
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
