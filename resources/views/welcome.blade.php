@extends('layouts.template')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- TABEL STOK -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary"><i class="fas fa-boxes"></i> Daftar Stok Barang</h4>
                    <span class="text-muted small">Update: <span id="last-updated">-</span></span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabel-stok" class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 15%">Stok</th>
                                    <th style="width: 20%">Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHART STOK -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0 text-primary"><i class="fas fa-chart-bar"></i> Grafik Stok</h4>
                </div>
                <div class="card-body">
                    <canvas id="stokChart" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let stokChart;

    function badgeStatus(stok) {
        if (stok == 0) {
            return `<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Habis</span>`;
        } else if (stok <= 5) {
            return `<span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Sedikit</span>`;
        } else {
            return `<span class="badge badge-success"><i class="fas fa-check-circle"></i> Cukup</span>`;
        }
    }

    function updateChart(data) {
        const labels = data.map(item => item.barang_nama);
        const stokData = data.map(item => item.barang_stok);

        if (stokChart) {
            stokChart.data.labels = labels;
            stokChart.data.datasets[0].data = stokData;
            stokChart.update();
        } else {
            const ctx = document.getElementById('stokChart').getContext('2d');
            stokChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: stokData,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                }
            });
        }
    }

    function loadStok() {
        $.get('/dashboard/stok-json', function(data) {
            let table = $('#tabel-stok').DataTable();
            table.clear();

            data.forEach((item, index) => {
                table.row.add([
                    index + 1,
                    item.barang_nama,
                    item.barang_stok,
                    badgeStatus(item.barang_stok)
                ]);
            });

            table.draw();
            $('#last-updated').text(new Date().toLocaleTimeString());
            updateChart(data);
        });
    }

    $(document).ready(function () {
        $('#tabel-stok').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: false
        });

        loadStok();
        setInterval(loadStok, 10000); // refresh tiap 10 detik
    });
</script>
@endpush
