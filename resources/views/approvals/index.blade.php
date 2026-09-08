<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Approval Pengajuan - {{ config('app.name', 'Safepedia') }}</title>

    
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

        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }

        .max-w-7xl {
            max-width: 90rem;
        }

        /* Timeline Mini Component */
        .timeline-stepper {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .timeline-step {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #cbd5e1;
            position: relative;
        }

        .timeline-step.active {
            background-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
        }

        .timeline-step.completed {
            background-color: #198754;
        }

        .timeline-step.rejected {
            background-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
        }

        .timeline-line {
            flex-grow: 1;
            height: 2px;
            background-color: #e2e8f0;
            min-width: 12px;
        }

        .timeline-line.completed {
            background-color: #198754;
        }
    </style>
</head>

<body class="bg-light min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3 sticky-top">
        <div class="container-fluid max-w-7xl px-3 px-lg-4">

            <div class="d-flex align-items-center justify-content-between w-100">

                <div class="d-flex align-items-center gap-3">

                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-2"
                        title="Kembali ke Dashboard">
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <div>
                        <h2 class="h5 font-weight-bold text-dark mb-0">
                            Approval Pengajuan Gudang
                        </h2>

                        <p class="text-muted extra-small mb-0">
                            Daftar pengajuan yang membutuhkan review dan persetujuan Anda.
                        </p>
                    </div>

                </div>

                <form method="POST" action="{{ route('logout') }}" class="d-inline"
                    onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                    @csrf

                    <button type="submit" class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 rounded-2">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Keluar
                    </button>
                </form>

            </div>

        </div>
    </nav>
    <main class="py-4">

        <div class="container-fluid max-w-7xl px-3 px-lg-4">
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3"
                    role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <span>{{ session('error') }}</span>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
            <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="bi bi-person-check fs-4"></i>
                        </div>

                        <div>
                            <span class="text-muted extra-small d-block">
                                LEVEL APPROVAL ANDA
                            </span>

                            <h5 class="fw-bold text-dark mb-0">
                                Level {{ $approvalLevel->level }} —
                                {{ $approvalLevel->name }}
                            </h5>
                        </div>

                    </div>

                </div>

            </div>

            <div class="card border-0 bg-white rounded-3 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4">

                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

               
                        <div>
                            @if (($statusFilter ?? request('status')) === 'approved')
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle fw-semibold px-3 py-2 rounded-2">
                                    <i class="bi bi-check-all me-1"></i>
                                    Pengajuan Disetujui: {{ $requests->total() }}
                                </span>
                            @elseif (($statusFilter ?? request('status')) === 'rejected')
                                <span
                                    class="badge bg-danger-subtle text-danger border border-danger-subtle fw-semibold px-3 py-2 rounded-2">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Pengajuan Ditolak: {{ $requests->total() }}
                                </span>
                            @else
                                <span
                                    class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-3 py-2 rounded-2">
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    Menunggu Approval: {{ $requests->total() }}
                                </span>
                            @endif
                        </div>

                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('approvals.index') }}"
                                class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                Pending / Review
                            </a>
                            <a href="{{ route('approvals.index', ['status' => 'approved']) }}"
                                class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-secondary' }}">
                                Approved
                            </a>
                            <a href="{{ route('approvals.index', ['status' => 'rejected']) }}"
                                class="btn {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}">
                                Rejected
                            </a>
                        </div>

                    </div>

                </div>
                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light border-bottom">

                            <tr>

                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">
                                    Kode
                                </th>

                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">
                                    Nama Gudang
                                </th>

                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">
                                    Requestor
                                </th>

                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">
                                    Estimasi Budget
                                </th>

                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold"
                                    style="min-width: 200px;">
                                    Status & Progress Approval
                                </th>

                                <th class="px-4 py-3 text-end text-muted extra-small text-uppercase fw-bold">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($requests as $request)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3">

                                        <span
                                            class="fw-bold text-dark font-monospace small bg-light px-2 py-1 rounded border">
                                            {{ $request->code }}
                                        </span>

                                    </td>
                                    <td class="px-4 py-3">

                                        <span class="fw-bold text-dark d-block small">
                                            {{ $request->warehouse_name }}
                                        </span>

                                        <span class="text-muted extra-small">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ Str::limit($request->address, 35) }}
                                        </span>

                                    </td>
                                    <td class="px-4 py-3">

                                        <span class="fw-semibold text-dark small">
                                            {{ $request->requestor->name }}
                                        </span>

                                    </td>
                                    <td class="px-4 py-3">

                                        <span class="fw-semibold text-dark small">
                                            Rp {{ number_format($request->estimated_budget, 0, ',', '.') }}
                                        </span>

                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($request->status === 'approved')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-2 extra-small mb-1 d-inline-block">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approved Full
                                            </span>
                                            <div class="timeline-stepper mt-1" title="Semua level approval selesai">
                                                @for ($i = 1; $i <= 6; $i++)
                                                    <div class="timeline-step completed" data-bs-toggle="tooltip"
                                                        title="Lvl {{ $i }} Approved"></div>
                                                    @if ($i < 6)
                                                        <div class="timeline-line completed"></div>
                                                    @endif
                                                @endfor
                                            </div>
                                        @elseif ($request->status === 'rejected')
                                            @php
                                                $lastHistory = $request->approvalHistories
                                                    ->where('status', 'rejected')
                                                    ->last();
                                                $rejectedLevel =
                                                    $lastHistory?->approvalLevel?->level ??
                                                    ($request->current_approval_level ?? 1);
                                            @endphp
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-2 extra-small mb-1 d-inline-block"
                                                data-bs-toggle="tooltip"
                                                title="Alasan: {{ $lastHistory->note ?? 'Ditolak' }}">
                                                <i class="bi bi-x-circle-fill me-1"></i> Rejected (Lvl
                                                {{ $rejectedLevel }})
                                            </span>
                                            <div class="timeline-stepper mt-1">
                                                @for ($i = 1; $i <= 6; $i++)
                                                    @if ($i < $rejectedLevel)
                                                        <div class="timeline-step completed"></div>
                                                        <div class="timeline-line completed"></div>
                                                    @elseif ($i == $rejectedLevel)
                                                        <div class="timeline-step rejected" data-bs-toggle="tooltip"
                                                            title="Ditolak di Level {{ $i }}"></div>
                                                        @if ($i < 6)
                                                            <div class="timeline-line"></div>
                                                        @endif
                                                    @else
                                                        <div class="timeline-step"></div>
                                                        @if ($i < 6)
                                                            <div class="timeline-line"></div>
                                                        @endif
                                                    @endif
                                                @endfor
                                            </div>

                                          
                                        @else
                                            @php
                                                $activeLevel = $request->current_approval_level ?? 1;
                                            @endphp
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-2 extra-small mb-1 d-inline-block">
                                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Lvl
                                                {{ $activeLevel }}
                                            </span>
                                            <div class="timeline-stepper mt-1">
                                                @for ($i = 1; $i <= 6; $i++)
                                                    @if ($i < $activeLevel)
                                                        <div class="timeline-step completed" data-bs-toggle="tooltip"
                                                            title="Lvl {{ $i }} Selesai"></div>
                                                        <div class="timeline-line completed"></div>
                                                    @elseif ($i == $activeLevel)
                                                        <div class="timeline-step active" data-bs-toggle="tooltip"
                                                            title="Posisi saat ini: Level {{ $i }}"></div>
                                                        @if ($i < 6)
                                                            <div class="timeline-line"></div>
                                                        @endif
                                                    @else
                                                        <div class="timeline-step" data-bs-toggle="tooltip"
                                                            title="Lvl {{ $i }}"></div>
                                                        @if ($i < 6)
                                                            <div class="timeline-line"></div>
                                                        @endif
                                                    @endif
                                                @endfor
                                            </div>
                                        @endif

                                    </td>

                                    <td class="px-4 py-3 text-end">

                                        <a href="{{ route('approvals.show', $request) }}"
                                            class="btn btn-outline-primary btn-sm px-3 py-1 rounded-2">
                                            <i class="bi bi-eye me-1"></i>
                                            {{ in_array($request->status, ['approved', 'rejected']) ? 'Detail' : 'Review' }}
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="px-4 py-5 text-center text-muted">

                                        <div class="py-4">

                                            <i
                                                class="bi bi-check2-circle fs-1 d-block mb-2 text-success opacity-50"></i>

                                            <p class="mb-1 fw-medium text-dark">
                                                Tidak ada data pengajuan.
                                            </p>

                                            <span class="extra-small text-muted">
                                                Belum ada data untuk status/filter yang dipilih.
                                            </span>

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if ($requests->hasPages())
                    <div class="card-footer bg-white border-top py-3 px-4">

                        {{ $requests->links() }}

                    </div>
                @endif

            </div>

        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>

</body>

</html>
