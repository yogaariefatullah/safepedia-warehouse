<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pengajuan Gudang Distribusi - {{ config('app.name', 'Safepedia') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 & Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #2b2f38;
            background-color: #f8fafc;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        }

        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.08);
        }

        .step-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .badge-step {
            font-size: 0.75rem;
            padding: 0.35em 0.8em;
            border-radius: 50rem;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex items-center gap-2 font-bold fs-5" href="#">
                <i class="bi bi-box-seam-fill text-warning fs-4"></i>
                <span>SAFE<span class="text-warning">WAREHOUSE</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 small fw-medium">
                    <li class="nav-item"><a class="nav-link active" href="#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur Utama</a></li>
                    <li class="nav-item"><a class="nav-link" href="#workflow">Alur Approval</a></li>
                </ul>

                <div class="d-flex gap-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-warning btn-sm fw-bold px-3 py-2 rounded-3">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="btn btn-outline-light btn-sm fw-semibold px-3 py-2 rounded-3">
                                Masuk
                            </a>

                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="bg-gradient-primary text-white py-5 py-lg-6 position-relative overflow-hidden">
        <div class="container py-4">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <span
                        class="badge bg-white bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill small mb-3">
                        <i class="bi bi-shield-check me-1"></i> Enterprise Warehouse Management System
                    </span>
                    <h1 class="display-5 fw-extrabold mb-3 leading-tight">
                        Sistem Pengajuan Gudang Distribusi Terintegrasi
                    </h1>
                    <p class="text-white-50 lead fs-6 mb-4">
                        Kelola alur pengajuan lokasi gudang baru secara transparan dengan keamanan autentikasi 2FA,
                        verifikasi berkas, dan persetujuan berjenjang real-time.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="btn btn-warning btn-lg fw-bold px-4 rounded-3 shadow-sm">
                                Buka Dashboard <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg fw-bold px-4 rounded-3 shadow-sm">
                                Ajukan Gudang Sekarang <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Graphic/Illustration Card -->
                <div class="col-lg-6">
                    <div
                        class="p-4 p-lg-5 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-20 text-white backdrop-blur">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex p-3 rounded-circle bg-warning bg-opacity-20 text-warning mb-3">
                                <i class="bi bi-building-add display-4"></i>
                            </div>
                            <h4 class="fw-bold">Manajemen Pengajuan Gudang</h4>
                            <p class="text-white-50 small mb-0">Platform digital terpadu untuk percepatan ekspansi
                                jaringan distribusi perusahaan.</p>
                        </div>

                        <div class="d-flex flex-column gap-3 mt-4">
                            <div
                                class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <i class="bi bi-shield-check text-warning fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Otorisasi & Keamanan 2FA</h6>
                                    <small class="text-white-50">Proteksi login akun dengan Google
                                        Authenticator.</small>
                                </div>
                            </div>

                            <div
                                class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <i class="bi bi-diagram-3 text-warning fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Persetujuan Berjenjang 6 Level</h6>
                                    <small class="text-white-50">Transparansi alur review dari SPV hingga
                                        Direktur.</small>
                                </div>
                            </div>

                            <div
                                class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <i class="bi bi-geo-alt text-warning fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Pemetaan Titik Lokasi GIS</h6>
                                    <small class="text-white-50">Visualisasi koordinat lokasi & budget
                                        pembangunan.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container py-4">
            <div class="text-center max-w-xl mx-auto mb-5">
                <h2 class="fw-bold text-dark">Fitur Unggulan Sistem</h2>
                <p class="text-muted">Dirancang untuk memenuhi standar operasional PT Safepedia Global Teknologi.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-custom h-100 p-4 bg-white">
                        <div class="step-icon bg-primary-subtle text-primary mb-3">
                            <i class="bi bi-shield-lock-fill fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">2FA Google Authenticator</h5>
                        <p class="text-muted small mb-0">Keamanan berlapis menggunakan OTP time-based untuk setiap akses
                            akun sesuai tingkatan otorisasi.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom h-100 p-4 bg-white">
                        <div class="step-icon bg-success-subtle text-success mb-3">
                            <i class="bi bi-diagram-3-fill fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Approval Berjenjang</h5>
                        <p class="text-muted small mb-0">Workflow persetujuan otomatis terstruktur dari Requestor
                            hingga Direktur Keuangan.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom h-100 p-4 bg-white">
                        <div class="step-icon bg-danger-subtle text-danger mb-3">
                            <i class="bi bi-geo-fill fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">GIS & Visualisasi Peta</h5>
                        <p class="text-muted small mb-0">Pemetaan lokasi gudang secara presisi berbasis Latitude &
                            Longitude dengan antarmuka peta interaktif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Approval Workflow Section -->
    <section id="workflow" class="py-5 bg-white border-top border-bottom">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Alur Persetujuan 6 Tingkat</h2>
                <p class="text-muted">Proses verifikasi ketat untuk menjamin kelayakan operasional dan finansial.</p>
            </div>

            <div class="row g-3 justify-content-center">
                <!-- Step 1 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-3 rounded-3 bg-light text-center h-100 border">
                        <span class="badge bg-secondary badge-step mb-2">1. Requestor</span>
                        <h6 class="fw-bold mb-1 fs-6">Buat Draft</h6>
                        <p class="text-muted extra-small mb-0" style="font-size: 0.75rem;">Input data & upload min. 3
                            dokumen</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-3 rounded-3 bg-light text-center h-100 border">
                        <span class="badge bg-primary badge-step mb-2">2. SPV Gudang</span>
                        <h6 class="fw-bold mb-1 fs-6">Review Awal</h6>
                        <p class="text-muted extra-small mb-0" style="font-size: 0.75rem;">Pemeriksaan kelayakan awal
                        </p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-3 rounded-3 bg-light text-center h-100 border">
                        <span class="badge bg-info text-dark badge-step mb-2">3. Ka. Gudang</span>
                        <h6 class="fw-bold mb-1 fs-6">Validasi Berkas</h6>
                        <p class="text-muted extra-small mb-0" style="font-size: 0.75rem;">Pemeriksaan dokumen &
                            kebutuhan</p>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-3 rounded-3 bg-light text-center h-100 border">
                        <span class="badge bg-warning text-dark badge-step mb-2">4. Manager Ops</span>
                        <h6 class="fw-bold mb-1 fs-6">Aspek Operasi</h6>
                        <p class="text-muted extra-small mb-0" style="font-size: 0.75rem;">Peninjauan lokasi &
                            estimasi budget</p>
                    </div>
                </div>
                <!-- Step 5 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-3 rounded-3 bg-light text-center h-100 border">
                        <span class="badge bg-indigo text-white bg-dark badge-step mb-2">5. Dir. Ops</span>
                        <h6 class="fw-bold mb-1 fs-6">Strategi Kelayakan</h6>
                        <p class="text-muted extra-small mb-0" style="font-size: 0.75rem;">Persetujuan strategi
                            operasional</p>
                    </div>
                </div>
                <!-- Step 6 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div
                        class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 text-center h-100">
                        <span class="badge bg-success badge-step mb-2">6. Dir. Keuangan</span>
                        <h6 class="fw-bold mb-1 fs-6 text-success">Persetujuan Akhir</h6>
                        <p class="text-muted extra-small mb-0" style="font-size: 0.75rem;">Finalisasi budget &
                            pencairan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-dark text-white-50 py-4 border-top border-secondary">
        <div class="container text-center small">
            <p class="mb-1">&copy; {{ date('Y') }} PT Safepedia Global Teknologi. All rights reserved.</p>
            <p class="mb-0 text-muted">Mini Project Technical Test - PHP Developer</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
