<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Operator QC - Tabel Berdampingan Lengkap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 0.9rem; }
        .section-title { background-color: #2c3e50; color: #f1c40f; padding: 6px 12px; font-weight: bold; font-size: 0.95rem; border-radius: 4px; text-transform: uppercase; }
        th { font-size: 0.72rem; padding: 5px 3px !important; background-color: #f8f9fa !important; }
        td { font-size: 0.83rem; padding: 5px 3px !important; }
        .table-responsive { max-height: 450px; overflow-y: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 4px; }
        .form-label-sm { font-size: 0.8rem; font-weight: bold; margin-bottom: 2px; }
    </style>
</head>
<body class="bg-light container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Panel Operator QC</h3>
            <p class="text-muted small mb-0">Input & Kelola Data Hasil Trase Kopi (Multi-Tabel Berdampingan)</p>
        </div>
        <div>
            <a href="/monitor" target="_blank" class="btn btn-sm btn-info me-2 fw-semibold shadow-sm">Buka Layar Monitor</a>
            <a href="/logout" class="btn btn-sm btn-danger fw-semibold shadow-sm">Log Out</a>
        </div>
    </div>

    <div class="card mb-3 shadow-sm">
        <div class="card-body py-2">
            <?php if (!$activeBatch): ?>
                <div class="alert alert-warning py-1 px-3 mb-2 small fw-semibold">Status: Tidak ada Batch Produksi yang Aktif saat ini!</div>
                <form action="/batch/buka" method="POST" class="row g-2 align-items-center">
                    <?= csrf_field() ?>
                    <div class="col-md-9">
                        <input type="text" name="nama_batch" class="form-control form-control-sm" placeholder="Masukkan Nama Batch Baru (Contoh: BATCH-SHIFT-A)" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-success w-100 fw-semibold">Buka Batch Baru</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small">Batch Aktif Saat Ini:</span>
                        <strong class="text-success ms-1 fs-5"><?= esc($activeBatch['nama_batch']) ?></strong>
                    </div>
                    <a href="/batch/tutup/<?= $activeBatch['id'] ?>" class="btn btn-sm btn-warning fw-semibold px-3" onclick="return confirm('Tutup & Kunci batch produksi ini?')">Tutup Batch Kerja</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($activeBatch): ?>
    <div class="card mb-3 shadow-sm border-primary">
        <div class="card-header bg-primary text-white py-1 px-3 fw-bold d-flex justify-content-between align-items-center">
            <span id="form-title" class="small">Form Input Angka Hasil Trase (%)</span>
            <button type="button" id="btn-batal-edit" class="btn btn-xs btn-light text-dark py-0 px-2 d-none" style="font-size: 11px;" onclick="resetForm()">Batal Edit (Mode Baru)</button>
        </div>
        <form action="/trase/simpan" method="POST" class="card-body row g-2 py-2" id="form-trase">
            <?= csrf_field() ?>
            <input type="hidden" name="batch_id" value="<?= $activeBatch['id'] ?>">
            <input type="hidden" name="id" id="input-id" value="">
            
            <div class="col-md-2">
                <label class="form-label-sm">Jenis Kopi</label>
                <select name="jenis_kopi" id="input-jenis-kopi" class="form-select form-select-sm" required>
                    <option value="TRACEMAX 11%">KOPI TRACEMAX 11%</option>
                    <option value="KOPI ENTENG RINGAN">KOPI ENTENG RINGAN</option>
                </select>
            </div>
            <div class="col-md-1"><label class="form-label-sm">Mesin GS</label><input type="text" name="mesin_gs" id="input-mesin-gs" class="form-control form-control-sm" required placeholder="Ex: 2"></div>
            <div class="col-md-1"><label class="form-label-sm">AP</label><input type="number" step="0.01" name="ap" id="input-ap" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1"><label class="form-label-sm">CERA</label><input type="number" step="0.01" name="cera" id="input-cera" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1"><label class="form-label-sm">HITAM</label><input type="number" step="0.01" name="hitam" id="input-hitam" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1"><label class="form-label-sm">BUSUK</label><input type="number" step="0.01" name="busuk" id="input-busuk" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1"><label class="form-label-sm">PECAH</label><input type="number" step="0.01" name="pecah" id="input-pecah" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1"><label class="form-label-sm">KULIT</label><input type="number" step="0.01" name="kulit" id="input-kulit" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1"><label class="form-label-sm">BATU</label><input type="number" step="0.01" name="batu" id="input-batu" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1"><label class="form-label-sm">GLNDG</label><input type="number" step="0.01" name="gelondong" id="input-gelondong" class="form-control form-control-sm" value="0.00"></div>
            <div class="col-md-1">
                <label class="form-label-sm">Ket</label>
                <select name="keterangan" id="input-keterangan" class="form-select form-select-sm">
                    <option value="Perjam">Perjam</option>
                    <option value="Setting">Setting</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold shadow-sm" id="btn-submit">Simpan</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="row g-3">
        
        <div class="col-xl-6">
            <div class="section-title mb-2">1. RIWAYAT DATA: TRACEMAX 11%</div>
            <div class="table-responsive bg-white">
                <table class="table table-bordered table-striped text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th>JAM</th><th>MSN</th><th>AP</th><th>CR</th><th>HT</th><th>BS</th><th>PC</th><th>KL</th><th>BT</th><th>GL</th><th class="table-success">TTL</th><th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $adaTracemax = false;
                        if (!empty($dataTrase)): 
                            foreach($dataTrase as $row): 
                                if(trim($row['jenis_kopi']) === 'TRACEMAX 11%'):
                                    $adaTracemax = true;
                        ?>
                            <tr>
                                <td class="fw-bold text-secondary"><?= substr($row['pukul'], 0, 5) ?></td>
                                <td><span class="badge bg-dark px-2"><?= esc($row['mesin_gs']) ?></span></td>
                                <td><?= $row['ap'] ?></td><td><?= $row['cera'] ?></td><td><?= $row['hitam'] ?></td><td><?= $row['busuk'] ?></td>
                                <td><?= $row['pecah'] ?></td><td><?= $row['kulit'] ?></td><td><?= $row['batu'] ?></td><td><?= $row['gelondong'] ?></td>
                                <td class="fw-bold text-success bg-light"><?= $row['total'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-link p-0 text-primary me-2 fw-semibold text-decoration-none" onclick="siapEdit(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">Edit</button>
                                    <a href="/trase/hapus/<?= $row['id'] ?>" class="btn btn-sm btn-link p-0 text-danger fw-semibold text-decoration-none" onclick="return confirm('Hapus permanen data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php 
                                endif;
                            endforeach; 
                        endif; 
                        if(!$adaTracemax): ?>
                            <tr><td colspan="12" class="text-muted p-3 small">Belum ada data Tracemax masuk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="section-title mb-2" style="background-color: #2980b9;">2. RIWAYAT DATA: KOPI ENTENG RINGAN</div>
            <div class="table-responsive bg-white">
                <table class="table table-bordered table-striped text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th>JAM</th><th>MSN</th><th>AP</th><th>CR</th><th>HT</th><th>BS</th><th>PC</th><th>KL</th><th>BT</th><th>GL</th><th class="table-success">TTL</th><th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $adaEnteng = false;
                        if (!empty($dataTrase)): 
                            foreach($dataTrase as $row): 
                                if(trim($row['jenis_kopi']) === 'KOPI ENTENG RINGAN'):
                                    $adaEnteng = true;
                        ?>
                            <tr>
                                <td class="fw-bold text-secondary"><?= substr($row['pukul'], 0, 5) ?></td>
                                <td><span class="badge bg-dark px-2"><?= esc($row['mesin_gs']) ?></span></td>
                                <td><?= $row['ap'] ?></td><td><?= $row['cera'] ?></td><td><?= $row['hitam'] ?></td><td><?= $row['busuk'] ?></td>
                                <td><?= $row['pecah'] ?></td><td><?= $row['kulit'] ?></td><td><?= $row['batu'] ?></td><td><?= $row['gelondong'] ?></td>
                                <td class="fw-bold text-success bg-light"><?= $row['total'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-link p-0 text-primary me-2 fw-semibold text-decoration-none" onclick="siapEdit(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">Edit</button>
                                    <a href="/trase/hapus/<?= $row['id'] ?>" class="btn btn-sm btn-link p-0 text-danger fw-semibold text-decoration-none" onclick="return confirm('Hapus permanen data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php 
                                endif;
                            endforeach; 
                        endif; 
                        if(!$adaEnteng): ?>
                            <tr><td colspan="12" class="text-muted p-3 small">Belum ada data Kopi Enteng Ringan masuk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function siapEdit(data) {
            document.getElementById('form-title').innerText = "Mode Edit Data (Mengubah Data)";
            document.getElementById('btn-submit').innerText = "Update";
            document.getElementById('btn-batal-edit').classList.remove('d-none');
            
            document.getElementById('input-id').value = data.id;
            document.getElementById('input-jenis-kopi').value = data.jenis_kopi;
            document.getElementById('input-mesin-gs').value = data.mesin_gs;
            document.getElementById('input-ap').value = data.ap;
            document.getElementById('input-cera').value = data.cera;
            document.getElementById('input-hitam').value = data.hitam;
            document.getElementById('input-busuk').value = data.busuk;
            document.getElementById('input-pecah').value = data.pecah;
            document.getElementById('input-kulit').value = data.kulit;
            document.getElementById('input-batu').value = data.batu;
            document.getElementById('input-gelondong').value = data.gelondong;
            document.getElementById('input-keterangan').value = data.keterangan;
            
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function resetForm() {
            document.getElementById('form-title').innerText = "Form Input Angka Hasil Trase (%)";
            document.getElementById('btn-submit').innerText = "Simpan";
            document.getElementById('btn-batal-edit').classList.add('d-none');
            
            document.getElementById('form-trase').reset();
            document.getElementById('input-id').value = "";
        }
    </script>
</body>
</html>