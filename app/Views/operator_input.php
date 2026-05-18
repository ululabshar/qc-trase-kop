<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Operator QC - Tabel Berdampingan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }

        .card-custom {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            background-color: #ffffff;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #000000;
            margin-bottom: 3px;
        }

        .form-control-sm,
        .form-select-sm {
            font-size: 0.85rem;
            padding: 4px 8px;
            color: #000000;
            border: 1px solid #999999;
        }

        /* 1. Pertajam Header Tabel (Teks Putih Terang Kontras Tinggi) */
        th {
            font-size: 11px;
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            text-align: center;
            vertical-align: middle;
            padding: 8px !important;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* 2. Pertajam Isi Data Tabel (Warna Hitam Pekat, Bukan Abu-abu) */
        td {
            font-size: 13px;
            color: #000000 !important;
            text-align: center;
            vertical-align: middle;
            padding: 6px !important;
            font-weight: 500;
        }

        /* 3. Buat Jam Lebih Nyata & Tebal */
        td.text-muted {
            color: #000000 !important;
            font-weight: bold;
        }

        .bg-tracemax {
            background-color: #0b5ed7 !important;
            color: white;
            font-weight: bold;
        }

        .bg-ker {
            background-color: #1976d2 !important;
            color: white;
            font-weight: bold;
        }

        /* 4. Pertajam Tombol Aksi */
        .btn-action-edit {
            color: #0d6efd !important;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-action-edit:hover {
            text-decoration: underline;
        }

        .btn-action-hapus {
            color: #dc3545 !important;
            font-weight: bold;
            text-decoration: none;
            margin-left: 8px;
        }

        .btn-action-hapus:hover {
            text-decoration: underline;
        }

        .btn-action-cetak {
            color: #198754 !important;
            font-weight: bold;
            text-decoration: none;
            margin-left: 8px;
        }

        .btn-action-cetak:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-custom py-2 mb-3">
        <div class="container-fluid px-4">
            <div>
                <h4 class="m-0 fw-bold text-dark">Panel Operator QC</h4>
                <span class="text-muted small">Input & Kelola Data Hasil Trase Kopi (Multi-Tabel Berdampingan)</span>
            </div>
            <div>
                <a href="/monitor" target="_blank" class="btn btn-sm btn-info text-white fw-bold me-2">Buka Layar Monitor</a>
                <a href="/logout" class="btn btn-sm btn-danger fw-bold">Log Out</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">

        <div class="card card-custom p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small">Batch Aktif Saat Ini:</span>
                    <h4 class="fw-bold text-success m-0 display-6" style="font-size: 1.5rem;"><?= $activeBatch ? esc($activeBatch['id']) : '<span class="text-danger">TUTUP</span>' ?></h4>
                </div>
                <?php if ($activeBatch): ?>
                    <a href="/batch/tutup/<?= $activeBatch['id'] ?>" class="btn btn-warning fw-bold shadow-sm" onclick="return confirm('Tutup Batch Kerja?')">Tutup Batch Kerja</a>
                <?php else: ?>
                    <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalBukaBatch">Buka Batch Baru</button>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($activeBatch): ?>
            <div class="card card-custom mb-4">
                <div class="card-header bg-primary text-white py-2 fw-bold" style="font-size: 0.9rem;">
                    Form Input Angka Hasil Trase (%)
                </div>
                <div class="card-body p-3">
                    <form action="/trase/simpan" method="POST" id="formTrase">
                        <?= csrf_field() ?>
                        <input type="hidden" name="batch_id" value="<?= $activeBatch['id'] ?>">
                        <input type="hidden" name="id" id="trase_id">

                        <div class="row g-2 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label">Jenis Kopi</label>
                                <select name="jenis_kopi" id="jenis_kopi" class="form-select form-select-sm" required>
                                    <option value="TRACEMAX 11%">KOPI TRACEMAX 11%</option>
                                    <option value="KOPI ENTENG RINGAN">KOPI ENTENG RINGAN</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Mesin GS</label>
                                <input type="text" name="mesin_gs" id="mesin_gs" class="form-control form-control-sm" placeholder="Ex: 2" required>
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-primary">AP</label>
                                <input type="number" step="0.01" name="ap" id="ap" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-primary">CERA</label>
                                <input type="number" step="0.01" name="cera" id="cera" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-4 col-md-1">
                                <label class="form-label text-danger">HITAM</label>
                                <input type="number" step="0.01" name="hitam" id="hitam" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-4 col-md-1">
                                <label class="form-label text-danger">BUSUK</label>
                                <input type="number" step="0.01" name="busuk" id="busuk" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-4 col-md-1">
                                <label class="form-label text-danger">PECAH</label>
                                <input type="number" step="0.01" name="pecah" id="pecah" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-4 col-md-1">
                                <label class="form-label text-danger">KULIT</label>
                                <input type="number" step="0.01" name="kulit" id="kulit" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-4 col-md-1">
                                <label class="form-label text-danger">BATU</label>
                                <input type="number" step="0.01" name="batu" id="batu" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-4 col-md-1">
                                <label class="form-label text-danger">GLNDG</label>
                                <input type="number" step="0.01" name="gelondong" id="gelondong" class="form-control form-control-sm" value="0.00" onfocus="this.select()">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Ket</label>
                                <select name="keterangan" id="keterangan" class="form-select form-select-sm">
                                    <option value="Perjam">Perjam</option>
                                    <option value="Setting">Setting</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" name="aksi" value="simpan" class="btn btn-primary fw-bold px-4">Simpan</button>
                            <button type="submit" name="aksi" value="cetak" class="btn btn-success fw-bold px-4 ms-2"><i class="fas fa-print me-1"></i> Simpan & Cetak Label</button>
                            <button type="button" id="btnBatal" class="btn btn-light border ms-2 d-none" onclick="resetForm()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-3">

            <div class="col-md-6">
                <div class="card card-custom">
                    <div class="card-header bg-tracemax py-2 text-uppercase" style="font-size: 0.85rem;">
                        1. Riwayat Data: Tracemax 11%
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>JAM</th>
                                    <th>MSN</th>
                                    <th>AP</th>
                                    <th>CR</th>
                                    <th>HT</th>
                                    <th>BS</th>
                                    <th>PC</th>
                                    <th>KL</th>
                                    <th>BT</th>
                                    <th>GL</th>
                                    <th class="table-success text-dark">TTL</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hasTmax = false;
                                foreach ($dataTrase as $row):
                                    if (strpos(strtoupper($row['jenis_kopi']), 'TRACEMAX') !== false):
                                        $hasTmax = true;
                                ?>
                                        <tr>
                                            <td class="text-muted fw-bold"><?= substr($row['pukul'], 0, 5) ?></td>
                                            <td><span class="badge bg-dark">gs-<?= esc($row['mesin_gs']) ?></span></td>
                                            <td><?= number_format($row['ap'], 2) ?></td>
                                            <td><?= number_format($row['cera'], 2) ?></td>
                                            <td><?= number_format($row['hitam'], 2) ?></td>
                                            <td><?= number_format($row['busuk'], 2) ?></td>
                                            <td><?= number_format($row['pecah'], 2) ?></td>
                                            <td><?= number_format($row['kulit'], 2) ?></td>
                                            <td><?= number_format($row['batu'], 2) ?></td>
                                            <td><?= number_format($row['gelondong'], 2) ?></td>
                                            <td class="fw-bold text-success bg-light"><?= number_format($row['total'], 2) ?></td>
                                            <td>
                                                <a href="#" class="btn-action-edit" onclick="editData(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</a>
                                                <a href="/trase/cetak-ulang/<?= $row['id'] ?>" class="btn-action-cetak">Cetak</a>
                                                <a href="/trase/hapus/<?= $row['id'] ?>" class="btn-action-hapus" onclick="return confirm('Hapus data?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endif;
                                endforeach;
                                if (!$hasTmax): ?>
                                    <tr>
                                        <td colspan="12" class="text-muted py-3">Belum ada history Tracemax.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-custom">
                    <div class="card-header bg-ker py-2 text-uppercase" style="font-size: 0.85rem;">
                        2. Riwayat Data: Kopi Enteng Ringan
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>JAM</th>
                                    <th>MSN</th>
                                    <th>AP</th>
                                    <th>CR</th>
                                    <th>HT</th>
                                    <th>BS</th>
                                    <th>PC</th>
                                    <th>KL</th>
                                    <th>BT</th>
                                    <th>GL</th>
                                    <th class="table-success text-dark">TTL</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hasKer = false;
                                foreach ($dataTrase as $row):
                                    if (strpos(strtoupper($row['jenis_kopi']), 'TRACEMAX') === false):
                                        $hasKer = true;
                                ?>
                                        <tr>
                                            <td class="text-muted fw-bold"><?= substr($row['pukul'], 0, 5) ?></td>
                                            <td><span class="badge bg-dark">gs-<?= esc($row['mesin_gs']) ?></span></td>
                                            <td><?= number_format($row['ap'], 2) ?></td>
                                            <td><?= number_format($row['cera'], 2) ?></td>
                                            <td><?= number_format($row['hitam'], 2) ?></td>
                                            <td><?= number_format($row['busuk'], 2) ?></td>
                                            <td><?= number_format($row['pecah'], 2) ?></td>
                                            <td><?= number_format($row['kulit'], 2) ?></td>
                                            <td><?= number_format($row['batu'], 2) ?></td>
                                            <td><?= number_format($row['gelondong'], 2) ?></td>
                                            <td class="fw-bold text-success bg-light"><?= number_format($row['total'], 2) ?></td>
                                            <td>
                                                <a href="#" class="btn-action-edit" onclick="editData(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</a>
                                                <a href="/trase/cetak-ulang/<?= $row['id'] ?>" class="btn-action-cetak">Cetak</a>
                                                <a href="/trase/hapus/<?= $row['id'] ?>" class="btn-action-hapus" onclick="return confirm('Hapus data?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endif;
                                endforeach;
                                if (!$hasKer): ?>
                                    <tr>
                                        <td colspan="12" class="text-muted py-3">Belum ada history Kopi Enteng.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalBukaBatch" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/batch/buka" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Mulai Kerja Batch Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nama / Nomor Batch</label>
                        <input type="text" name="nama_batch" class="form-control" placeholder="Contoh: 3" required>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-success">Buka Jaringan</button></div>
                </form>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('cetak_data')):
        $label = session()->getFlashdata('cetak_data');
    ?>
        <div id="print-area-label" class="d-none">
            <div style="width: 54mm; font-family: 'Courier New', Courier, monospace; font-size: 11px; padding: 2px; color: #000; line-height: 1.2;">
                <div style="text-align: center; font-weight: bold; border-bottom: 1px dashed #000; padding-bottom: 4px; margin-bottom: 6px; font-size: 11px;">
                    PT TORABIKA EKA SEMESTA<br>QC TRASE IDENTITAS SAMPEL
                </div>
                <table style="width: 100%; font-size: 11px; margin-bottom: 4px;">
                    <tr>
                        <td style="width: 35%;"><b>BATCH</b></td>
                        <td>: <?= esc($label['batch_name']) ?></td>
                    </tr>
                    <tr>
                        <td><b>PRODUK</b></td>
                        <td>: <?= esc($label['jenis_kopi']) ?></td>
                    </tr>
                    <tr>
                        <td><b>TANGGAL</b></td>
                        <td>: <?= $label['tanggal'] ?></td>
                    </tr>
                    <tr>
                        <td><b>JAM</b></td>
                        <td>: <?= $label['jam'] ?></td>
                    </tr>
                    <tr>
                        <td><b>MESIN/GS</b></td>
                        <td>: <?= esc($label['mesin_gs']) ?></td>
                    </tr>
                </table>

                <div style="border-top: 1px dashed #000; margin-top: 4px; padding-top: 4px; font-weight: bold;">RINCIAN ANALISA:</div>
                <table style="width: 100%; font-size: 11px;">
                    <tr>
                        <td>KADAR AIR AP</td>
                        <td style="text-align: right;"><?= number_format($label['ap'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>KADAR AIR CERA</td>
                        <td style="text-align: right;"><?= number_format($label['cera'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>BIJI HITAM</td>
                        <td style="text-align: right;"><?= number_format($label['hitam'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>BIJI BUSUK</td>
                        <td style="text-align: right;"><?= number_format($label['busuk'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>BIJI PECAH</td>
                        <td style="text-align: right;"><?= number_format($label['pecah'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>KULIT KOPI</td>
                        <td style="text-align: right;"><?= number_format($label['kulit'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>BATU/SAMPAH</td>
                        <td style="text-align: right;"><?= number_format($label['batu'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>GELONDONG</td>
                        <td style="text-align: right;"><?= number_format($label['gelondong'], 2) ?></td>
                    </tr>
                    <tr style="font-size: 12px; font-weight: bold; border-top: 1px solid #000;">
                        <td style="padding-top: 4px;">TOTAL TRASH</td>
                        <td style="text-align: right; padding-top: 4px;"><?= number_format($label['total'], 2) ?>%</td>
                    </tr>
                </table>
                <div style="text-align: center; border-top: 1px dashed #000; margin-top: 6px; padding-top: 4px; font-size: 8px; font-style: italic;">
                    * LAB & PRODUCTION ATTACHMENT *
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    var printContents = document.getElementById('print-area-label').innerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;

                    setTimeout(() => {
                        window.location.replace("/input");
                    }, 300);
                }, 500);
            });
        </script>
        <style>
            @media print {
                body {
                    background: white;
                }

                #print-area-label {
                    display: block !important;
                }
            }
        </style>
    <?php endif; ?>

    <script>
        function editData(data) {
            document.getElementById('trase_id').value = data.id;
            document.getElementById('jenis_kopi').value = data.jenis_kopi;
            document.getElementById('mesin_gs').value = data.mesin_gs;
            document.getElementById('ap').value = data.ap;
            document.getElementById('cera').value = data.cera;
            document.getElementById('hitam').value = data.hitam;
            document.getElementById('busuk').value = data.busuk;
            document.getElementById('pecah').value = data.pecah;
            document.getElementById('kulit').value = data.kulit;
            document.getElementById('batu').value = data.batu;
            document.getElementById('gelondong').value = data.gelondong;
            document.getElementById('keterangan').value = data.keterangan;
            document.getElementById('btnBatal').classList.remove('d-none');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function resetForm() {
            document.getElementById('formTrase').reset();
            document.getElementById('trase_id').value = '';
            document.getElementById('btnBatal').classList.add('d-none');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>