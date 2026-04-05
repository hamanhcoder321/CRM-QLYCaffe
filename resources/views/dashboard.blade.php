@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">Tổng quan hệ thống</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      {{-- ===== STAT CARDS ===== --}}
      <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng lô hàng</span>
              <span class="info-box-number">{{ number_format($tongLoHang) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-invoice"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Đơn nhập</span>
              <span class="info-box-number">{{ number_format($tongDonNhap) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Nhân viên đang làm</span>
              <span class="info-box-number">{{ number_format($tongNhanVien) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-handshake"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Nhà cung cấp</span>
              <span class="info-box-number">{{ number_format($tongNCC) }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- ===== ROW 1: Biểu đồ cột + Tròn ===== --}}
      <div class="row mb-4">

        {{-- Biểu đồ cột: Lô hàng nhập theo tháng --}}
        <div class="col-lg-8">
          <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <h3 class="card-title font-weight-bold">
                <i class="fas fa-chart-bar text-primary mr-2"></i>Lô hàng nhập theo tháng
              </h3>
              <small class="text-muted">6 tháng gần nhất</small>
            </div>
            <div class="card-body">
              <canvas id="chart-lo-hang" height="120"></canvas>
            </div>
          </div>
        </div>

        {{-- Biểu đồ tròn: Kết quả lô hàng --}}
        <div class="col-lg-4">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title font-weight-bold">
                <i class="fas fa-chart-pie text-success mr-2"></i>Kết quả lô hàng
              </h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center">
              <canvas id="chart-ket-qua" height="200" style="max-width:220px"></canvas>
              <div class="mt-3 d-flex gap-3" style="gap:12px; font-size:12px;">
                <span><i class="fas fa-circle" style="color:#22c55e"></i> Hoàn thành</span>
                <span><i class="fas fa-circle" style="color:#f59e0b"></i> Nhập liệu</span>
                <span><i class="fas fa-circle" style="color:#ef4444"></i> Thất bại</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ===== ROW 2: Biểu đồ cột ngang + Bảng lô hàng mới ===== --}}
      <div class="row">

        {{-- Biểu đồ cột ngang: Nhân viên theo bộ phận --}}
        <div class="col-lg-5">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title font-weight-bold">
                <i class="fas fa-users-cog text-warning mr-2"></i>Nhân viên theo bộ phận
              </h3>
            </div>
            <div class="card-body">
              <canvas id="chart-nhan-vien" height="220"></canvas>
            </div>
          </div>
        </div>

        {{-- Bảng lô hàng mới nhất --}}
        <div class="col-lg-7">
          <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <h3 class="card-title font-weight-bold">
                <i class="fas fa-truck-loading text-info mr-2"></i>Lô hàng mới nhất
              </h3>
              <a href="{{ route('nhaphang.list') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm table-hover mb-0" style="font-size:13px">
                <thead class="bg-light">
                  <tr>
                    <th class="pl-3">Tên lô</th>
                    <th>Ngày</th>
                    <th>Giá trị</th>
                    <th>Kết quả</th>
                  </tr>
                </thead>
                <tbody id="recent-lo-hang">
                  <tr><td colspan="4" class="text-center py-3 text-muted">Đang tải...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>

<!-- Chart.js v4 CDN — load trước footer để script inline bên dưới dùng được -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

@include('layouts/parts/footer')

<script>
// ===== DỮ LIỆU TỪ SERVER =====
const barLabels  = @json($barLabels);
const barSoLo    = @json($barSoLo);
const barGiaTri  = @json($barGiaTri);
const pieData    = @json($pieData);
const hbarLabels = @json($hbarLabels);
const hbarData   = @json($hbarData);

// ===== 1. BIỂU ĐỒ CỘT: Lô hàng theo tháng (Chart.js v4) =====
new Chart(document.getElementById('chart-lo-hang'), {
    type: 'bar',
    data: {
        labels: barLabels.length ? barLabels : ['Chưa có dữ liệu'],
        datasets: [
            {
                label: 'Số lô',
                data: barSoLo,
                backgroundColor: 'rgba(79, 70, 229, 0.75)',
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Giá trị (Triệu đ)',
                data: barGiaTri,
                backgroundColor: 'rgba(16, 185, 129, 0.65)',
                borderRadius: 6,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.datasetIndex === 0
                        ? ctx.dataset.label + ': ' + ctx.parsed.y + ' lô'
                        : ctx.dataset.label + ': ' + ctx.parsed.y + ' Tr.đ'
                }
            }
        },
        scales: {
            y:  { type: 'linear', position: 'left',  beginAtZero: true, title: { display: true, text: 'Số lô' } },
            y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, title: { display: true, text: 'Triệu đồng' } }
        }
    }
});

// ===== 2. BIỂU ĐỒ TRÒN: Kết quả lô hàng =====
new Chart(document.getElementById('chart-ket-qua'), {
    type: 'doughnut',
    data: {
        labels: ['Hoàn thành', 'Nhập liệu', 'Thất bại'],
        datasets: [{
            data: pieData,
            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.label + ': ' + ctx.parsed + ' lô'
                }
            }
        }
    }
});

// ===== 3. BIỂU ĐỒ CỘT NGANG: Nhân viên theo bộ phận =====
new Chart(document.getElementById('chart-nhan-vien'), {
    type: 'bar',
    data: {
        labels: hbarLabels.length ? hbarLabels : ['Chưa có dữ liệu'],
        datasets: [{
            label: 'Số nhân viên',
            data: hbarData,
            backgroundColor: [
                '#6366f1','#22c55e','#f59e0b','#ef4444',
                '#3b82f6','#ec4899','#14b8a6','#f97316'
            ],
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ctx.parsed.x + ' người' } }
        },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

// ===== 4. BẢNG LÔ HÀNG MỚI NHẤT =====
$.get('/nhap-hang/data', { draw:1, start:0, length:8, columns:[{data:'name_arrange'},{data:'day'},{data:'total_arrange'},{data:'result'}], order:[{column:0,dir:'desc'}] })
    .done(function(res) {
        const rows = res.data || [];
        if (!rows.length) {
            $('#recent-lo-hang').html('<tr><td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td></tr>');
            return;
        }
        let html = '';
        rows.slice(0,8).forEach(r => {
            html += `<tr>
                <td class="pl-3" style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${r.name_arrange ?? '—'}</td>
                <td>${r.day ?? '—'}</td>
                <td>${r.total_arrange ?? '—'}</td>
                <td>${r.result ?? '—'}</td>
            </tr>`;
        });
        $('#recent-lo-hang').html(html);
    })
    .fail(function() {
        $('#recent-lo-hang').html('<tr><td colspan="4" class="text-center text-muted">Không tải được dữ liệu</td></tr>');
    });
</script>