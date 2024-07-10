@extends('layouts.main')

@section('title', 'Dashboard - Wonokitri Tourism')

@section('main')
    <div class="container-fluid">
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
                                <form enctype="multipart/form-data">
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
                                            <label for="profiles" class="form-label">Profile</label>
                                            <input type="file" name="profiles" class="form-control" id="profiles"
                                                aria-describedby="profiles">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-primary">Buat</button>
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
                                    Name
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection()
