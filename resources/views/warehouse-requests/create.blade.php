<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Pengajuan Gudang - {{ config('app.name', 'Safepedia') }}</title>

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

        .max-w-3xl {
            max-width: 48rem;
        }

        #picker-map {
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
        <div class="container-fluid max-w-3xl px-3">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('warehouse-requests.index') }}" class="btn btn-outline-secondary btn-sm rounded-2"
                        title="Kembali ke Daftar">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="h5 font-weight-bold text-dark mb-0">
                        Buat Pengajuan Gudang
                    </h2>
                </div>
                <span class="badge bg-light text-muted border px-2.5 py-1 rounded-2 small">
                    Formulir Draft
                </span>
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
        <div class="container-fluid max-w-3xl px-3">

            <!-- Banner Deskripsi -->
            <div class="mb-4">
                <h1 class="h4 font-weight-bold text-dark mb-1">
                    Informasi Pengajuan Pembangunan Gudang
                </h1>
                <p class="text-muted small mb-0">
                    Lengkapi seluruh informasi data lahan dan estimasi anggaran di bawah ini.
                </p>
            </div>

            {{-- Error Validation Alert --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3"
                    role="alert">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                        <strong class="small">Terdapat kesalahan dalam pengisian form:</strong>
                    </div>
                    <ul class="mb-0 ps-4 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form Card -->
            <div class="card border-0 bg-white rounded-3 shadow-sm">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('warehouse-requests.store') }}">
                        @csrf

                        <!-- Nama Gudang -->
                        <div class="mb-3">
                            <label for="warehouse_name" class="form-label font-weight-semibold text-dark small mb-1">
                                Nama Gudang <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="warehouse_name" name="warehouse_name"
                                value="{{ old('warehouse_name') }}" class="form-control rounded-2"
                                placeholder="Contoh: Gudang Distribusi Region 1" required>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="address" class="form-label font-weight-semibold text-dark small mb-1">
                                Alamat Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea id="address" name="address" rows="3" class="form-control rounded-2"
                                placeholder="Jalan, Kelurahan, Kecamatan, Kota/Kabupaten" required>{{ old('address') }}</textarea>
                        </div>

                        <!-- Map Picker Section -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label font-weight-semibold text-dark small mb-0">
                                    Pilih Lokasi Pada Peta <span class="text-danger">*</span>
                                </label>
                                <span class="text-muted extra-small" style="font-size: 0.78rem;">
                                    <i class="bi bi-geo-fill text-primary me-1"></i>Klik lokasi di peta untuk mengisi
                                    koordinat
                                </span>
                            </div>
                            <div id="picker-map" class="border"></div>
                        </div>

                        <!-- Grid Latitude & Longitude -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label font-weight-semibold text-dark small mb-1">
                                    Latitude <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="latitude" name="latitude" step="any"
                                    value="{{ old('latitude') }}" class="form-control rounded-2" placeholder="-6.200000"
                                    required readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="longitude" class="form-label font-weight-semibold text-dark small mb-1">
                                    Longitude <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="longitude" name="longitude" step="any"
                                    value="{{ old('longitude') }}" class="form-control rounded-2"
                                    placeholder="106.816666" required readonly>
                            </div>
                        </div>

                        <!-- Grid Luas Area & Budget -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="area" class="form-label font-weight-semibold text-dark small mb-1">
                                    Luas Area (m²) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="area" name="area" step="0.01"
                                        min="0" value="{{ old('area') }}"
                                        class="form-control rounded-start-2" placeholder="1500" required>
                                    <span class="input-group-text bg-light text-muted small rounded-end-2">m²</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="estimated_budget"
                                    class="form-label font-weight-semibold text-dark small mb-1">
                                    Estimasi Budget (Rp) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted small rounded-start-2">Rp</span>
                                    <input type="number" id="estimated_budget" name="estimated_budget"
                                        step="0.01" min="0" value="{{ old('estimated_budget') }}"
                                        class="form-control rounded-end-2" placeholder="5000000000" required>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="form-label font-weight-semibold text-dark small mb-1">
                                Deskripsi Tambahan <span class="text-danger">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" class="form-control rounded-2"
                                placeholder="Jelaskan kebutuhan operasional dan pertimbangan lokasi gudang ini..." required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('warehouse-requests.index') }}"
                                class="btn btn-light font-weight-semibold btn-sm px-3 py-2 rounded-2 border">
                                Batal
                            </a>
                            <button type="submit"
                                class="btn btn-primary font-weight-bold btn-sm px-3 py-2 rounded-2">
                                <i class="bi bi-save me-1"></i> Simpan Draft
                            </button>
                        </div>

                    </form>

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
            // Default center: Jakarta (-6.2088, 106.8456) atau nilai old() jika validasi gagal
            var initialLat = {{ old('latitude', -6.2088) }};
            var initialLng = {{ old('longitude', 106.8456) }};
            var hasOldValue = {{ old('latitude') ? 'true' : 'false' }};

            var map = L.map('picker-map').setView([initialLat, initialLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            var marker;

            // Pasang marker jika ada old value
            if (hasOldValue) {
                marker = L.marker([initialLat, initialLng]).addTo(map);
            }

            // Handler klik pada peta
            map.on('click', function(e) {
                var lat = e.latlng.lat.toFixed(6);
                var lng = e.latlng.lng.toFixed(6);

                // Update nilai input form
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Geser atau buat marker baru
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });
        });
    </script>
</body>

</html>
