<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LIVE MONITORING MULTI KOPI 24 JAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #ffffff; }
        .table-dark { background-color: #1e1e1e; }
        .header-title { background-color: #008080; color: white; padding: 10px; font-weight: bold; position: relative; }
        .logout-btn { position: absolute; right: 15px; top: 18px; }
        .kopi-section-title { background-color: #2c3e50; color: #f1c40f; padding: 6px 15px; font-weight: bold; font-size: 1.2rem; border-radius: 4px; }
        th { font-size: 0.85rem; }
        td { font-size: 0.95rem; }
    </style>
</head>
<body>
<div class="container-fluid mt-2">
    <div class="header-title mb-3 text-center text-uppercase rounded shadow-sm">
        <h3 class="fw-bold m-0">Monitoring Jam-Jaman Trase Kopi</h3>
        <h5 id="batch-title" class="text-light my-1">LOADING BATCH...</h5>
        <div id="live-clock" class="text-light small">Loading Waktu...</div>
        <a href="/logout" class="btn btn-sm btn-outline-light logout-btn">Exit</a>
    </div>

    <div class="mb-4">
        <div class="kopi-section-title mb-2">1. KOPI TRACEMAX 11%</div>
        <div class="table-responsive">
            <table class="table table-dark table-bordered table-striped text-center align-middle mb-0">
                <thead>
                    <tr class="table-active text-white">
                        <th rowspan="2">TANGGAL</th><th rowspan="2">MESIN GS</th><th rowspan="2">PUKUL</th>
                        <th colspan="8">HASIL TRASE (%)</th><th rowspan="2">TOTAL</th><th rowspan="2">KETERANGAN</th>
                    </tr>
                    <tr class="table-active text-white">
                        <th>AP</th><th>CERA</th><th>HITAM</th><th>BUSUK</th><th>PECAH</th><th>KULIT</th><th>BATU</th><th>GELONDONG</th>
                    </tr>
                </thead>
                <tbody id="data-tracemax"></tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <div class="kopi-section-title mb-2" style="background-color: #2980b9;">2. KOPI ENTENG RINGAN</div>
        <div class="table-responsive">
            <table class="table table-dark table-bordered table-striped text-center align-middle mb-0">
                <thead>
                    <tr class="table-active text-white">
                        <th rowspan="2">TANGGAL</th><th rowspan="2">MESIN GS</th><th rowspan="2">PUKUL</th>
                        <th colspan="8">HASIL TRASE (%)</th><th rowspan="2">TOTAL</th><th rowspan="2">KETERANGAN</th>
                    </tr>
                    <tr class="table-active text-white">
                        <th>AP</th><th>CERA</th><th>HITAM</th><th>BUSUK</th><th>PECAH</th><th>KULIT</th><th>BATU</th><th>GELONDONG</th>
                    </tr>
                </thead>
                <tbody id="data-enteng"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    async function loadDataTrase() {
        try {
            let response = await fetch('/trase/api-data');
            let result = await response.json();
            
            document.getElementById('batch-title').innerText = "BATCH PRODUKSI: " + result.nama_batch;

            let htmlTracemax = '';
            let htmlEnteng = '';
            let data = result.data;

            if(data.length > 0) {
                data.forEach(row => {
                    let tr = `
                        <tr>
                            <td>${row.tanggal}</td>
                            <td><span class="badge bg-info text-dark fs-5 fw-bold px-2">${row.mesin_gs}</span></td>
                            <td class="fw-bold text-warning">${row.pukul}</td>
                            <td>${row.ap || '0.00'}</td><td>${row.cera || '0.00'}</td><td>${row.hitam || '0.00'}</td><td>${row.busuk || '0.00'}</td>
                            <td>${row.pecah || '0.00'}</td><td>${row.kulit || '0.00'}</td><td>${row.batu || '0.00'}</td><td>${row.gelondong || '0.00'}</td>
                            <td class="fw-bold text-success fs-4">${row.total}</td>
                            <td><span class="badge ${row.keterangan === 'Setting' ? 'bg-secondary' : 'bg-success'}">${row.keterangan}</span></td>
                        </tr>`;
                    
                    // Filter hanya untuk 2 jenis kopi ini
                    if(row.jenis_kopi === 'TRACEMAX 11%') {
                        htmlTracemax += tr;
                    } else if(row.jenis_kopi === 'KOPI ENTENG RINGAN') {
                        htmlEnteng += tr;
                    }
                });
            }

            let emptyRow = `<tr><td colspan="13" class="text-muted small">Belum ada data masuk untuk jenis kopi ini</td></tr>`;
            document.getElementById('data-tracemax').innerHTML = htmlTracemax || emptyRow;
            document.getElementById('data-enteng').innerHTML = htmlEnteng || emptyRow;

        } catch (error) { console.error("Error sinkronisasi:", error); }
    }

    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').innerText = now.toLocaleString('id-ID') + " WIB";
    }

    setInterval(loadDataTrase, 10000); // REFRESH DATA MONITOR TIAP 10 DETIK
    setInterval(updateClock, 1000);
    loadDataTrase();
    updateClock();
</script>
</body>
</html>