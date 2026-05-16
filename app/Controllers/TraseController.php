<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\TraseModel;

class TraseController extends BaseController
{
    protected $batchModel;
    protected $traseModel;
    protected $session;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->traseModel = new TraseModel();
        $this->session = session();
    }

    // 1. Halaman Login Sederhana
    public function login()
    {
        return view('login_page');
    }

    public function prosesLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Koneksi langsung ke database untuk cek user
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        // Jika user ditemukan dan password-nya cocok (di-verify dari hash)
        if ($user && password_verify($password, $user['password'])) {
            $this->session->set([
                'isLoggedIn' => true,
                'role'       => $user['role']
            ]);

            // Redirect sesuai role masing-masing
            if ($user['role'] == 'operator') {
                return redirect()->to('/input');
            } else {
                return redirect()->to('/monitor');
            }
        }

        return redirect()->back()->with('error', 'Username atau Password Salah!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    // 2. Halaman Input Data (Khusus Akun Operator)
    public function inputHalaman()
    {
        if ($this->session->get('role') != 'operator') {
            return redirect()->to('/login')->with('error', 'Akses ditolak!');
        }

        $activeBatch = $this->batchModel->where('status', 'Buka')->orderBy('id', 'DESC')->first();
        $dataTrase = [];
        if ($activeBatch) {
            $dataTrase = $this->traseModel->where('batch_id', $activeBatch['id'])->orderBy('pukul', 'DESC')->findAll();
        }

        return view('operator_input', [
            'activeBatch' => $activeBatch,
            'dataTrase'   => $dataTrase
        ]);
    }

    public function bukaBatch()
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        $this->batchModel->save([
            'nama_batch'   => $this->request->getPost('nama_batch'),
            'jenis_kopi'   => $this->request->getPost('jenis_kopi'), // Ini akan menyimpan 'KOPI ENTENG RINGAN', 'KER', atau 'TRACEMAX 11%'
            'tanggal_buka' => date('Y-m-d'),
            'status'       => 'Buka'
        ]);
        return redirect()->to('/input');
    }

    public function tutupBatch($id)
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        $this->batchModel->update($id, ['status' => 'Tutup']);
        return redirect()->to('/input');
    }

    // --- JALUR SIMPAN (BISA UPDATE ATAU INSERT BARU) ---
    public function simpanTrase()
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        $id = $this->request->getPost('id'); // Ambil ID hidden (jika ada)

        $ap = (float)$this->request->getPost('ap');
        $cera = (float)$this->request->getPost('cera');
        $hitam = (float)$this->request->getPost('hitam');
        $busuk = (float)$this->request->getPost('busuk');
        $pecah = (float)$this->request->getPost('pecah');
        $kulit = (float)$this->request->getPost('kulit');
        $batu = (float)$this->request->getPost('batu');
        $gelondong = (float)$this->request->getPost('gelondong');

        // Perhitungan Total sesuai rumus kotoran Anda
        $total = $hitam + $busuk + $pecah + $kulit + $batu + $gelondong;

        $dataArray = [
            'batch_id'   => $this->request->getPost('batch_id'),
            'jenis_kopi' => $this->request->getPost('jenis_kopi'),
            'tanggal'    => date('Y-m-d'),
            'mesin_gs'   => $this->request->getPost('mesin_gs'),
            'pukul'      => date('H:i:s'),
            'ap' => $ap,
            'cera' => $cera,
            'hitam' => $hitam,
            'busuk' => $busuk,
            'pecah' => $pecah,
            'kulit' => $kulit,
            'batu' => $batu,
            'gelondong' => $gelondong,
            'total'      => $total,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        // LOGIKA UTAMA: Jika ID ada raises, lakukan edit update data lama
        if (!empty($id)) {
            $dataArray['id'] = $id;
        }

        $this->traseModel->save($dataArray); // Fungsi save() otomatis mendeteksi insert/update berdasarkan keberadaan key ID

        return redirect()->to('/input');
    }

    // --- FUNGSI HAPUS DATA ---
    public function hapusTrase($id)
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        $this->traseModel->delete($id);
        return redirect()->to('/input');
    }

    // 3. Halaman Layar Monitor (Bisa diakses Akun Monitor & Operator)
    public function monitorHalaman()
    {
        if (!$this->session->get('isLoggedIn')) return redirect()->to('/login');
        return view('dashboard_monitor');
    }

    // API JSON untuk auto-refresh layar monitor
    public function getLatestData()
    {
        $activeBatch = $this->batchModel->where('status', 'Buka')->orderBy('id', 'DESC')->first();
        if (!$activeBatch) {
            return $this->response->setJSON(['nama_batch' => 'TIDAK ADA BATCH AKTIF', 'data' => []]);
        }
        $dataTrase = $this->traseModel->where('batch_id', $activeBatch['id'])->orderBy('pukul', 'DESC')->findAll();
        return $this->response->setJSON(['nama_batch' => $activeBatch['nama_batch'], 'data' => $dataTrase]);
    }
}
