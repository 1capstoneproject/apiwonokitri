<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;

class DashboardController extends Controller
{
    //
    public function Dashboard(Request $request){
        // admin dashboard variable
        $totalUsersTransaction = Models\Transaction::where('tourism_id', $request->user()->id)->whereIn('status', ['paid', 'refund'])->sum('total');
        $totalUsersProduct = Models\Product::where('users_id', $request->user()->id)->where('is_package', 0)->count();
        $totalUsersPaket = Models\Product::where('users_id', $request->user()->id)->where('is_package', 1)->count();

        $recentUsersTransaction = Models\Transaction::where('tourism_id', $request->user()->id)->whereIn('status', ['paid', 'refund', 'inprogress'])->orderBy('created_at', 'desc')->take(20)->get();

        // superadmin dashboard variable
        $totalSuperadminTransaction = Models\Transaction::whereIn('status', ['paid', 'refund'])->sum('total');
        $totalSuperadminAllUsers = Models\User::where('roles_id', 3)->count();
        $totalSuperadminAllAdmin = Models\User::where('roles_id', 2)->count();
        $totalSuperadminTopProductTerlaris = [];
        $transaksiAll = Models\Transaction::where('status', 'paid')->get();
        foreach($transaksiAll as $tx){
            $totalSuperadminTopProductTerlaris[$tx->product->id] = $tx->product;
        }
        $totalSuperadminProductEvent = Models\Product::where('is_event', 1)->get();

        return response()->view('pages.dashboard', compact(
            'totalUsersTransaction',
            'totalUsersProduct',
            'totalUsersPaket',
            'recentUsersTransaction',
            // superadmin
            'totalSuperadminTransaction',
            'totalSuperadminAllUsers',
            'totalSuperadminAllAdmin',
            'totalSuperadminTopProductTerlaris',
            'totalSuperadminProductEvent',
        ));
    }
}
