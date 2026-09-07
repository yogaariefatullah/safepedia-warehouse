<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Pengajuan Gudang - {{ config('app.name', 'Safepedia') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 & Bootstrap Icons CDN -->
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

        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>

<body class="bg-light min-vh-100">

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3 sticky-top">
        <div class="container-fluid max-w-7xl px-3 px-lg-4">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between w-100 gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-2"
                        title="Kembali ke Dashboard">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h2 class="h5 font-weight-bold text-dark mb-0">
                            Pengajuan Pembangunan Gudang
                        </h2>
                        <p class="text-muted extra-small mb-0 mt-0.5">
                            Daftar dan status seluruh permohonan otorisasi lokasi gudang distribusi.
                        </p>
                    </div>
                </div>

                @if ((auth()->user()->role->name ?? '') === 'requestor')
                    <div>
                        <a href="{{ route('warehouse-requests.create') }}"
                            class="btn btn-primary btn-sm font-weight-bold px-3 py-2 rounded-2">
                            <i class="bi bi-plus-lg me-1"></i> Buat Pengajuan Baru
                        </a>
                    </div>
                @endif
                <!-- Tombol Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 rounded-2">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </button>
                    </form>
            </div>
        </div>
    </nav>

    <!-- Main Content Body -->
    <main class="py-4">
        <div class="container-fluid max-w-7xl px-3 px-lg-4">

            {{-- Flash Alert Session --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3"
                    role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Data Table Card Container -->
            <div class="card border-0 bg-white rounded-3 shadow-sm overflow-hidden">

                <!-- Table Header / Quick Search -->
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1.5 rounded-2">
                                <i class="bi bi-layers me-1"></i> Total Data: {{ $requests->total() }}
                            </span>
                        </div>

                        <!-- Form Search Ringkas -->
                        <a href="{{ route('warehouse-requests.create') }}"
                            class="btn btn-primary btn-sm font-weight-bold px-3 py-1.5 rounded-2 d-inline-flex align-items-center">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">Kode
                                </th>
                                <th scope="col" class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">Nama
                                    Gudang</th>
                                <th scope="col" class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">
                                    Estimasi Budget</th>
                                <th scope="col" class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">
                                    Status Approval</th>
                                <th scope="col"
                                    class="px-4 py-3 text-end text-muted extra-small text-uppercase fw-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $request)
                                <tr class="border-bottom">
                                    <!-- Kode Pengajuan -->
                                    <td class="px-4 py-3">
                                        <span
                                            class="fw-bold text-dark font-monospace small bg-light px-2 py-1 rounded border">{{ $request->code }}</span>
                                    </td>

                                    <!-- Nama Gudang -->
                                    <td class="px-4 py-3">
                                        <span
                                            class="fw-bold text-dark d-block small">{{ $request->warehouse_name }}</span>
                                        <span class="text-muted extra-small"><i
                                                class="bi bi-geo-alt me-0.5"></i>{{ Str::limit($request->address, 35) }}</span>
                                    </td>

                                    <!-- Estimasi Budget -->
                                    <td class="px-4 py-3">
                                        <span class="fw-semibold text-dark small">Rp
                                            {{ number_format($request->estimated_budget, 0, ',', '.') }}</span>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-4 py-3">
                                        @if ($request->status === 'draft')
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-2 extra-small">
                                                <i class="bi bi-pencil-square me-1"></i> Draft
                                            </span>
                                        @elseif ($request->status === 'submitted')
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-2 extra-small">
                                                <i class="bi bi-clock-history me-1"></i> Review
                                            </span>
                                        @elseif ($request->status === 'approved')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-2 extra-small">
                                                <i class="bi bi-check-circle me-1"></i> Approved
                                            </span>
                                        @elseif ($request->status === 'rejected')
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-2 extra-small">
                                                <i class="bi bi-x-circle me-1"></i> Rejected
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 extra-small">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            {{-- Detail --}}
                                            <a href="{{ route('warehouse-requests.show', $request) }}"
                                                class="btn btn-outline-secondary btn-sm px-2.5 py-1 rounded-2"
                                                title="Lihat Detail">
                                                <i class="bi bi-eye me-1"></i> Detail
                                            </a>

                                            @if ($request->status === 'draft')
                                                {{-- Edit --}}
                                                <a href="{{ route('warehouse-requests.edit', $request) }}"
                                                    class="btn btn-outline-primary btn-sm px-2.5 py-1 rounded-2"
                                                    title="Edit Draft">
                                                    <i class="bi bi-pencil me-1"></i> Edit
                                                </a>

                                                {{-- Hapus --}}
                                                <form method="POST"
                                                    action="{{ route('warehouse-requests.destroy', $request) }}"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus permohonan draft ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm px-2 py-1 rounded-2"
                                                        title="Hapus Draft">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-5 text-center text-muted">
                                        <div class="py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                            <p class="mb-2 fw-medium text-dark">Belum ada pengajuan gudang yang
                                                terdaftar.</p>
                                            @if ((auth()->user()->role->name ?? '') === 'requestor')
                                                <a href="{{ route('warehouse-requests.create') }}"
                                                    class="btn btn-primary btn-sm font-weight-semibold rounded-2 mt-1">
                                                    <i class="bi bi-plus-lg me-1"></i> Buat Pengajuan Pertama
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if ($requests->hasPages())
                    <div class="card-footer bg-white border-top py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            {{ $requests->links() }}
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
