<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengajuan Gudang - {{ config('app.name', 'Safepedia') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3 sticky-top">
        <div class="container-fluid max-w-3xl px-3">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('warehouse-requests.show', $warehouseRequest) }}"
                        class="btn btn-outline-secondary btn-sm rounded-2" title="Kembali ke Detail">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h2 class="h5 font-weight-bold text-dark mb-0">
                            Edit Pengajuan Gudang
                        </h2>
                        <span class="text-muted extra-small font-monospace" style="font-size: 0.78rem;">Kode:
                            {{ $warehouseRequest->code }}</span>
                    </div>
                </div>
                <span
                    class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-2 small">
                    <i class="bi bi-pencil-square me-1"></i> Edit Draft
                </span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="d-inline"
            onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 rounded-2">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar
            </button>
        </form>
    </nav>

    <main class="py-4">
        <div class="container-fluid max-w-3xl px-3">

            <div class="mb-4">
                <h1 class="h4 font-weight-bold text-dark mb-1">
                    Pembaruan Informasi Pengajuan
                </h1>
                <p class="text-muted small mb-0">
                    Ubah data lahan, lokasi peta, atau estimasi anggaran sebelum melakukan submit.
                </p>
            </div>

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
            <div class="card border-0 bg-white rounded-3 shadow-sm">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('warehouse-requests.update', $warehouseRequest) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="warehouse_name" class="form-label font-weight-semibold text-dark small mb-1">
                                Nama Gudang <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="warehouse_name" name="warehouse_name"
                                value="{{ old('warehouse_name', $warehouseRequest->warehouse_name) }}"
                                class="form-control rounded-2" placeholder="Contoh: Gudang Distribusi Region 1"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label font-weight-semibold text-dark small mb-1">
                                Alamat Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea id="address" name="address" rows="3" class="form-control rounded-2"
                                placeholder="Jalan, Kelurahan, Kecamatan, Kota/Kabupaten" required>{{ old('address', $warehouseRequest->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label font-weight-semibold text-dark small mb-0">
                                    Lokasi Pada Peta <span class="text-danger">*</span>
                                </label>
                                <span class="text-muted extra-small" style="font-size: 0.78rem;">
                                    <i class="bi bi-geo-fill text-primary me-1"></i>Klik lokasi di peta untuk
                                    memperbarui koordinat
                                </span>
                            </div>
                            <div id="picker-map" class="border"></div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label font-weight-semibold text-dark small mb-1">
                                    Latitude <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="latitude" name="latitude" step="any"
                                    value="{{ old('latitude', $warehouseRequest->latitude) }}"
                                    class="form-control rounded-2" placeholder="-6.200000" required readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="longitude" class="form-label font-weight-semibold text-dark small mb-1">
                                    Longitude <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="longitude" name="longitude" step="any"
                                    value="{{ old('longitude') ?? $warehouseRequest->longitude }}"
                                    class="form-control rounded-2" placeholder="106.816666" required readonly>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="area" class="form-label font-weight-semibold text-dark small mb-1">
                                    Luas Area (m²) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="area" name="area" step="0.01"
                                        min="0" value="{{ old('area', $warehouseRequest->area) }}"
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
                                        step="0.01" min="0"
                                        value="{{ old('estimated_budget', $warehouseRequest->estimated_budget) }}"
                                        class="form-control rounded-end-2" placeholder="5000000000" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label font-weight-semibold text-dark small mb-1">
                                Deskripsi Tambahan <span class="text-danger">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" class="form-control rounded-2"
                                placeholder="Jelaskan kebutuhan operasional dan pertimbangan lokasi gudang ini..." required>{{ old('description', $warehouseRequest->description) }}</textarea>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('warehouse-requests.show', $warehouseRequest) }}"
                                class="btn btn-light font-weight-semibold btn-sm px-3 py-2 rounded-2 border">
                                Batal
                            </a>
                            <button type="submit"
                                class="btn btn-primary font-weight-bold btn-sm px-3 py-2 rounded-2">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

   
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var currentLat = {{ old('latitude', $warehouseRequest->latitude ?? -6.2088) }};
            var currentLng = {{ old('longitude', $warehouseRequest->longitude ?? 106.8456) }};

            var map = L.map('picker-map').setView([currentLat, currentLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([currentLat, currentLng]).addTo(map)
                .bindPopup('<b>{{ addslashes($warehouseRequest->warehouse_name) }}</b><br>Lokasi saat ini.')
                .openPopup();

            map.on('click', function(e) {
                var lat = e.latlng.lat.toFixed(6);
                var lng = e.latlng.lng.toFixed(6);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                marker.setLatLng(e.latlng);
            });
        });
    </script>
</body>

</html>
