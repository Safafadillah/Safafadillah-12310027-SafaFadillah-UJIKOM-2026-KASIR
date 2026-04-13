<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('{{ asset('images/kasir.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .overlay {
            background: rgba(0, 0, 0, 0.5);
            height: 100vh;
        }
    </style>
</head>

<body>

    <div class="overlay d-flex justify-content-center align-items-center">
        <div class="card p-4 shadow" style="width: 400px; background-color: #b3e5fc83;">
            <h4 class="mb-3 text-center">Login</h4>
            <form method="POST" action="/login">
                @csrf

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <div class="mb-3">
                    <button class="btn btn-primary w-100">Login</button>
                </div>
                <div class="mt-3">
                    <a href="{{ url('/') }}" class="btn btn-dark w-100">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>