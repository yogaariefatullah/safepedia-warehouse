<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>User Management - {{ config('app.name', 'Safepedia') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
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
    </style>
</head>

<body class="bg-light min-vh-100">

    <!-- Header / Navbar -->
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
                            User Management
                        </h2>

                        <p class="text-muted extra-small mb-0">
                            Kelola pengguna terdaftar, perbarui data akun, dan atribusi role/hak akses.
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

    <!-- Main Content -->
    <main class="py-4">

        <div class="container-fluid max-w-7xl px-3 px-lg-4">

            <!-- Flash Alert Messages -->
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Validation Error Alert (Gagal saat simpan edit) -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3"
                    role="alert">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                        <strong class="me-auto">Terdapat kesalahan pengisian data:</strong>
                    </div>
                    <ul class="mb-0 extra-small ps-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Header Card Info & Action -->
            <div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-3 p-3">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div>
                                <span class="text-muted extra-small d-block text-uppercase fw-semibold">
                                    ADMINISTRASI PENGGUNA
                                </span>
                                <h5 class="fw-bold text-dark mb-0">
                                    Daftar Pengguna Sistem ({{ $users->total() }})
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card border-0 bg-white rounded-3 shadow-sm overflow-hidden">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold" style="width: 5%;">
                                    No</th>
                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">Nama Lengkap</th>
                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">Alamat Email</th>
                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">Role / Hak Akses
                                </th>
                                <th class="px-4 py-3 text-muted extra-small text-uppercase fw-bold">Tanggal Terdaftar
                                </th>
                                <th class="px-4 py-3 text-end text-muted extra-small text-uppercase fw-bold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr class="border-bottom">

                                    <!-- Index Number -->
                                    <td class="px-4 py-3 text-muted extra-small fw-semibold">
                                        {{ $users->firstItem() + $index }}
                                    </td>

                                    <!-- Nama User -->
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center font-weight-bold border"
                                                style="width: 32px; height: 32px; font-size: 0.85rem;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block small mb-0">
                                                    {{ $user->name }}
                                                </span>
                                                @if (auth()->id() === $user->id)
                                                    <span
                                                        class="badge bg-primary-subtle text-primary border border-primary-subtle extra-small">
                                                        Akun Anda
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email -->
                                    <td class="px-4 py-3">
                                        <span class="text-dark small font-monospace">
                                            {{ $user->email }}
                                        </span>
                                    </td>

                                    <!-- Role Badge -->
                                    <td class="px-4 py-3">
                                        @php
                                            $roleSlug = $user->role->slug ?? ($user->role->name ?? '');
                                        @endphp
                                        <span
                                            class="badge {{ $roleSlug === 'admin' ? 'bg-danger-subtle text-danger border-danger-subtle' : ($roleSlug === 'requestor' ? 'bg-info-subtle text-info border-info-subtle' : 'bg-success-subtle text-success border-success-subtle') }} border px-2.5 py-1.5 rounded-2 extra-small fw-semibold">
                                            <i class="bi bi-shield-lock me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $user->role->name ?? 'No Role')) }}
                                        </span>
                                    </td>

                                    <!-- Tanggal Terdaftar -->
                                    <td class="px-4 py-3">
                                        <span class="text-muted extra-small">
                                            {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                                        </span>
                                    </td>

                                    <!-- Action Button (Edit Modal Trigger) -->
                                    <td class="px-4 py-3 text-end">
                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm px-3 py-1 rounded-2"
                                            data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>
                                    </td>

                                </tr>

                                <!-- Modal Edit User untuk User ID ini -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form action="{{ route('usermanagement.update', $user) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header bg-light py-3">
                                                    <h5 class="modal-title font-weight-bold h6 mb-0"
                                                        id="editUserModalLabel{{ $user->id }}">
                                                        <i class="bi bi-person-gear me-1 text-primary"></i> Edit Data
                                                        User: {{ $user->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body text-start p-4">

                                                    <!-- Nama Lengkap -->
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label font-weight-semibold small text-dark">Nama
                                                            Lengkap</label>
                                                        <input type="text" name="name"
                                                            class="form-control form-control-sm rounded-2"
                                                            value="{{ old('name', $user->name) }}" required>
                                                    </div>

                                                    <!-- Alamat Email -->
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label font-weight-semibold small text-dark">Alamat
                                                            Email</label>
                                                        <input type="email" name="email"
                                                            class="form-control form-control-sm rounded-2"
                                                            value="{{ old('email', $user->email) }}" required>
                                                    </div>

                                                    <!-- Role Selection -->
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label font-weight-semibold small text-dark">Role
                                                            / Hak Akses</label>
                                                        <select name="role_id"
                                                            class="form-select form-select-sm rounded-2" required>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <hr class="my-3 border-light-subtle">

                                                    <!-- Password Baru (Opsional) -->
                                                    <div class="mb-3">
                                                        <label class="form-label font-weight-semibold small text-dark">
                                                            Password Baru <span
                                                                class="text-muted extra-small fw-normal">(Kosongkan
                                                                jika tidak ingin diubah)</span>
                                                        </label>
                                                        <input type="password" name="password"
                                                            class="form-control form-control-sm rounded-2"
                                                            placeholder="Masukkan password baru minimal 8 karakter">
                                                    </div>

                                                    <!-- Konfirmasi Password Baru -->
                                                    <div class="mb-0">
                                                        <label
                                                            class="form-label font-weight-semibold small text-dark">Konfirmasi
                                                            Password Baru</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control form-control-sm rounded-2"
                                                            placeholder="Ulangi password baru">
                                                    </div>

                                                </div>

                                                <div class="modal-footer bg-light py-2">
                                                    <button type="button"
                                                        class="btn btn-light btn-sm font-weight-semibold px-3"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm font-weight-bold px-3">
                                                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->

                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-5 text-center text-muted">
                                        <div class="py-4">
                                            <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>
                                            <p class="mb-0 fw-medium text-dark">Belum ada pengguna terdaftar.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

                <!-- Pagination Footer -->
                @if ($users->hasPages())
                    <div class="card-footer bg-white border-top py-3 px-4">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>

        </div>

    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
