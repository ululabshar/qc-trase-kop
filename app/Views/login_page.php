<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - QC Trace Kopi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; height: 100vh; }
    </style>
</head>
<body class="d-flex align-items-center">
<div class="card mx-auto shadow-sm" style="width: 400px;">
    <div class="card-body p-4">
        <h4 class="card-title text-center mb-4 fw-bold text-uppercase">Sistem Trase Kopi</h4>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <form action="/login/proses" method="POST">
            <?= csrf_field() ?> <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Masuk Sistem</button>
        </form>
    </div>
</div>
</body>
</html>