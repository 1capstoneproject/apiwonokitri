@extends('layouts.main')

@section('title', 'Dashboard - Wonokitri Tourism')

@section('main')
    <div class="container-fluid">
        <!-- PR HANDLE ERROR -->
        <div class="card w-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">
                        Media Banner
                    </h5>
                    <button class="btn btn-primary py-8 px-5 text-small d-flex gap-2 align-items-center rounded-2"
                        data-bs-toggle="modal" data-bs-target="#modalBannerCreate">
                        <i class="ti ti-plus"></i>
                        <span>Buat Banner</span>
                    </button>
                    <div class="modal fade" id="modalBannerCreate" tabindex="-1" role="dialog"
                        aria-labelledby="modalBannerCreateTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalBannerCreateTitle">Buat Banner</h5>
                                        <button type="button" class="close btn btn-error" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Banner Name</label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                aria-describedby="name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Banner Image</label>
                                            <input type="file" name="files" class="form-control" id="files"
                                                aria-describedby="files">
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
                                    Image
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $bannerCount = 1;
                            @endphp
                            @foreach ($banners as $banner)
                                <tr>
                                    <td>{{ $bannerCount }}</td>
                                    <td>
                                        <img src="{{ asset($banner->path) }}" width="320" class="rounded"
                                            alt="{{ $banner->name }}">
                                    </td>
                                    <td>{{ $banner->name }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="action-menu-{{ $banner->id }}-button" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Aksi
                                            </button>
                                            <div class="dropdown-menu"
                                                aria-labelledby="action-menu-{{ $banner->id }}-button">
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#modalBannerEdit{{ $banner->id }}">
                                                    <span>Edit</span>
                                                </button>

                                                <form method="post"
                                                    action="{{ route('banner.delete', ['id' => $banner->id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        href="#">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $bannerCount++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @foreach ($banners as $banner)
        <div class="modal fade" id="modalBannerEdit{{ $banner->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalBannerEdit{{ $banner->id }}Title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('banner.edit', ['id' => $banner->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalBannerEdit{{ $banner->id }}Title">Create Banner</h5>
                            <button type="button" class="close btn btn-error" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Banner Name</label>
                                <input type="text" name="name" value="{{ $banner->name }}" class="form-control"
                                    id="name" aria-describedby="name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Banner Image</label>
                                <input type="file" name="files" class="form-control" id="files"
                                    aria-describedby="files">
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
