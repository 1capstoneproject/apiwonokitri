@extends('layouts.main')

@section('title', "Transaksi - Wonokitri Tourism")

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
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <a href="#">
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
@endsection()
