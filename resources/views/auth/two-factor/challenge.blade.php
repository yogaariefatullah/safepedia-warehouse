<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi 2FA - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100 d-flex align-items-center justify-center py-4">

    <div class="container" style="max-width: 420px;">
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">
            <div class="card-body">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <h1 class="h4 fw-bold text-dark mb-2">
                        Verifikasi 2FA
                    </h1>
                    <p class="text-secondary small mb-0">
                        Masukkan 6 digit kode dari Google Authenticator.
                    </p>
                </div>

                {{-- Alert Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger text-start small mb-4" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form Verifikasi Kode --}}
                <form method="POST" action="{{ route('2fa.verify') }}">
                    @csrf

                    <div class="mb-4 text-start">
                        <label for="code" class="form-label fw-semibold text-secondary small mb-1">
                            Kode Authenticator
                        </label>

                        <input type="text" id="code" name="code" inputmode="numeric" maxlength="6"
                            autocomplete="one-time-code"
                            class="form-control text-center font-monospace fs-4 tracking-widest py-2"
                            placeholder="000000" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm mb-3">
                        Verifikasi
                    </button>
                </form>

                {{-- Form Logout / Kembali --}}
                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf

                    <button type="submit" class="btn btn-link text-decoration-none text-secondary small p-0">
                        Kembali ke Login
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
