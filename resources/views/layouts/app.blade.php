<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kasir UKK</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 untuk icon lebih bagus -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0d1b2a;
            position: fixed;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar .brand {
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar .brand h4 {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .sidebar .brand h4 i {
            color: #4c9aff;
            margin-right: 8px;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            margin: 4px 12px;
            border-radius: 12px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .sidebar a i {
            width: 22px;
            font-size: 1.1rem;
        }

        .sidebar a:hover {
            background: #1b263b;
            color: white;
            transform: translateX(4px);
        }

        .content {
            margin-left: 260px;
            padding: 24px 32px;
        }

        .card {
            border-radius: 20px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08);
        }

        .sidebar a.active,
        .sidebar a.bg-primary {
            background: #2a6df4;
            color: white;
            border-radius: 12px;
        }

        .sidebar a.logout-link {
            background-color: #dc3545;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div>
            <div class="brand text-center">
                <h4>
                    <i class="fas fa-cash-register"></i>
                    Kasir S-App
                </h4>
            </div>

            {{-- DASHBOARD --}}
            <a href="{{ Auth::user()->role == 'admin' ? '/admin/dashboard' : '/petugas/dashboard' }}"
                class="{{ request()->is(Auth::user()->role . '/dashboard') ? 'bg-primary text-white' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>

            {{-- PRODUK --}}
            <a href="{{ Auth::user()->role == 'admin' ? '/admin/produk' : '/petugas/produk' }}"
                class="{{ request()->is(Auth::user()->role . '/produk*') ? 'bg-primary text-white' : '' }}">
                <i class="fas fa-boxes"></i> Produk
            </a>

            {{-- PEMBELIAN --}}
            <a href="{{ Auth::user()->role == 'admin' ? '/admin/pembelian' : '/petugas/pembelian' }}"
                class="{{ request()->is(Auth::user()->role . '/pembelian*') ? 'bg-primary text-white' : '' }}">
                <i class="fas fa-shopping-cart"></i> Penjualan
            </a>

            {{-- KHUSUS ADMIN --}}
            @if (Auth::user()->role == 'admin')
                <a href="/admin/user" class="{{ request()->is('admin/user*') ? 'bg-primary text-white' : '' }}">
                    <i class="fas fa-users"></i> User
                </a>
            @endif
        </div>

        {{-- 🔥 LOGOUT DI PALING BAWAH --}}
        <div class="mt-auto mb-3">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <a href="#" class="sidebar-link logout-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </form>
        </div>

    </div>

    <!-- CONTENT -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Yakin ingin hapus?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6c5ce7',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success_delete'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success_delete')),
                    confirmButtonColor: '#6c5ce7'
                });
            @endif
        });
    </script>

    {{-- ✅ INI YANG PALING PENTING --}}
    @stack('scripts')

</body>

</html>
