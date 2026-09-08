<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'Safepedia') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .extra-small {
            font-size: 0.78rem;
        }
    </style>
</head>

<body class="bg-light min-vh-100">

    @php
        extract(\App\Helpers\DashboardHelper::getDashboardData());
    @endphp

    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3 sticky-top">
        <div class="container-fluid max-w-7xl px-3 px-lg-4">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between w-100 gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h2 class="h4 font-weight-bold text-dark mb-0">
                            Dashboard {{ ucfirst(str_replace('_', ' ', auth()->user()->role->name ?? 'User')) }}
                        </h2>
                        <span
                            class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill small">
                            Active
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-1">
                        Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> 👋
                    </p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @if ($roleName === 'requestor')
                        <a href="{{ route('warehouse-requests.create') }}"
                            class="btn btn-primary btn-sm font-weight-bold px-3 py-2 rounded-2">
                            <i class="bi bi-plus-lg me-1"></i> Buat Pengajuan Baru
                        </a>
                    @elseif ($roleName === 'admin')
                        <a href="{{ route('usermanagement.index') }}"
                            class="btn btn-primary btn-sm font-weight-bold px-3 py-2 rounded-2">
                            <i class="bi bi-people-fill me-1"></i> User Management
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="d-inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                        @csrf
                        <button type="submit"
                            class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 rounded-2">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container-fluid max-w-7xl px-3 px-lg-4">

            <div class="card border-0 bg-white rounded-3 mb-4 p-4 shadow-sm">
                <div class="card-body p-0">
                    <span class="badge bg-light text-primary border px-2.5 py-1 rounded-1 mb-2 font-weight-semibold">
                        <i class="bi bi-shield-check me-1"></i> Portal Otorisasi Gudang
                    </span>

                    @if ($roleName === 'admin')
                        <h4 class="font-weight-bold text-dark mb-1">
                            Panel Administrator System
                        </h4>
                        <p class="text-muted small mb-0">
                            Kelola akun pengguna, alokasi role/hak akses sistem, dan pantau pengguna terdaftar dalam
                            aplikasi.
                        </p>
                    @elseif ($roleName === 'requestor')
                        <h4 class="font-weight-bold text-dark mb-1">
                            Monitoring & Pengajuan Gudang Distribusi
                        </h4>
                        <p class="text-muted small mb-0">
                            Kelola berkas persyaratan, tentukan titik lokasi presisi pada peta, dan pantau perkembangan
                            approval 6 tingkat dari Supervisor hingga Direktur Keuangan.
                        </p>
                    @else
                        <h4 class="font-weight-bold text-dark mb-1">
                            Portal Review & Keputusan Otorisasi
                        </h4>
                        <p class="text-muted small mb-0">
                            Tinjau kelengkapan dokumen, spesifikasi lahan, koordinat peta, dan anggaran biaya untuk
                            memberikan keputusan approval/reject sesuai kewenangan Anda.
                        </p>
                    @endif
                </div>
            </div>

            @if ($roleName === 'admin')
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div
                            class="card border-0 bg-white rounded-3 p-3 h-100 shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-uppercase text-muted font-weight-bold small">Total Pengguna</span>
                                    <div class="bg-primary-subtle text-primary p-2 rounded">
                                        <i class="bi bi-people fs-5"></i>
                                    </div>
                                </div>
                                <h2 class="font-weight-bold text-dark mb-1">{{ $totalUsers }}</h2>
                                <span class="text-muted extra-small d-block mb-3">Pengguna terdaftar di dalam
                                    sistem</span>
                            </div>
                            <div class="pt-2 border-top">
                                <a href="{{ route('usermanagement.index') }}"
                                    class="btn btn-primary btn-sm w-100 font-weight-semibold">
                                    <i class="bi bi-gear-fill me-1"></i> Buka User Management
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                    <div
                        class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-person-lines-fill me-1 text-primary"></i> Pengguna Terbaru
                        </h6>
                        <a href="{{ route('usermanagement.index') }}"
                            class="btn btn-outline-primary btn-sm rounded-2 font-weight-semibold">
                            Kelola Semua User <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3 extra-small text-uppercase text-muted fw-bold">Nama</th>
                                    <th class="px-4 py-3 extra-small text-uppercase text-muted fw-bold">Email</th>
                                    <th class="px-4 py-3 extra-small text-uppercase text-muted fw-bold">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $recentUser)
                                    <tr>
                                        <td class="px-4 py-3 fw-semibold text-dark small">{{ $recentUser->name }}</td>
                                        <td class="px-4 py-3 text-muted small">{{ $recentUser->email }}</td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark border px-2 py-1 extra-small">
                                                {{ ucfirst(str_replace('_', ' ', $recentUser->role->name ?? 'No Role')) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4 small">Belum ada pengguna
                                            terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif ($roleName === 'requestor')
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 bg-white rounded-3 p-3 h-100 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-uppercase text-muted small font-weight-semibold">Total Form</span>
                                <div class="bg-light p-2 rounded text-secondary">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-0">{{ $totalRequests }}</h3>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="card border-0 bg-white rounded-3 p-3 h-100 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-uppercase text-warning small font-weight-semibold">Diproses</span>
                                <div class="bg-warning-subtle text-warning p-2 rounded">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-warning mb-0">{{ $submittedRequests }}</h3>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="card border-0 bg-white rounded-3 p-3 h-100 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-uppercase text-success small font-weight-semibold">Disetujui</span>
                                <div class="bg-success-subtle text-success p-2 rounded">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-success mb-0">{{ $approvedRequests }}</h3>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="card border-0 bg-white rounded-3 p-3 h-100 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-uppercase text-danger small font-weight-semibold">Ditolak</span>
                                <div class="bg-danger-subtle text-danger p-2 rounded">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-danger mb-0">{{ $rejectedRequests }}</h3>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div
                            class="card border border-warning-subtle bg-warning-subtle rounded-3 p-3 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-uppercase text-warning-emphasis font-weight-bold small">Butuh
                                        Action Anda</span>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                                <h2 class="font-weight-bold text-dark mb-1">{{ $pendingMyReview }}</h2>
                                <span class="text-muted extra-small d-block mb-3">
                                    Pengajuan yang menunggu approval Level {{ $currentApprovalLevel ?? '-' }}
                                </span>
                            </div>
                            <div class="pt-2 border-top border-warning-subtle">
                                <a href="{{ route('approvals.index') }}"
                                    class="btn btn-warning btn-sm w-100 font-weight-semibold">
                                    <i class="bi bi-card-checklist me-1"></i> Lihat Daftar Review
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div
                            class="card border-0 bg-white rounded-3 p-3 h-100 shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-uppercase text-success font-weight-semibold small">Total
                                        Approved</span>
                                    <div class="bg-success-subtle text-success p-1.5 rounded">
                                        <i class="bi bi-check-circle fs-6"></i>
                                    </div>
                                </div>
                                <h2 class="font-weight-bold text-success mb-1">{{ $approvedRequests }}</h2>
                                <span class="text-muted extra-small d-block mb-3">Disetujui penuh hingga level
                                    Direktur</span>
                            </div>
                            <div class="pt-2 border-top">
                                <a href="{{ route('approvals.index', ['status' => 'approved']) }}"
                                    class="btn btn-outline-success btn-sm w-100 font-weight-semibold">
                                    <i class="bi bi-eye me-1"></i> Detail Disetujui
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div
                            class="card border-0 bg-white rounded-3 p-3 h-100 shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-uppercase text-danger font-weight-semibold small">Total
                                        Rejected</span>
                                    <div class="bg-danger-subtle text-danger p-1.5 rounded">
                                        <i class="bi bi-x-circle fs-6"></i>
                                    </div>
                                </div>
                                <h2 class="font-weight-bold text-danger mb-1">{{ $rejectedRequests }}</h2>
                                <span class="text-muted extra-small d-block mb-3">Pengajuan yang ditolak</span>
                            </div>
                            <div class="pt-2 border-top">
                                <a href="{{ route('approvals.index', ['status' => 'rejected']) }}"
                                    class="btn btn-outline-danger btn-sm w-100 font-weight-semibold">
                                    <i class="bi bi-exclamation-circle me-1"></i> Detail Izin Ditolak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div
                        class="card border-0 bg-white rounded-3 p-4 h-100 d-flex flex-column justify-content-between shadow-sm">
                        <div>
                            <div class="bg-light text-dark p-2 rounded d-inline-block mb-3">
                                <i class="bi bi-list-task fs-5"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-1">
                                @if ($roleName === 'admin')
                                    Kelola Pengguna Sistem
                                @elseif ($roleName === 'requestor')
                                    Daftar Pengajuan Saya
                                @else
                                    Butuh Action Anda
                                @endif
                            </h5>
                            <p class="text-muted small mb-0">
                                @if ($roleName === 'admin')
                                    Tambah pengguna baru, atur ulang password, dan perbarui hak akses role pengguna.
                                @elseif ($roleName === 'requestor')
                                    Kelola draft, periksa alasan penolakan, atau pantau tahap approval gudang Anda saat
                                    ini.
                                @else
                                    Tinjau berkas pendukung, koordinat lokasi gudang, dan lakukan tindakan Approve atau
                                    Reject.
                                @endif
                            </p>
                        </div>
                        <div class="pt-3 border-top mt-4">
                            @if ($roleName === 'admin')
                                <a href="{{ route('usermanagement.index') }}"
                                    class="btn btn-outline-primary btn-sm font-weight-semibold">
                                    Buka User Management <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            @else
                                <a href="{{ $roleName === 'requestor' ? route('warehouse-requests.index') : route('approvals.index') }}"
                                    class="btn btn-outline-dark btn-sm font-weight-semibold">
                                    {{ $roleName === 'requestor' ? 'Buka Daftar Pengajuan' : 'Buka Menu Review' }} <i
                                        class="bi bi-arrow-right ms-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div
                        class="card border-0 bg-white rounded-3 p-4 h-100 d-flex flex-column justify-content-between shadow-sm">
                        <div>
                            <div class="bg-success-subtle text-success p-2 rounded d-inline-block mb-3">
                                <i class="bi bi-shield-lock fs-5"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-1">Keamanan Akun & 2FA</h5>
                            <p class="text-muted small mb-0">
                                Verifikasi 2FA via Google Authenticator wajib aktif untuk memastikan autentikasi
                                keamanan saat pengajuan atau persetujuan dokumen.
                            </p>
                        </div>
                        <div class="pt-3 border-top mt-4">
                            <div
                                class="d-inline-flex align-items-center gap-2 bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-2 small fw-semibold">
                                <i class="bi bi-patch-check-fill fs-6"></i>
                                <span>Akun Anda Sudah Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
