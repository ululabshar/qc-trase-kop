<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. TORABIKA EKA SEMESTA - QC LAB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI',Roboto,Helvetica,Arial,sans-serif; overflow-x: hidden; }
        .main-sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; width: 240px; background-color: #22252a; }
        .sidebar-brand { display: block; padding: 18px; font-size: 1.1rem; color: #fff; text-decoration: none; border-bottom: 1px solid #343840; font-weight: bold; text-align: center; }
        .sidebar-menu { padding: 15px 0; list-style: none; }
        .sidebar-menu a { display: block; padding: 10px 20px; color: #c2c7d0; text-decoration: none; font-size: 0.9rem; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #343a40; color: #fff; border-left: 4px solid #0d6efd; }
        .content-wrapper { margin-left: 240px; padding: 20px; }
        .card-custom { border-radius: 6px; border: 1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.02); background: #fff; margin-bottom: 20px; }
        
        .form-label { font-weight: bold; font-size: 0.8rem; color: #000000; margin-bottom: 3px; }
        .form-control-sm { font-size: 0.85rem; padding: 4px 6px; color: #000000; border: 1px solid #888; font-weight: 600; }
        th { font-size: 10px; background-color: #00c0ef !important; color: #000000 !important; text-align: center; vertical-align: middle; padding: 6px 3px !important; font-weight: 700; border: 1px solid #aaa !important; }
        td { font-size: 11px; color: #000000 !important; text-align: center; vertical-align: middle; padding: 5px 3px !important; font-weight: 600; border: 1px solid #ccc !important; }
    </style>
</head>
<body>

<div class="main-sidebar">
    <div class="sidebar-brand text-warning"><i class="fas fa-coffee me-2"></i>PT. TORABIKA</div>
    <ul class="sidebar-menu">
        <li><a href="/admin" class="active"><i class="fas fa-table me-2"></i> Rekap Lot Kantor</a></li>
        <li><a href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Log Out</a></li>
    </ul>
</div>

<div class="content-wrapper">
    <div class="mb-4 border-bottom pb-2">
        <h4 class="m-0 fw-bold text-dark">LAPORAN ANALISA TRACE HASIL PRODUKSI - REGULER BATCH TRACEMAX</h4>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success py-2 small fw-bold"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card card-custom p-3 mb-3">
        <span class="text-muted small d-block">Batch Aktif Saat Ini:</span>
        <h5 class="fw-bold text-success m-0"><?= (isset($activeBatch) && !empty($activeBatch)) ? esc($activeBatch['nama_batch']) : 'SHIFT DEFAULT' ?></h5>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-dark text-white fw-bold py-2" style="font-size:0.85rem;">Form Entri Laboratorium</div>
        <div class="card-body p-3">
            <form action="/lot/simpan" method="POST" id="formLotAdmin">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="lot_id">
                <input type="hidden" name="batch_id" value="<?= (isset($activeBatch) && !empty($activeBatch)) ? $activeBatch['id'] : '' ?>">

                <div class="row g-2 mb-2">
                    <div class="col-md-2">
                        <label class="form-label">QAN</label>
                        <input type="text" name="qan" id="qan" class="form-control form-control-sm" placeholder="4139361439-CMP" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Nama LOT / Pallet</label>
                        <input type="text" name="nama_lot" id="nama_lot" class="form-control form-control-sm" placeholder="PALLET GD D1" required>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label">Colly</label>
                        <input type="number" name="colly" id="colly" class="form-control form-control-sm" value="0" required>
                    </div>
                    <div class="col-6 col-md-1.5">
                        <label class="form-label">Jumlah (Kg)</label>
                        <input type="number" step="0.01" name="kg" id="kg" class="form-control form-control-sm" value="0.00" required>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-primary">AP (%)</label>
                        <input type="number" step="0.01" name="ap" id="ap" class="form-control form-control-sm" value="0.00" required>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-primary">CERA (%)</label>
                        <input type="number" step="0.01" name="cera" id="cera" class="form-control form-control-sm" value="0.00" required>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-danger">HITAM</label>
                        <input type="number" step="0.01" name="hitam" id="hitam" class="form-control form-control-sm" value="0.00">
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-danger">BUSUK</label>
                        <input type="number" step="0.01" name="busuk" id="busuk" class="form-control form-control-sm" value="0.00">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6 col-md-1">
                        <label class="form-label text-danger">PECAH</label>
                        <input type="number" step="0.01" name="pecah" id="pecah" class="form-control form-control-sm" value="0.00">
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-danger">KULIT</label>
                        <input type="number" step="0.01" name="kulit" id="kulit" class="form-control form-control-sm" value="0.00">
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-danger">BATU</label>
                        <input type="number" step="0.01" name="batu" id="batu" class="form-control form-control-sm" value="0.00">
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-danger">GELONDONG</label>
                        <input type="number" step="0.01" name="gelondong" id="gelondong" class="form-control form-control-sm" value="0.00">
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-success">ROBINSON</label>
                        <input type="number" step="0.01" name="robinson" id="robinson" class="form-control form-control-sm" value="0.00">
                    </div>
                    <div class="col-6 col-md-1.5">
                        <label class="form-label text-success">ABU AYAK (%)</label>
                        <input type="number" step="0.01" name="abu_ayak" id="abu_ayak" class="form-control form-control-sm" value="0.00">
                    </div>
                    <div class="col-6 col-md-1.5">
                        <label class="form-label text-secondary">SNI</label>
                        <input type="number" step="0.01" name="sni" id="sni" class="form-control form-control-sm" value="0.00">
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-sm btn-primary fw-bold w-100">Simpan Record</button>
                        <button type="button" id="btnBatal" class="btn btn-sm btn-light border d-none" onclick="resetForm()">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th rowspan="2">TANGGAL ANALISA</th>
                        <th rowspan="2">QAN</th>
                        <th rowspan="2">NAMA LOT</th>
                        <th colspan="2">JUMLAH</th>
                        <th colspan="2">KADAR AIR</th>
                        <th colspan="6">HASIL TRASE</th>
                        <th rowspan="2" style="background-color: #f8d7da !important;">TOTAL</th>
                        <th colspan="2" style="background-color: #cff4fc !important;">PROP MC</th>
                        <th rowspan="2" style="background-color: #ffecb5 !important;">PROPORSIONAL TRACE</th>
                        <th rowspan="2">ROBINSON</th>
                        <th rowspan="2">ABU AYAK (%)</th>
                        <th rowspan="2">SNI</th>
                        <th rowspan="2">PROP SNI</th>
                        <th rowspan="2" style="background-color: #666 !important; color:#fff !important;">AKSI</th>
                    </tr>
                    <tr>
                        <th>COLLY</th><th>Kg</th>
                        <th>AP</th><th>CERA</th>
                        <th>HITAM</th><th>BUSUK</th><th>PECAH</th><th>KULIT</th><th>BATU</th><th>GELONDONG</th>
                        <th style="background-color: #cff4fc !important;">AP</th><th style="background-color: #cff4fc !important;">CERA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotalColly = 0; $grandTotalKg = 0; 
                    $grandTotalPropAp = 0; $grandTotalPropCera = 0; $grandTotalPropTrace = 0; $grandTotalPropSni = 0;
                    $sumAp = 0; $sumCera = 0; $sumHitam = 0; $sumBusuk = 0; $sumPecah = 0;
                    $sumKulit = 0; $sumBatu = 0; $sumGelondong = 0; $sumTotal = 0; 
                    $sumRobinson = 0; $sumAbu = 0; $sumSni = 0;

                    $jumlahBaris = !empty($dataLot) ? count($dataLot) : 0;

                    if($jumlahBaris > 0): 
                        foreach($dataLot as $row): 
                            $grandTotalColly     += (int)$row['colly'];
                            $grandTotalKg        += (float)$row['kg'];
                            $grandTotalPropAp    += (float)$row['prop_ap'];
                            $grandTotalPropCera  += (float)$row['prop_cera'];
                            $grandTotalPropTrace += (float)$row['prop_trace'];
                            $grandTotalPropSni   += (float)$row['prop_sni'];

                            $sumAp        += (float)$row['ap'];
                            $sumCera      += (float)$row['cera'];
                            $sumHitam     += (float)$row['hitam'];
                            $sumBusuk     += (float)$row['busuk'];
                            $sumPecah     += (float)$row['pecah'];
                            $sumKulit     += (float)$row['kulit'];
                            $sumBatu      += (float)$row['batu'];
                            $sumGelondong += (float)$row['gelondong'];
                            $sumTotal     += (float)$row['total'];
                            $sumRobinson  += (float)$row['robinson'];
                            $sumAbu       += (float)$row['abu_ayak'];
                            $sumSni       += (float)$row['sni'];
                    ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td><small class="text-muted fw-bold"><?= esc($row['qan']) ?></small></td>
                        <td class="bg-light fw-bold text-primary"><?= esc($row['nama_lot']) ?></td>
                        <td><?= number_format($row['colly']) ?></td>
                        <td><?= number_format($row['kg'], 1) ?></td>
                        <td><?= number_format($row['ap'], 2) ?></td>
                        <td><?= number_format($row['cera'], 2) ?></td>
                        <td><?= number_format($row['hitam'], 2) ?></td>
                        <td><?= number_format($row['busuk'], 2) ?></td>
                        <td><?= number_format($row['pecah'], 2) ?></td>
                        <td><?= number_format($row['kulit'], 2) ?></td>
                        <td><?= number_format($row['batu'], 2) ?></td>
                        <td><?= number_format($row['gelondong'], 2) ?></td>
                        <td class="fw-bold bg-danger-subtle text-danger"><?= number_format($row['total'], 2) ?></td>
                        
                        <td class="fw-bold bg-info-subtle"><?= number_format($row['prop_ap'], 2) ?></td>
                        <td class="fw-bold bg-info-subtle"><?= number_format($row['prop_cera'], 2) ?></td>
                        <td class="fw-bold bg-warning-subtle text-dark"><?= number_format($row['prop_trace'], 2) ?></td>
                        
                        <td><?= number_format($row['robinson'], 2) ?></td>
                        <td><?= number_format($row['abu_ayak'], 2) ?>%</td>
                        <td><?= number_format($row['sni'], 2) ?></td>
                        <td><?= number_format($row['prop_sni'], 2) ?></td>
                        <td>
                            <a href="#" class="btn btn-xs text-primary p-0 fw-bold me-1" onclick="editData(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</a>
                            <a href="/lot/hapus/<?= $row['id'] ?>" class="btn btn-xs text-danger p-0 fw-bold" onclick="return confirm('Hapus baris lot ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 

                        $avgAp   = $sumAp / $jumlahBaris; $avgCera = $sumCera / $jumlahBaris;
                        $avgHtm  = $sumHitam / $jumlahBaris; $avgBsk  = $sumBusuk / $jumlahBaris;
                        $avgPch  = $sumPecah / $jumlahBaris; $avgKlt  = $sumKulit / $jumlahBaris;
                        $avgBt   = $sumBatu / $jumlahBaris; $avgGld  = $sumGelondong / $jumlahBaris;
                        $avgTtl  = $sumTotal / $jumlahBaris; $avgRob  = $sumRobinson / $jumlahBaris;
                        $avgAbu  = $sumAbu / $jumlahBaris; $avgSni  = $sumSni / $jumlahBaris;
                    ?>
                    <tr class="fw-bold" style="background-color: #ddd; border-top: 2px solid #000; border-bottom: 2px solid #000;">
                        <td colspan="3" class="text-end">AVERAGE ALL TAPELAN:</td>
                        <td><?= number_format($grandTotalColly) ?></td>
                        <td><?= number_format($grandTotalKg, 1) ?></td>
                        <td><?= number_format($avgAp, 2) ?></td>
                        <td><?= number_format($avgCera, 2) ?></td>
                        <td><?= number_format($avgHtm, 2) ?></td>
                        <td><?= number_format($avgBsk, 2) ?></td>
                        <td><?= number_format($avgPch, 2) ?></td>
                        <td><?= number_format($avgKlt, 2) ?></td>
                        <td><?= number_format($avgBt, 2) ?></td>
                        <td><?= number_format($avgGld, 2) ?></td>
                        <td class="text-danger bg-danger-subtle"><?= number_format($avgTtl, 2) ?></td>
                        
                        <td class="bg-info-subtle"><?= number_format($grandTotalPropAp, 2) ?></td>
                        <td class="bg-info-subtle"><?= number_format($grandTotalPropCera, 2) ?></td>
                        <td class="bg-warning-subtle"><?= number_format($grandTotalPropTrace, 2) ?></td>
                        
                        <td><?= number_format($avgRob, 2) ?></td>
                        <td><?= number_format($avgAbu, 2) ?>%</td>
                        <td><?= number_format($avgSni, 2) ?></td>
                        <td><?= number_format($grandTotalPropSni, 2) ?></td>
                        <td></td>
                    </tr>
                    <?php else: ?>
                        <tr><td colspan="22" class="text-muted py-3">Belum ada rekapitulasi data LOT admin kantor.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function editData(data) {
        document.getElementById('lot_id').value = data.id;
        document.getElementById('qan').value = data.qan ? data.qan : '';
        document.getElementById('nama_lot').value = data.nama_lot;
        document.getElementById('colly').value = data.colly;
        document.getElementById('kg').value = data.kg;
        document.getElementById('ap').value = data.ap;
        document.getElementById('cera').value = data.cera;
        document.getElementById('hitam').value = data.hitam;
        document.getElementById('busuk').value = data.busuk;
        document.getElementById('pecah').value = data.pecah;
        document.getElementById('kulit').value = data.kulit;
        document.getElementById('batu').value = data.batu;
        document.getElementById('gelondong').value = data.gelondong;
        document.getElementById('robinson').value = data.robinson;
        document.getElementById('abu_ayak').value = data.abu_ayak;
        document.getElementById('sni').value = data.sni;
        
        document.getElementById('btnBatal').classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function resetForm() {
        document.getElementById('formLotAdmin').reset();
        document.getElementById('lot_id').value = '';
        document.getElementById('btnBatal').classList.add('d-none');
    }
</script>
</body>
</html>