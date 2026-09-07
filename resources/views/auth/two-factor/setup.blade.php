<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Setup Google Authenticator - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-4">

    <div class="container" style="max-width: 450px;">
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">
            <div class="card-body">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <h1 class="h4 fw-bold text-dark mb-2">
                        Google Authenticator
                    </h1>
                    <p class="text-secondary small mb-0">
                        Aktifkan Two-Factor Authentication untuk mengamankan akun Anda.
                    </p>
                </div>

                {{-- Alert Success --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show text-start small mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

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

                {{-- Main Content --}}
                @if (isset($qrCodeUrl) && $qrCodeUrl)
                    <div class="text-center">

                        <p class="text-secondary small mb-3">
                            Scan QR Code berikut menggunakan aplikasi Google Authenticator.
                        </p>

                        {{-- QR Code Image --}}
                        <div class="d-flex justify-content-center mb-4">
                            <div class="p-2 border rounded-3 bg-white shadow-sm">
                                <img src="https://quickchart.io/qr?text={{ urlencode($qrCodeUrl) }}&size=200"
                                    alt="QR Code Google Authenticator" width="200" height="200"
                                    class="img-fluid d-block mx-auto">
                            </div>
                        </div>

                        <p class="text-secondary small mb-2">
                            Atau masukkan secret key secara manual:
                        </p>

                        {{-- Secret Key --}}
                        <div class="bg-light rounded-3 p-3 mb-4 border">
                            <code class="text-dark fw-bold font-monospace text-break">
                                {{ $secret ?? '-' }}
                            </code>
                        </div>

                        {{-- Form Verifikasi Kode --}}
                        <form method="POST" action="{{ route('2fa.enable') }}">
                            @csrf

                            <div class="mb-4 text-start">
                                <label for="code" class="form-label fw-semibold text-secondary small mb-1">
                                    Kode Google Authenticator
                                </label>

                                <input type="text" id="code" name="code" inputmode="numeric" maxlength="6"
                                    autocomplete="one-time-code"
                                    class="form-control text-center font-monospace fs-5 tracking-widest"
                                    placeholder="123456" required autofocus>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">
                                Aktifkan 2FA
                            </button>
                        </form>

                    </div>
                @else
                    {{-- Form Generate QR Code --}}
                    <div class="text-center">
                        <p class="text-secondary small mb-4">
                            Klik tombol di bawah ini untuk membuat QR Code autentikasi akun Anda.
                        </p>

                        <form method="POST" action="{{ route('2fa.generate') }}">
                            @csrf

                            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">
                                Generate QR Code
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
