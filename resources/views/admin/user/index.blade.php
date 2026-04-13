@extends('layouts.app')

@section('content')

<div class="mb-2 text-muted d-flex align-items-center gap-2">
    <a href="/admin/dashboard" class="text-muted text-decoration-none">
        <i class="fas fa-home"></i>
    </a>
    <span>></span>
    <span>User</span>
</div>

<h3 class="mb-4 fw-bold">
    <i class="text-primary"></i>User
</h3>

<div class="card p-4 shadow-sm">

    <div class="mb-3 text-end">
        <a href="/admin/user/create" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <span class="badge bg-{{ $item->role == 'admin' ? 'primary' : 'success' }}">
                                {{ ucfirst($item->role) }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                <!-- EDIT -->
                                <a href="/admin/user/{{ $item->id }}/edit" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <!-- DELETE BUTTON -->
                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                    data-id="{{ $item->id }}">
                                    Hapus
                                </button>

                                <!-- HIDDEN FORM -->
                                <form id="delete-form-{{ $item->id }}"
                                    action="/admin/user/{{ $item->id }}"
                                    method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Data kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection