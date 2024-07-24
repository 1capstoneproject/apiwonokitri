<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models;

class TransactionController extends Controller
{
    public $status = [
        // draft : status produk yang masih di dalam wishlist atau cart
        // inprogress : status produk yang menunggu di bayar
        // paid : status produk yang sudah di bayar
        // cancel : status produk yang dibatalkan
        // refund : status produk yang di refund
        // checkin: status transaksi user sudah checkout
        // onprogress: status transaksi user yang sedang berwisata
        // done : status produk yang selesai (sudah liburan).
        'checkin' => 'Checkin',
        'onprogress' => 'Sedang Berlibur',
        'done' => 'Selesaikan Transaksi'
    ];
   
    public function Transaction(Request $request){
        $transactions = Models\Transaction::where('tourism_id', $request->user()->id)->get();
        return response()->view("pages.transaction", [
            'transactions' => $transactions,
            'tx_status' => $this->status,
        ]);
    }
    
    public function UpdateStatusTransaction(Request $request, $id){
        try {
            $form = $request->validate([
                'status' => 'required|in:checkin,onprogress,done',
            ]);

            $tx = Models\Transaction::find($id);
            if(!$tx){
                return back()->withErrors([
                    "error" => "Transaksi tidak ditemukan",
                ]);
            }
            $tx->update($form);
            return back()->with("success", "Sukses update transaksi ".$tx->code." menjadi ".$tx->status);
        }catch(ValidationException $e){
            return back()->withErrors($e->validator->errors());
        }catch(\Exception $e){
            return back()->withErrors($e->getMessage());
        }
    }
}
