<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models;

class TransactionController extends Controller
{
    public function Transaction(Request $request){
        $transactions = Models\Transaction::where('tourism_id', $request->user()->id)->get();
        return response()->view("pages.transaction", [
            'transactions' => $transactions,
        ]);
    }
}
