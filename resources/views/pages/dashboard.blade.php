@extends('layouts.main')

@section('title', "Dashboard - Nengndi")

@section('main')
    <div class="container-fluid">
        @if(auth()->user()->hasRole('base.role_superadmin'))
        <div class="row">
            <div class="col-lg-8 d-flex align-items-strech">
            </div> 
        </div>
        @endif

        @if(auth()->user()->hasRole('base.role_admin'))
            <div class="row">
                <div class="col-lg-4 d-flex align-items-strech">

                    <div class="card w-100 overflow-hidden">
                        <div class="card-body p-4">
                            <p class="card-title mb-9 fw-semibold">
                                Total Transaksi
                            </p>
                            <h4 class="fw-semibold mb-3 gap-3" style="display: flex; flex-direction: row; align-items: center;">
                                <i class="ti ti-moneybag" style="font-size: 32px"></i>
                                Rp. {{ number_format($totalUsersTransaction, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                
                </div>
                <div class="col-lg-4 d-flex align-items-strech">

                    <div class="card w-100 overflow-hidden">
                        <div class="card-body p-4">
                            <p class="card-title mb-9 fw-semibold">
                                Total Produk
                            </p>
                            <h4 class="fw-semibold mb-3 gap-3" style="display: flex; flex-direction: row; align-items: center;">
                                <i class="ti ti-box" style="font-size: 32px"></i>
                                {{ $totalUsersProduct }}
                            </h4>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 d-flex align-items-strech">

                    <div class="card w-100 overflow-hidden">
                        <div class="card-body p-4">
                            <p class="card-title mb-9 fw-semibold">
                                Total Paket
                            </p>
                            <h4 class="fw-semibold mb-3 gap-3" style="display: flex; flex-direction: row; align-items: center;">
                                <i class="ti ti-box" style="font-size: 32px"></i>
                                {{ $totalUsersPaket }}
                            </h4>
                        </div>
                    </div>

                </div> 
            </div>
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="card w-100 overflow-hidden">
                        <div class="card-body p-4">
                            <p class="card-title mb-9 fw-semibold">
                                Transaksi Terahir
                            </p>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentUsersTransaction as $transaction)
                                            <tr>
                                                <td>{{ loop()->iteration() }}</td>
                                                <td>
                                                    {{ $transaction->code }}
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection()
