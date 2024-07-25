@extends('layouts.main')

@section('title', 'Transaksi - Wonokitri Tourism')

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
                        Transaksi
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Kode
                                </th>
                                <th>
                                    Customer
                                </th>
                                <th>
                                    Product
                                </th>
                                <th>
                                    Status Transaksi
                                </th>
                                <th>
                                    Tanggal
                                </th>
                                <th>
                                    Durasi
                                </th>
                                <th>
                                    Metode Pembayaran
                                </th>
                                <th>
                                    Harga
                                </th>
                                <th>
                                    Jumlah
                                </th>
                                <th>
                                    Total
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                         <a 
                                            href="#edit{{$transaction->id}}"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modelViewTransaction{{$transaction->id}}"
                                        >
                                            {{ $transaction->code }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $transaction->Customer->name }}
                                    </td>
                                    <td>
                                        {{ $transaction->Product->name }}
                                    </td>
                                    <td>
                                        {{ $transaction->status }}
                                    </td>
                                    <td>
                                        {{ $transaction->created_at }}
                                    </td>
                                    <td>
                                        {{ $transaction->duration }}
                                    </td>
                                    <td>
                                        {{ $transaction->payment_method }}
                                    </td>
                                    <td>
                                        {{ $transaction->price }}
                                    </td>
                                    <td>
                                        {{ $transaction->quantity }}
                                    </td>
                                    <td>
                                        {{ $transaction->total }}
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-danger">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @foreach($transactions as $transaction)
    <div class="modal fade" id="modelViewTransaction{{$transaction->id}}" tabindex="-1" role="dialog" aria-labelledby="modelViewTransaction{{$transaction->id}}Title" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" action="{{ route('transaction.update', ['id' => $transaction->id]) }}">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" id="modelViewTransaction{{$transaction->id}}Title">{{ $transaction->code }}</h5>
                    <button type="button" class="close btn btn-error" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive pb-6">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th>
                                        Nama Product
                                    </th>
                                    <th>
                                        Nama Kontak
                                    </th>
                                    <th>
                                        No HP
                                    </th>
                                    <th>
                                        Status Pembayaran
                                    </th>
                                    <th>
                                        Jumlah Rombongan
                                    </th>
                                    <th>
                                        Jadwal Liburan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $transaction->product->name }}
                                    </td>
                                    <td>
                                        {{ $transaction->Customer->name }}
                                    </td>
                                    <td>
                                        {{ '+62'.$transaction->Customer->phone }}
                                    </td>
                                    <td>
                                        {{ $transaction->status }}
                                    </td>
                                    <td>
                                        {{ $transaction->quantity }}
                                    </td>
                                    <td>
                                        {{ $transaction->date }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Transaksi</label>
                        <select type="text" name="status" class="form-control" id="status" aria-describedby="status" @if(!in_array($transaction->status, ['paid', 'onprogress', 'checkin']))) disabled @endif>
                            @if(in_array($transaction->status, ['paid', 'onprogress', 'checkin']))
                                @foreach($tx_status as $id => $status)
                                    <option value="{{$id}}" @if($transaction->status == $id) checked @endif>{{$status}}</option>
                                @endforeach
                            @else
                                <option>{{$transaction->status}}</option>
                            @endif
                        </select>
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
