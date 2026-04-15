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
              <span class="info-box-text">Tổng loại sản phẩm</span>
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
          <a href="{{ route('banhang.thuc-uong') }}" class="btn btn-sm btn-outline-warning">
            <i class="fas fa-plus mr-1"></i>Nhập thêm hàng
          </a>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="tonkho-table" class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-gradient-info">
              <tr>
                <th class="pl-3">#</th>
                <th>Tên sản phẩm</th>
                <th>Lô hàng</th>
                <th>Đơn giá</th>
                <th>Nhập về</th>
                <th>Đã xuất bán</th>
                <th>Tồn kho</th>
                <!-- <th>Giá trị tồn</th> -->
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')

<style>
.badge-success { color:#15803d;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; }
.badge-warning { color:#854d0e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; }
.badge-danger  { color:#b91c1c;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; }
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
        { data: 'price' },
        { data: 'number_in' },
        { data: 'number_out' },
        { data: 'con_lai', orderable: false },
        { data: null, orderable: false, render: (_, __, row) => {
            const val = (row.con_lai_raw ?? 0) * (row.price_raw ?? 0);
            return val > 0 ? '<strong>' + val.toLocaleString('vi-VN') + ' đ</strong>' : '—';
        }},
    ],
    language: { processing:'Đang tải...', search:'Tìm:', emptyTable:'Chưa có dữ liệu tồn kho', info:'_START_-_END_ / _TOTAL_ sản phẩm', lengthMenu:'Hiển thị _MENU_ dòng', paginate:{next:'›',previous:'‹'} },
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
</script>
