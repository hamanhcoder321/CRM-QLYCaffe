@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold"><i class="fas fa-hand-holding-usd text-success mr-2"></i>Cơ Chế Lương (Salary Mechanism)</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
          <ol class="breadcrumb mr-3 mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Hệ thống</a></li>
            <li class="breadcrumb-item active">Cơ chế lương</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-users mr-2 text-primary"></i>Danh sách áp dụng</h3>
          <form action="{{ route('cocheluong.index') }}" method="GET" class="form-inline ml-auto">
            <select name="user_type" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
              <option value="">Tất cả nhân sự</option>
              <option value="manager" {{ request('user_type') == 'manager' ? 'selected' : '' }}>Quản lý / G.Đốc</option>
              <option value="staff" {{ request('user_type') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
            </select>
          </form>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-light">
              <tr>
                <th class="pl-3">#</th>
                <th>Tên nhân sự</th>
                <th>Chức vụ</th>
                <th>Mức lương cơ bản</th>
                <th>Chính sách giữ lương</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $index => $u)
                @php
                  $mech = $mechanisms->get($u->id);
                @endphp
              <tr>
                <td class="pl-3">{{ $index + 1 }}</td>
                <td>
                  <strong>{{ $u->name }}</strong><br>
                  <small class="text-muted">{{ $u->email }}</small>
                </td>
                
                <td>{{ $u->getPositionName() ?: 'Chưa phân bổ' }}</td>
                <td>
                  @if($mech && $mech->salary)
                    <strong class="text-success">{{ number_format($mech->salary) }} VNĐ</strong>
                    <small class="text-muted">/{{ $u->isManagerSalary() ? 'Tháng' : 'Giờ' }}</small>
                  @else
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Chưa thiết lập</span>
                  @endif
                </td>
                <td>
                  @if($mech && $mech->salary_keep > 0)
                    <span class="badge badge-warning">
                      Giữ {{ number_format($mech->salary_keep) }}đ / tháng
                    </span><br>
                    <small>Tổng cần: {{ number_format($mech->salary_need_keep) }}đ</small>
                  @else
                    <span class="text-muted">Không áp dụng</span>
                  @endif
                </td>
                <td class="text-center">
                  <button class="btn btn-sm btn-primary" onclick="openConfigModal({{ $u->id }}, '{{ $u->name }}', {{ $u->isManagerSalary() ? 'true' : 'false' }})">
                    <i class="fas fa-cog mr-1"></i> Cấu hình
                  </button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-3">Không có nhân sự nào trong hệ thống.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL CẤU HÌNH LƯƠNG --}}
<div class="modal fade" id="salaryConfigModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-sliders-h mr-2"></i>Cấu hình lương: <span id="cfg-user-name" class="text-warning"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body bg-light">
        <input type="hidden" id="cfg-user-id" value="">
        
        <div class="card shadow-none border">
            <div class="card-header bg-white font-weight-bold">
                1. Mức lương cơ bản
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    <label id="lbl-salary">Lương (VNĐ)</label>
                    <div class="input-group">
                        <input type="number" id="cfg-salary" class="form-control form-control-lg text-success font-weight-bold" placeholder="0">
                        <div class="input-group-append">
                            <span class="input-group-text font-weight-bold" id="lbl-salary-unit">VNĐ</span>
                        </div>
                    </div>
                    <small class="text-muted" id="hint-salary"></small>
                </div>
            </div>
        </div>

        <div class="card shadow-none border mt-3">
            <div class="card-header bg-white font-weight-bold">
                2. Phụ cấp bổ sung
            </div>
            <div class="card-body pb-0">
                <!-- Cho Admin -->
                <div class="form-group" id="group-responsibility" style="display: none;">
                    <label>Phụ cấp trách nhiệm (VNĐ / Tháng)</label>
                    <input type="number" id="cfg-responsibility" class="form-control" placeholder="Ví dụ: 1000000">
                    <small class="text-muted">Áp dụng cho Quản lý / Ban Giám Đốc</small>
                </div>
                
                <!-- Cho Staff -->
                <div id="group-staff-allowance" style="display: none;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Phụ cấp chuyên cần (VNĐ / Tháng)</label>
                            <input type="number" id="cfg-enthusiasm" class="form-control" placeholder="Ví dụ: 300000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Phụ cấp hỗ trợ (Cơm/Xe) (VNĐ)</label>
                            <input type="number" id="cfg-support" class="form-control" placeholder="Ví dụ: 500000">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-none border border-warning mt-3">
            <div class="card-header bg-warning text-dark font-weight-bold">
                <i class="fas fa-lock mr-1"></i> 3. Chính sách Giữ Lương (Thế chân)
            </div>
            <div class="card-body pb-0">
                <div class="alert alert-light text-sm mb-3">
                    Tính năng này hỗ trợ tự động trừ dần lương hàng tháng của nhân viên cho đến khi đủ số tiền cần giữ (mục đích chống nghỉ ngang).
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Số tiền giữ lại MỖI THÁNG (VNĐ)</label>
                        <input type="number" id="cfg-salary-keep" class="form-control text-danger" placeholder="Ví dụ: 500000">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>TỔNG TIỀN cần thế chân (VNĐ)</label>
                        <input type="number" id="cfg-salary-need-keep" class="form-control text-danger" placeholder="Ví dụ: 2000000">
                    </div>
                </div>
                <p class="text-sm text-muted mt-1"><em>* Để trống hoặc nhập 0 nếu không áp dụng chính sách này.</em></p>
            </div>
        </div>

      </div>
      <div class="modal-footer bg-white">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="button" id="btn-save-cfg" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Lưu Cấu Hình</button>
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

    window.openConfigModal = function(userId, userName, isAdmin) {
        $('#cfg-user-id').val(userId);
        $('#cfg-user-name').text(userName);
        
        // Cấu hình giao diện theo Role
        if (isAdmin) {
            $('#lbl-salary').text('Lương cứng (VNĐ / THÁNG)');
            $('#lbl-salary-unit').text('VNĐ / THÁNG');
            $('#hint-salary').text('Mức lương cố định trả hàng tháng cho Quản lý.');
            $('#group-staff-allowance').hide();
            $('#group-responsibility').show();
        } else {
            $('#lbl-salary').text('Lương cơ bản (VNĐ / GIỜ)');
            $('#lbl-salary-unit').text('VNĐ / GIỜ');
            $('#hint-salary').text('Mức lương trả cho mỗi giờ làm việc thực tế của Nhân viên ca.');
            $('#group-responsibility').hide();
            $('#group-staff-allowance').show();
        }

        // Lấy dữ liệu cũ
        const btn = $(event.currentTarget);
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.get('{{ route("cocheluong.index") }}/get/' + userId, function(res) {
            btn.html('<i class="fas fa-cog mr-1"></i> Cấu hình');
            
            $('#cfg-salary').val(res.salary);
            $('#cfg-responsibility').val(res.responsibility);
            $('#cfg-enthusiasm').val(res.enthusiasm);
            $('#cfg-support').val(res.support);
            $('#cfg-salary-keep').val(res.salary_keep);
            $('#cfg-salary-need-keep').val(res.salary_need_keep);

            $('#salaryConfigModal').modal('show');
        }).fail(function() {
            btn.html('<i class="fas fa-cog mr-1"></i> Cấu hình');
            Swal.fire('Lỗi', 'Không thể lấy dữ liệu cấu hình!', 'error');
        });
    };

    $('#btn-save-cfg').click(function() {
        const data = {
            user_id: $('#cfg-user-id').val(),
            salary: $('#cfg-salary').val() || 0,
            responsibility: $('#cfg-responsibility').val() || 0,
            enthusiasm: $('#cfg-enthusiasm').val() || 0,
            support: $('#cfg-support').val() || 0,
            salary_keep: $('#cfg-salary-keep').val() || 0,
            salary_need_keep: $('#cfg-salary-need-keep').val() || 0,
        };

        if (data.salary <= 0) {
            Swal.fire('Cảnh báo', 'Vui lòng nhập Mức lương cơ bản lớn hơn 0!', 'warning');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Đang lưu...');

        $.ajax({
            url: '{{ route("cocheluong.update") }}',
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
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Lưu Cấu Hình');
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
