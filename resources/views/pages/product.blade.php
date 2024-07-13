@extends('layouts.main')

@section('title', "Dashboard - Nengndi")

@section('main')
    <div class="container-fluid w-100">
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
                        Product Parawisata
                    </h5>
                    <button 
                        class="btn btn-primary py-8 px-5 text-small d-flex gap-2 align-items-center rounded-2"
                        data-bs-toggle="modal"
                        data-bs-target="#modaProductCreate"
                    >
                        <i class="ti ti-plus"></i> 
                        <span>Buat Product</span>
                    </button>
                    
                    <div class="modal fade" id="modaProductCreate" tabindex="-1" role="dialog" aria-labelledby="modaProductCreateTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data">
                                @csrf
                                @method("POST")
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modaProductCreateTitle">Create Product</h5>
                                    <button type="button" class="close btn btn-error" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-6">
                                        <div class="col-sm-12 col-md-6 mb-sm-6">
                                            <label for="name" class="form-label">Product Name</label>
                                            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" required>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="description" class="form-label">Product Deskripsi Singkat</label>
                                            <input type="text" name="description" class="form-control" id="description" aria-describedby="description" required>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-sm-12 col-md-6 mb-sm-6">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" name="price" class="form-control" id="price" aria-describedby="price" required>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="min_order" class="form-label">Min Order</label>
                                            <input type="number" name="min_order" class="form-control" id="min_order" aria-describedby="min_order" required>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-sm-12 col-md-6 mb-sm-6">
                                            <label for="duration" class="form-label">Durasi</label>
                                            <input type="text" name="duration" class="form-control" id="duration" aria-describedby="duration" required>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="location" class="form-label">Lokasi</label>
                                            <input type="text" name="location" class="form-control" id="location" aria-describedby="location" required>
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <label for="description_details" class="form-label">Deskripsi Detail</label>
                                        <textarea rows="3" name="description_details" class="form-control" id="editor" aria-describedby="description_details">
                                            <p></p>
                                        </textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Buat Product</button>
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
                                    Name
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Durasi
                                </th>
                                <th>
                                    Harga
                                </th>
                                <th>
                                    Min Order
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Event
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $productCount = 1;
                            @endphp
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $productCount }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->duration }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->min_order }}</td>
                                    <td>{{ $product->is_package ? "Paket" : "Product" }}</td>
                                    <td>{{ $product->is_event ? "Event" : "Tidak" }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="action-menu-{{ $product->id }}-button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu" >
                                                <li>
                                                    <button
                                                        class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalAddImage{{$product->id}}"
                                                    >
                                                        <span>Tambah Gambar</span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button
                                                        class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalViewImage{{$product->id}}"
                                                    >
                                                        <span>Lihat Gambar</span>
                                                    </button>
                                                </li>
                                                <hr class="dropdown-divider">
                                                <button
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalProductEdit{{$product->id}}"
                                                >
                                                    <span>Edit Product</span>
                                                </button>
                                                <hr class="dropdown-divider">
                                                <form method="POST" action="{{ route('product.toggle.event', ['id' => $product->id])}}">
                                                    @csrf
                                                    @method("PUT")
                                                    <button
                                                        class="dropdown-item"
                                                        
                                                    >
                                                        <span>Beralih @if($product->is_event) Non Event @else Event @endif</span>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('product.toggle.package', ['id' => $product->id])}}">
                                                    @csrf
                                                    @method("PUT")
                                                    <button
                                                        class="dropdown-item"
                                                        
                                                    >
                                                        <span>Beralih @if($product->is_package) Non Paket @else Paket @endif</span>
                                                    </button>
                                                </form>
                                                <hr class="dropdown-divider">
                                                <form method="POST" action="{{ route('product.delete', ['id' => $product->id])}}">
                                                    @csrf
                                                    <button
                                                        class="dropdown-item text-danger"
                                                        
                                                    >
                                                        <span>Hapus Product</span>
                                                    </button>
                                                </form>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $productCount++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    
    @foreach($products as $product)
        <div class="modal fade" id="modalViewImage{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="modalViewImage{{$product->id}}Title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalViewImage{{$product->id}}Title">Image {{ $product->name }}</h5>
                        <button type="button" class="close btn btn-error" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                </th>
                                <tbody>
                                    @foreach($product->ImagesIds as $image)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('/storage/'.$image->path) }}" width="320" class="rounded" alt="{{ $image->name }}">
                                            </td>
                                            <td>
                                                <form method="POST" enctype="multipart/form-data" action="{{ route('product.image.delete', ['id' => $image->id]) }}">
                                                @csrf
                                                @method('POST')
                                                    <button class="btn btn-danger">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($products as $product)
        <div class="modal fade" id="modalAddImage{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="modalAddImage{{$product->id}}Title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('product.image.add', ['id' => $product->id]) }}">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddImage{{$product->id}}Title">Tambah Image {{ $product->name }}</h5>
                        <button type="button" class="close btn btn-error" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Product Image</label>
                            <input type="file" name="files[]" class="form-control" id="files" aria-describedby="files" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Gambar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($products as $product)
        <div class="modal fade" id="modalProductEdit{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="modalProductEdit{{$product->id}}Title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('product.edit', ['id' => $product->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProductEdit{{$product->id}}Title">Edit {{ $product->name }}</h5>
                        <button type="button" class="close btn btn-error" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-6">
                            <div class="col-sm-12 col-md-6 mb-sm-6">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" name="name" value="{{ $product->name}}" class="form-control" id="name" aria-describedby="name" required>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="description" class="form-label">Product Deskripsi Singkat</label>
                                <input type="text" name="description" value="{{ $product->description}}" class="form-control" id="description" aria-describedby="description" required>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-sm-12 col-md-6 mb-sm-6">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" value="{{ $product->price}}" class="form-control" id="price" aria-describedby="price" required>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="min_order" class="form-label">Min Order</label>
                                <input type="number" name="min_order" value="{{ $product->min_order}}" class="form-control" id="min_order" aria-describedby="min_order" required>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-sm-12 col-md-6 mb-sm-6">
                                <label for="duration" class="form-label">Durasi</label>
                                <input type="text" name="duration" value="{{ $product->duration}}" class="form-control" id="duration" aria-describedby="duration" required>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" name="location" value="{{ $product->location}}" class="form-control" id="location" aria-describedby="location" required>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="description_details" class="form-label">Deskripsi Detail</label>
                            <textarea rows="3" name="description_details" class="form-control" id="editor" aria-describedby="description_details">
                                {{ $product->description_details}}
                            </textarea>
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


@section("extra-js")
<script src="{{ asset('/js/tinymce/tinymce.min.js') }} "></script>
<script>
    $(document).ready(function(){
        tinymce.init({
            selector: 'textarea#editor',
            plugins: 'lists',
            toolbar: 'undo redo bold italic styles alignleft aligncenter alignright justify numlist bullist decreaseindent increaseindent ',
            lists_indent_on_tab: true
        });
    });
</script>
@endsection()