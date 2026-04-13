<!DOCTYPE html>
<html>
<head>
    <title>404</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
        }
        .error-container {
            height: 100vh;
        }
        img {
            max-width: 300px;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center error-container">
    <div class="text-center">

        <!-- GANTI DENGAN GAMBAR KAMU -->
        <img src="https://cdn-icons-png.flaticon.com/512/6195/6195678.png" alt="404">

        <h4 class="mt-4">You can't access this page.</h4>

        @if(Auth::check())
            @if(Auth::user()->role === 'admin')
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary mt-3">
                    Back
                </a>
            @elseif(Auth::user()->role === 'petugas')
                <a href="{{ url('/petugas/dashboard') }}" class="btn btn-primary mt-3">
                    Back
                </a>
            @else
                <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                    Back
                </a> 
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                Back
            </a>
        @endif

    </div>
</div>

</body>
</html>