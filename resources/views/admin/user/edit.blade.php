@extends('layouts.app')

@section('content')

<div class="mb-2 text-muted d-flex align-items-center gap-2">
    <a href="/admin/dashboard" class="text-muted text-decoration-none"><i class="fas fa-home"></i></a>
    <span>></span>
    <a href="/admin/user" class="text-muted text-decoration-none">User</a>
    <span>></span>
    <span>Edit User</span>
</div>

<h3 class="mb-4 fw-bold">
    <i class="text-primary"></i>Edit User
</h3>

<div class="card p-4 shadow-sm">

<form action="/admin/user/{{ $user->id }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-control @error('role') is-invalid @enderror">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Password <span class="text-muted">(opsional)</span></label>
            <input type="password" name="password" class="form-control">
        </div>

    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="/admin/user" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Update
        </button>
    </div>

</form>

</div>

@endsection