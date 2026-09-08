<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pengajuan - {{ config('app.name', 'Safepedia') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 & Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Leaflet OpenStreetMap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .max-w-4xl {
            max-width: 56rem;
        }

        .extra-small {
            font-size: 0.78rem;
        }

        #map {
            height: 260px;
            width: 100%;
            border-radius: 0.5rem;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-light min-vh-100">

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3 sticky-top">
        <div class="container-fluid max-w-4xl px-3">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('warehouse-requests.index') }}" class="btn btn-outline-secondary btn-sm rounded-2"
                        title="Kembali">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h2 class="h5 font-weight-bold text-dark mb-0">
                            Detail Pengajuan
                        </h2>
                        <span class="text-muted extra-small font-monospace">Kode: {{ $warehouseRequest->code }}</span>
                    </div>
                </div>

                <div>
                    @if ($warehouseRequest->status === 'draft')
                        <span
                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-2">
                            <i class="bi bi-pencil-square me-1"></i> Draft
                        </span>
                    @elseif ($warehouseRequest->status === 'submitted')
                        <span
                            class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-2">
                            <i class="bi bi-clock-history me-1"></i> On Review
                        </span>
                    @elseif ($warehouseRequest->status === 'approved')
                        <span
                            class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-2">
                            <i class="bi bi-check-circle me-1"></i> Approved
                        </span>
                    @elseif ($warehouseRequest->status === 'rejected')
                        <span
                            class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-2">
                            <i class="bi bi-x-circle me-1"></i> Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" class="d-inline"
            onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 rounded-2">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar
            </button>
        </form>
    </nav>

    <!-- Main Content Body -->
    <main class="py-4">
        <div class="container-fluid max-w-4xl px-3">

            {{-- Flash Alert Session Success --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Informational Summary Card -->
            <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="font-weight-bold text-dark mb-0 fs-6">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informasi Utama Gudang
                    </h5>
                </div>
                <div class="card-body p-4">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <span class="text-muted extra-small d-block text-uppercase font-weight-semibold">Nama
                                Gudang</span>
                            <span class="fw-bold text-dark fs-6">{{ $warehouseRequest->warehouse_name }}</span>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted extra-small d-block text-uppercase font-weight-semibold">Requestor
                                (Pemohon)</span>
                            <span class="fw-semibold text-dark">{{ $warehouseRequest->requestor->name }}</span>
                        </div>

                        <div class="col-12">
                            <span class="text-muted extra-small d-block text-uppercase font-weight-semibold">Alamat
                                Lokasi</span>
                            <span class="fw-medium text-dark">{{ $warehouseRequest->address }}</span>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted extra-small d-block text-uppercase font-weight-semibold">Luas Area
                                Lahan</span>
                            <span
                                class="fw-semibold text-dark">{{ number_format($warehouseRequest->area, 2, ',', '.') }}
                                m²</span>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted extra-small d-block text-uppercase font-weight-semibold">Estimasi
                                Budget Pembangunan</span>
                            <span class="fw-bold text-success fs-6">Rp
                                {{ number_format($warehouseRequest->estimated_budget, 0, ',', '.') }}</span>
                        </div>

                        <div class="col-12">
                            <span
                                class="text-muted extra-small d-block text-uppercase font-weight-semibold mb-1">Deskripsi
                                Tambahan</span>
                            <div class="bg-light p-3 rounded-2 text-dark small border">
                                {{ $warehouseRequest->description }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- GIS Map Visual Card -->
            <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h5 class="font-weight-bold text-dark mb-0 fs-6">
                        <i class="bi bi-geo-alt text-primary me-2"></i>Pemetaan Lokasi GIS
                    </h5>
                    <span class="text-muted extra-small font-monospace">
                        Lat: {{ $warehouseRequest->latitude }}, Long: {{ $warehouseRequest->longitude }}
                    </span>
                </div>
                <div class="card-body p-3">
                    <div id="map"></div>
                </div>
            </div>

            {{-- Tahap Upload Dokumen (Khusus Status Draft) --}}
            @if ($warehouseRequest->status === 'draft')
                <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="font-weight-bold text-dark mb-0 fs-6">
                            <i class="bi bi-cloud-upload text-primary me-2"></i>Upload Dokumen Pendukung
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">
                            Unggah berkas persyaratan pengajuan (Proposal, RAB, Legalitas Lahan, dll). Format yang
                            diperbolehkan: PDF, JPG, PNG, DOC, DOCX (Maksimal 5 MB per file).
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 small mb-3">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST"
                            action="{{ route('warehouse-requests.documents.upload', $warehouseRequest) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="documents" class="form-label font-weight-semibold small">Pilih File
                                    Dokumen</label>
                                <input type="file" id="documents" name="documents[]" multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required class="form-control rounded-2">
                                <span class="text-muted extra-small mt-1 d-block">Anda dapat memilih lebih dari satu
                                    file sekaligus.</span>
                            </div>

                            <button type="submit"
                                class="btn btn-primary btn-sm font-weight-bold px-3 py-2 rounded-2">
                                <i class="bi bi-upload me-1"></i> Upload Dokumen
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Tahap Submit Pengajuan (Khusus Status Draft) --}}
            @if ($warehouseRequest->status === 'draft')
                @php
                    $documentCount = $warehouseRequest->documents->count();
                @endphp

                <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                    <div
                        class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="font-weight-bold text-dark mb-0 fs-6">
                            <i class="bi bi-send text-primary me-2"></i>Submit Form Pengajuan
                        </h5>
                        <span
                            class="badge {{ $documentCount >= 3 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} px-2.5 py-1 rounded-2 small fw-semibold">
                            Dokumen: {{ $documentCount }} / 3 Syarat Minimal
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">
                            Sistem mengharuskan pengajuan memiliki minimal **3 dokumen pendukung** sebelum diserahkan ke
                            alur persetujuan.
                        </p>

                        @if ($documentCount >= 3)
                            <form method="POST" action="{{ route('warehouse-requests.submit', $warehouseRequest) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin mengajukan permohonan ini ke alur approval?');">
                                @csrf
                                <button type="submit"
                                    class="btn btn-success btn-sm font-weight-bold px-4 py-2 rounded-2">
                                    <i class="bi bi-check2-circle me-1"></i> Submit Pengajuan Sekarang
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning border-0 small mb-0">
                                <i class="bi bi-exclamation-triangle me-1"></i> Silakan unggah minimal
                                <strong>{{ 3 - $documentCount }} dokumen lagi</strong> sebelum dapat melanjutkan proses
                                submit.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Document Attachments Card -->
            <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="font-weight-bold text-dark mb-0 fs-6">
                        <i class="bi bi-paperclip text-primary me-2"></i>Berkas Dokumen Pendukung
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if ($warehouseRequest->documents->count())
                        <div class="list-group">
                            @foreach ($warehouseRequest->documents as $document)
                                <div
                                    class="list-group-item d-flex align-items-center justify-content-between rounded-2 border mb-2 p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light p-2 rounded text-primary">
                                            <i class="bi bi-file-earmark-text fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark small">{{ $document->document_name }}
                                            </h6>
                                            <span class="text-muted extra-small">
                                                Tipe: {{ strtoupper($document->file_type) }} •
                                                {{ number_format($document->file_size / 1024, 2) }} KB
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Tombol Download Dokumen --}}
                                        <a href="{{ route('warehouse-requests.documents.download', $document) }}"
                                            class="btn btn-outline-primary btn-sm px-2.5 py-1" title="Download File">
                                            <i class="bi bi-download me-1"></i> Download
                                        </a>

                                        {{-- Tombol Hapus Dokumen (Hanya jika status masih Draft) --}}
                                        @if ($warehouseRequest->status === 'draft')
                                            <form method="POST"
                                                action="{{ route('warehouse-requests.documents.destroy.zz', $document) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1"
                                                    title="Hapus Dokumen">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-file-earmark-x fs-2 d-block mb-1 text-secondary"></i>
                            <span class="small">Belum ada berkas dokumen yang diunggah.</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Approval Audit History Card -->
            <div class="card border-0 bg-white rounded-3 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="font-weight-bold text-dark mb-0 fs-6">
                        <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Persetujuan (Approval History)
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if ($warehouseRequest->approvalHistories->count())
                        <div class="d-flex flex-column gap-3">
                            @foreach ($warehouseRequest->approvalHistories as $history)
                                <div
                                    class="p-3 rounded-2 border-start border-4 {{ $history->status === 'approved' ? 'border-success bg-light' : ($history->status === 'rejected' ? 'border-danger bg-light' : 'border-warning bg-light') }}">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span
                                            class="fw-bold text-dark small">{{ $history->approvalLevel->name }}</span>
                                        <span
                                            class="badge {{ $history->status === 'approved' ? 'bg-success' : ($history->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} extra-small">
                                            {{ ucfirst($history->status) }}
                                        </span>
                                    </div>
                                    <span class="text-muted extra-small d-block mb-1">
                                        Oleh: <strong>{{ $history->approver->name }}</strong>
                                        ({{ $history->approver->role->name ?? 'Approver' }})
                                    </span>

                                    @if ($history->note)
                                        <div class="p-2 bg-white rounded border extra-small text-dark mt-2">
                                            <strong>Catatan:</strong> {{ $history->note }}
                                        </div>
                                    @endif

                                    <span class="text-muted extra-small d-block mt-2">
                                        Waktu: {{ $history->action_at?->format('d M Y, H:i') ?? '-' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-hourglass-split fs-2 d-block mb-1 text-secondary"></i>
                            <span class="small">Belum ada riwayat persetujuan untuk pengajuan ini.</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet OpenStreetMap JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lat = {{ $warehouseRequest->latitude ?? -6.2088 }};
            var lng = {{ $warehouseRequest->longitude ?? 106.8456 }};
            var name = "{{ addslashes($warehouseRequest->warehouse_name) }}";

            var map = L.map('map').setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>' + name + '</b><br>Lat: ' + lat + '<br>Long: ' + lng)
                .openPopup();
        });
    </script>
</body>

</html>
