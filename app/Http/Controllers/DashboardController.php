<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::count();
        $produk = Produk::count();
        $supplier = Supplier::count();
        $member = Member::count();
        $penjualan = Penjualan::sum('diterima');
        $pengeluaran = Pengeluaran::sum('nominal');
        $pembelian = Pembelian::sum('bayar');

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        $tanggal_awal = date('Y-m-01');

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact('kategori', 'produk', 'supplier', 'member', 'penjualan', 'pengeluaran', 'pembelian', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        } elseif (auth()->user()->level == 2) {
            $userId = auth()->id();
            $today = date('Y-m-d');

            $todaySales = Penjualan::where('id_user', $userId)
                ->whereDate('created_at', $today)
                ->where('bayar', '>', 0)
                ->sum('bayar');

            $todayTxCount = Penjualan::where('id_user', $userId)
                ->whereDate('created_at', $today)
                ->count();

            $recentSales = Penjualan::with('member')
                ->where('id_user', $userId)
                ->orderBy('id_penjualan', 'desc')
                ->limit(5)
                ->get();

            $bestProducts = PenjualanDetail::select('penjualan_detail.id_produk', 'produk.nama_produk', DB::raw('SUM(penjualan_detail.jumlah) as qty'))
                ->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
                ->join('produk', 'produk.id_produk', '=', 'penjualan_detail.id_produk')
                ->where('penjualan.id_user', $userId)
                ->whereDate('penjualan.created_at', $today)
                ->groupBy('penjualan_detail.id_produk', 'produk.nama_produk')
                ->orderByDesc('qty')
                ->limit(5)
                ->get();

            $lowStock = Produk::where('stok', '<=', 5)
                ->orderBy('stok')
                ->limit(5)
                ->get();

            return view('kasir.dashboard', compact('todaySales', 'todayTxCount', 'recentSales', 'bestProducts', 'lowStock'));
        } elseif (auth()->user()->level == 3) {
            $userId = auth()->id();
            $today = date('Y-m-d');
            
            // Calculate today's personal sales total (completed transactions)
            $todaySales = Penjualan::where('id_user', $userId)
                ->whereDate('created_at', $today)
                ->where('bayar', '>', 0)
                ->sum('bayar');

            // Count today's personal transactions
            $todayTxCount = Penjualan::where('id_user', $userId)
                ->whereDate('created_at', $today)
                ->where('bayar', '>', 0)
                ->count();

            // Get active transactions for this cashier (pending payment)
            $activeTransactions = Penjualan::where('id_user', $userId)
                ->where('bayar', 0)
                ->count();

            // Get recent personal sales
            $recentSales = Penjualan::with('member')
                ->where('id_user', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Get personal best-selling products today
            $bestProducts = PenjualanDetail::select(
                    'penjualan_detail.id_produk',
                    'produk.nama_produk',
                    DB::raw('SUM(penjualan_detail.jumlah) as qty')
                )
                ->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
                ->join('produk', 'produk.id_produk', '=', 'penjualan_detail.id_produk')
                ->where('penjualan.id_user', $userId)
                ->whereDate('penjualan.created_at', $today)
                ->groupBy('penjualan_detail.id_produk', 'produk.nama_produk')
                ->orderByDesc('qty')
                ->limit(5)
                ->get();

            // Get products with low stock
            $lowStockCount = Produk::where('stok', '<=', 5)->count();
            $lowStock = Produk::where('stok', '<=', 5)
                ->orderBy('stok')
                ->limit(5)
                ->get();

            // Get recent sales
            $recentSales = Penjualan::with('member')
                ->orderBy('id_penjualan', 'desc')
                ->limit(5)
                ->get();

            // Get best selling products
            $bestProducts = PenjualanDetail::select('penjualan_detail.id_produk', 'produk.nama_produk', DB::raw('SUM(penjualan_detail.jumlah) as qty'))
                ->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
                ->join('produk', 'produk.id_produk', '=', 'penjualan_detail.id_produk')
                ->whereDate('penjualan.created_at', $today)
                ->groupBy('penjualan_detail.id_produk', 'produk.nama_produk')
                ->orderByDesc('qty')
                ->limit(5)
                ->get();

            return view('manager.dashboard', compact(
                'todaySales',
                'todayTxCount',
                'activeTransactions',
                'lowStockCount',
                'recentSales',
                'bestProducts',
                'lowStock'
            ));
        } else {
            return view('customer.dashboard');
        }
    }
}
// visit "codeastro" for more projects!