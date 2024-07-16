@extends('layouts.main')

@section('title', 'Users - Wonokitri Tourism')

@section('main')
    <div class="container-fluid">
        @if ($errors->any())

            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach

        @endif
        <div class="card w-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">
                        Users
                    </h5>
                    <button class="btn btn-primary py-8 px-5 text-small d-flex gap-2 align-items-center rounded-2"
                        data-bs-toggle="modal" data-bs-target="#modalUsersCreate">
                        <i class="ti ti-plus"></i>
                        <span>Buat Users</span>
                    </button>

                    <div class="modal fade" id="modalUsersCreate" tabindex="-1" role="dialog"
                        aria-labelledby="modalUsersCreateTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form enctype="multipart/form-data" method="POST" actions="{{ route('users.create') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalUsersCreateTitle">Buat User</h5>
                                        <button type="button" class="close btn btn-error" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="mb-3 col-sm-12 col-md-6">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    aria-describedby="name" required>
                                            </div>
                                            <div class="mb-3 col-sm-12 col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    aria-describedby="email" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="mb-3 col-sm-12 col-md-6">
                                                <label for="description" class="form-label">Description</label>
                                                <input type="text" name="description" class="form-control"
                                                    id="description" aria-describedby="description" required>
                                            </div>
                                            <div class="mb-3 col-sm-12 col-md-6">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control" id="address"
                                                    aria-describedby="address" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                aria-describedby="password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="roles_id" class="form-label">Role</label>
                                            <select name="roles_id" class="form-select" id="roles_id"
                                                aria-describedby="roles_id" required>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">
                                                        {{ $role->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="profile" class="form-label">Profile</label>
                                            <input type="file" name="profile" class="form-control" id="profile"
                                                aria-describedby="profile" accept=".png,.jpeg,.jpg,.webp">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Buat</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Profile
                                </th>
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Deskripsi
                                </th>
                                <th>
                                    Akses
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <img src="{{ asset($user->profile ?? 'images/profile/no-images.png') }}"
                                            width="64" class="rounded" alt="{{ $user->name }}">
                                    </td>
                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        {{ $user->description }}
                                    </td>
                                    <td>
                                        {{ $user->roles->description }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button @if ($user->roles->id == 3) disabled @endif
                                                class="btn btn-primary dropdown-toggle" type="button"
                                                id="action-menu-{{ $user->id }}-button" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#modalUsersEdit{{ $user->id }}">
                                                        <span>Edit User</span>
                                                    </button>
                                                </li>
                                                <hr class="dropdown-divider">
                                                <form method="POST"
                                                    action="{{ route('users.delete', ['id' => $user->id]) }}">
                                                    @csrf
                                                    <button class="dropdown-item text-danger">
                                                        <span>Hapus Product</span>
                                                    </button>
                                                </form>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach()
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @foreach ($users as $user)
        <div class="modal fade" id="modalUsersEdit{{ $user->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalUsersEdit{{ $user->id }}Title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" method="POST"
                        action="{{ route('users.edit', ['id' => $user->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalUsersEdit{{ $user->id }}Title">Edit User</h5>
                            <button type="button" class="close btn btn-error" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" value="{{ $user->name }}" name="name"
                                        class="form-control" id="name" aria-describedby="name">
                                </div>
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" value="{{ $user->email }}" name="email"
                                        class="form-control" id="email" aria-describedby="email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" value="{{ $user->description }}" name="description"
                                        class="form-control" id="description" aria-describedby="description">
                                </div>
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" value="{{ $user->address }}" name="address"
                                        class="form-control" id="address" aria-describedby="address">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    aria-describedby="password">
                            </div>
                            <div class="mb-3">
                                <label for="roles_id" class="form-label">Role</label>
                                <select name="roles_id" class="form-select" id="roles_id" aria-describedby="roles_id">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $user->roles->id == $role->id ? 'selected' : '' }}>
                                            {{ $role->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="profile" class="form-label">Profile</label>
                                <input type="file" name="profile" class="form-control" id="profile"
                                    aria-describedby="profile">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection()
