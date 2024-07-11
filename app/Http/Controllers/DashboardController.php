<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;

class DashboardController extends Controller
{
    //
    public function Dashboard(Request $request){
        $totalUsers = Models\User::where('roles_id', 3)->count();
        $totalProducts = Models\Product::count();
        $totalBanners = Models\Banner::count();
        $totalTransactions = Models\Transaction::count();

        $totalUsersTransaction = Models\Transaction::where('tourism_id', $request->user()->id)->whereIn('status', ['paid', 'refund'])->sum('total');
        $totalUsersProduct = Models\Product::where('users_id', $request->user()->id)->where('is_package', 0)->count();
        $totalUsersPaket = Models\Product::where('users_id', $request->user()->id)->where('is_package', 1)->count();

        $recentUsersTransaction = Models\Transaction::where('tourism_id', $request->user()->id)->whereIn('status', ['paid', 'refund'])->orderBy('created_at', 'desc')->take(20)->get();
        return response()->view('pages.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalBanners',
            'totalTransactions',
            'totalUsersTransaction',
            'totalUsersProduct',
            'totalUsersPaket',
            'recentUsersTransaction',
        ));
    }
}
