    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Detail Approval Warehouse - {{ config('app.name', 'Safepedia') }}</title>

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

            #view-map {
                height: 280px;
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
                        <a href="{{ route('approvals.index') }}" class="btn btn-outline-secondary btn-sm rounded-2"
                            title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="h5 font-weight-bold text-dark mb-0">
                                Detail Approval Warehouse
                            </h2>
                            <span class="text-muted extra-small">Nomor Pengajuan:
                                <strong>{{ $warehouseRequest->request_number ?? $warehouseRequest->code }}</strong></span>
                        </div>
                    </div>

                    <div>
                        @if ($warehouseRequest->status === 'submitted')
                            <span
                                class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-2">
                                <i class="bi bi-clock-history me-1"></i> Submitted
                            </span>
                        @elseif ($warehouseRequest->status === 'on_review')
                            <span
                                class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5 rounded-2">
                                <i class="bi bi-search me-1"></i> On Review
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
                        @else
                            <span
                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-2">
                                {{ ucfirst($warehouseRequest->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Body -->
        <main class="py-4">
            <div class="container-fluid max-w-4xl px-3">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3"
                        role="alert">

                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>

                            <div>
                                <strong>Pengajuan tidak dapat ditolak.</strong>

                                <ul class="mb-0 mt-1 ps-3 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @endif
                <!-- Form Isian Pengajuan (Read-Only) -->
                <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                    <div
                        class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="font-weight-bold text-dark mb-0 fs-6">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>Formulir Data Pengajuan (Read-Only)
                        </h5>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-2 extra-small">
                            Pemohon: {{ $warehouseRequest->requestor->name ?? '-' }}
                        </span>
                    </div>
                    <div class="card-body p-4">

                        <!-- Nama Gudang -->
                        <div class="mb-3">
                            <label class="form-label font-weight-semibold text-dark small mb-1">Nama Gudang</label>
                            <input type="text" class="form-control bg-light rounded-2"
                                value="{{ $warehouseRequest->warehouse_name }}" readonly>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label class="form-label font-weight-semibold text-dark small mb-1">Alamat Lengkap</label>
                            <textarea class="form-control bg-light rounded-2" rows="3" readonly>{{ $warehouseRequest->address }}</textarea>
                        </div>

                        <!-- Map Display -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label font-weight-semibold text-dark small mb-0">
                                    Lokasi Pada Peta
                                </label>
                                <span class="text-muted extra-small" style="font-size: 0.78rem;">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>Pin koordinat lokasi terpilih
                                </span>
                            </div>
                            <div id="view-map" class="border"></div>
                        </div>

                        <!-- Grid Latitude & Longitude -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-semibold text-dark small mb-1">Latitude</label>
                                <input type="text" class="form-control bg-light rounded-2"
                                    value="{{ $warehouseRequest->latitude }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-semibold text-dark small mb-1">Longitude</label>
                                <input type="text" class="form-control bg-light rounded-2"
                                    value="{{ $warehouseRequest->longitude }}" readonly>
                            </div>
                        </div>

                        <!-- Grid Luas Area & Budget -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-semibold text-dark small mb-1">Luas Area
                                    (m²)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light rounded-start-2"
                                        value="{{ number_format($warehouseRequest->area, 2, ',', '.') }}" readonly>
                                    <span class="input-group-text bg-light text-muted small rounded-end-2">m²</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-semibold text-dark small mb-1">Estimasi Budget
                                    (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted small rounded-start-2">Rp</span>
                                    <input type="text"
                                        class="form-control bg-light rounded-end-2 fw-semibold text-success"
                                        value="{{ number_format($warehouseRequest->estimated_budget, 0, ',', '.') }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-0">
                            <label class="form-label font-weight-semibold text-dark small mb-1">Deskripsi
                                Tambahan</label>
                            <textarea class="form-control bg-light rounded-2" rows="4" readonly>{{ $warehouseRequest->description }}</textarea>
                        </div>

                    </div>
                </div>

                <!-- Dokumen Pendukung Card -->
                <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="font-weight-bold text-dark mb-0 fs-6">
                            <i class="bi bi-paperclip text-primary me-2"></i>Dokumen Pendukung
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse($warehouseRequest->documents as $document)
                            <div
                                class="list-group-item d-flex align-items-center justify-content-between rounded-2 border mb-2 p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light p-2 rounded text-primary">
                                        <i class="bi bi-file-earmark-text fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small">
                                            {{ $document->name ?? ($document->file_name ?? ($document->document_name ?? 'Dokumen Pendukung')) }}
                                        </h6>
                                    </div>
                                </div>

                                @if ($document->file_path ?? false)
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm px-2.5 py-1 rounded-2"
                                        title="Lihat Dokumen">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-file-earmark-x fs-2 d-block mb-1 text-secondary opacity-50"></i>
                                <span class="small">Tidak ada dokumen pendukung.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Approval History Card -->
                <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="font-weight-bold text-dark mb-0 fs-6">
                            <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Approval
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse($warehouseRequest->approvalHistories as $history)
                            <div
                                class="p-3 rounded-2 border-start border-4 mb-3 {{ $history->status === 'approved' ? 'border-success bg-light' : ($history->status === 'rejected' ? 'border-danger bg-light' : 'border-warning bg-light') }}">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="fw-bold text-dark small">Level
                                        {{ $history->approvalLevel->level ?? '-' }}</span>
                                    <span
                                        class="badge {{ $history->status === 'approved' ? 'bg-success' : ($history->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} extra-small">
                                        {{ ucfirst($history->status) }}
                                    </span>
                                </div>
                                <span class="text-muted extra-small d-block">
                                    {{ $history->approver->role->name ?? 'Role' }} •
                                    <strong>{{ $history->approver->name ?? 'Approver' }}</strong>
                                </span>

                                @if ($history->reason)
                                    <div class="p-2 bg-white rounded border extra-small text-dark mt-2">
                                        <strong>Alasan:</strong> {{ $history->reason }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-hourglass-split fs-2 d-block mb-1 text-secondary opacity-50"></i>
                                <span class="small">Belum ada riwayat approval.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Action Approval Card -->
                <div class="card border-0 bg-white rounded-3 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="font-weight-bold text-dark mb-0 fs-6">
                            <i class="bi bi-shield-check text-primary me-2"></i>Tindakan Approval
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($canApprove)
                            {{-- TAMPILKAN TOMBOL ACTION JIKA MEMILIKI HAK AKSES --}}
                            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-3 rounded-3"
                                role="alert">
                                <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                                <span class="small">Anda sedang memproses pertimbangan untuk <strong>Level
                                        {{ $approvalLevel->level ?? 1 }}</strong>.</span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('approvals.approve', $warehouseRequest) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-success btn-sm font-weight-bold px-3 py-2 rounded-2"
                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui (Approve) pengajuan ini?')">
                                        <i class="bi bi-check-lg me-1"></i> Approve
                                    </button>
                                </form>

                                <button type="button"
                                    class="btn btn-danger btn-sm font-weight-bold px-3 py-2 rounded-2"
                                    data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="bi bi-x-lg me-1"></i> Reject
                                </button>
                            </div>
                        @else
                            {{-- TAMPILKAN INFORMASI READ-ONLY JIKA TAHAPNYA SUDAH LEWAT/SELESAI --}}
                            <div class="alert alert-secondary border-0 d-flex align-items-center mb-0 rounded-3"
                                role="alert">
                                <i class="bi bi-eye-fill fs-5 me-2 text-secondary"></i>
                                <div class="small">
                                    <strong>Mode Lihat Saja (Read-Only)</strong>
                                    <br>
                                    @if ($warehouseRequest->status === 'approved')
                                        Pengajuan ini telah <strong>Disetujui Penuh (Approved)</strong>.
                                    @elseif ($warehouseRequest->status === 'rejected')
                                        Pengajuan ini telah <strong>Ditolak (Rejected)</strong>.
                                    @else
                                        Pengajuan ini saat ini sedang berada pada tahap <strong>Level
                                            {{ $warehouseRequest->current_approval_level }}</strong>.
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </main>

        <!-- Reject Modal (Bootstrap 5) -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('approvals.reject', $warehouseRequest) }}" method="POST">
                        @csrf
                        <div class="modal-header border-bottom py-3">
                            <h5 class="modal-title font-weight-bold fs-6" id="rejectModalLabel">
                                <i class="bi bi-exclamation-triangle text-danger me-2"></i>Reject Pengajuan
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label for="reason" class="form-label font-weight-semibold text-dark small mb-1">
                                    Alasan Reject <span class="text-danger">*</span>
                                </label>
                                <textarea id="note" name="note" class="form-control rounded-2" rows="4"
                                    placeholder="Tuliskan alasan penolakan pengajuan..." required>{{ old('note') }}</textarea>
                            </div>
                        </div>

                        <div class="modal-footer border-top py-2">
                            <button type="button" class="btn btn-light btn-sm border font-weight-semibold rounded-2"
                                data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm font-weight-bold rounded-2">
                                <i class="bi bi-x-circle me-1"></i> Reject Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Leaflet OpenStreetMap JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var lat = {{ $warehouseRequest->latitude ?? -6.2088 }};
                var lng = {{ $warehouseRequest->longitude ?? 106.8456 }};
                var warehouseName = "{{ addslashes($warehouseRequest->warehouse_name) }}";

                var map = L.map('view-map').setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup('<b>' + warehouseName + '</b><br>Lat: ' + lat + '<br>Long: ' + lng)
                    .openPopup();
            });
        </script>
    </body>

    </html>
