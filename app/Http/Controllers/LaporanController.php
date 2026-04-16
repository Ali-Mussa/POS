<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use PDF;

class LaporanController extends Controller
{
    // ─── Reports Hub ─────────────────────────────────────────────────────────

    public function hub()
    {
        $today = date('Y-m-d');
        $monthStart = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));

        $totalSalesMonth   = Penjualan::where('created_at', 'LIKE', "%".date('Y-m')."%")->where('bayar', '>', 0)->sum('bayar');
        $totalExpMonth     = Pengeluaran::where('created_at', 'LIKE', "%".date('Y-m')."%")->sum('nominal');
        $totalTxMonth      = Penjualan::where('created_at', 'LIKE', "%".date('Y-m')."%")->where('bayar', '>', 0)->count();
        $totalProducts     = Produk::count();

        return view('laporan.hub', compact(
            'totalSalesMonth', 'totalExpMonth', 'totalTxMonth', 'totalProducts', 'monthStart', 'today'
        ));
    }

    // ─── Periodic Reports (Daily, Weekly, Monthly, Annual) ───────────────────────

    public function daily()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        
        // Today's data
        $todaySales = Penjualan::whereDate('created_at', $today)->where('bayar', '>', 0)->get();
        $todayExpenses = Pengeluaran::whereDate('created_at', $today)->get();
        $todayPurchases = Pembelian::whereDate('created_at', $today)->get();
        
        // Yesterday's data for comparison
        $yesterdaySales = Penjualan::whereDate('created_at', $yesterday)->where('bayar', '>', 0)->sum('bayar');
        $yesterdayExpenses = Pengeluaran::whereDate('created_at', $yesterday)->sum('nominal');
        
        // Today's totals
        $totalTodaySales = $todaySales->sum('bayar');
        $totalTodayExpenses = $todayExpenses->sum('nominal');
        $totalTodayPurchases = $todayPurchases->sum('total_harga');
        $totalTodayTransactions = $todaySales->count();
        $netIncomeToday = $totalTodaySales - $totalTodayExpenses;
        
        // Comparison percentages
        $salesChange = $yesterdaySales > 0 ? (($totalTodaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;
        $expensesChange = $yesterdayExpenses > 0 ? (($totalTodayExpenses - $yesterdayExpenses) / $yesterdayExpenses) * 100 : 0;
        
        return view('laporan.daily', compact(
            'today', 'yesterday',
            'todaySales', 'todayExpenses', 'todayPurchases',
            'totalTodaySales', 'totalTodayExpenses', 'totalTodayPurchases',
            'totalTodayTransactions', 'netIncomeToday',
            'salesChange', 'expensesChange'
        ));
    }
    
    public function weekly()
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        $startOfLastWeek = date('Y-m-d', strtotime('monday last week'));
        $endOfLastWeek = date('Y-m-d', strtotime('sunday last week'));
        
        // This week's data
        $weeklySales = Penjualan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('bayar', '>', 0)->get();
        $weeklyExpenses = Pengeluaran::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        $weeklyPurchases = Pembelian::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        
        // Last week's data for comparison
        $lastWeekSales = Penjualan::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->where('bayar', '>', 0)->sum('bayar');
        $lastWeekExpenses = Pengeluaran::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->sum('nominal');
        
        // This week's totals
        $totalWeeklySales = $weeklySales->sum('bayar');
        $totalWeeklyExpenses = $weeklyExpenses->sum('nominal');
        $totalWeeklyPurchases = $weeklyPurchases->sum('total_harga');
        $totalWeeklyTransactions = $weeklySales->count();
        $netIncomeWeekly = $totalWeeklySales - $totalWeeklyExpenses;
        
        // Comparison percentages
        $salesChange = $lastWeekSales > 0 ? (($totalWeeklySales - $lastWeekSales) / $lastWeekSales) * 100 : 0;
        $expensesChange = $lastWeekExpenses > 0 ? (($totalWeeklyExpenses - $lastWeekExpenses) / $lastWeekExpenses) * 100 : 0;
        
        return view('laporan.weekly', compact(
            'startOfWeek', 'endOfWeek', 'startOfLastWeek', 'endOfLastWeek',
            'weeklySales', 'weeklyExpenses', 'weeklyPurchases',
            'totalWeeklySales', 'totalWeeklyExpenses', 'totalWeeklyPurchases',
            'totalWeeklyTransactions', 'netIncomeWeekly',
            'salesChange', 'expensesChange'
        ));
    }
    
    public function monthly()
    {
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));
        $currentYear = date('Y');
        
        // This month's data
        $monthlySales = Penjualan::where('created_at', 'LIKE', "$currentMonth%")->where('bayar', '>', 0)->get();
        $monthlyExpenses = Pengeluaran::where('created_at', 'LIKE', "$currentMonth%")->get();
        $monthlyPurchases = Pembelian::where('created_at', 'LIKE', "$currentMonth%")->get();
        
        // Last month's data for comparison
        $lastMonthSales = Penjualan::where('created_at', 'LIKE', "$lastMonth%")->where('bayar', '>', 0)->sum('bayar');
        $lastMonthExpenses = Pengeluaran::where('created_at', 'LIKE', "$lastMonth%")->sum('nominal');
        
        // This month's totals
        $totalMonthlySales = $monthlySales->sum('bayar');
        $totalMonthlyExpenses = $monthlyExpenses->sum('nominal');
        $totalMonthlyPurchases = $monthlyPurchases->sum('total_harga');
        $totalMonthlyTransactions = $monthlySales->count();
        $netIncomeMonthly = $totalMonthlySales - $totalMonthlyExpenses;
        
        // Comparison percentages
        $salesChange = $lastMonthSales > 0 ? (($totalMonthlySales - $lastMonthSales) / $lastMonthSales) * 100 : 0;
        $expensesChange = $lastMonthExpenses > 0 ? (($totalMonthlyExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 : 0;
        
        // Daily breakdown for the month
        $dailyBreakdown = [];
        for ($day = 1; $day <= date('t'); $day++) {
            $date = date('Y-m-d', strtotime(date('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT)));
            $dailySales = Penjualan::whereDate('created_at', $date)->where('bayar', '>', 0)->sum('bayar');
            $dailyExpenses = Pengeluaran::whereDate('created_at', $date)->sum('nominal');
            $dailyTransactions = Penjualan::whereDate('created_at', $date)->where('bayar', '>', 0)->count();
            
            $dailyBreakdown[] = [
                'date' => $date,
                'day' => $day,
                'sales' => $dailySales,
                'expenses' => $dailyExpenses,
                'net' => $dailySales - $dailyExpenses,
                'transactions' => $dailyTransactions
            ];
        }
        
        return view('laporan.monthly', compact(
            'currentMonth', 'lastMonth', 'currentYear',
            'monthlySales', 'monthlyExpenses', 'monthlyPurchases',
            'totalMonthlySales', 'totalMonthlyExpenses', 'totalMonthlyPurchases',
            'totalMonthlyTransactions', 'netIncomeMonthly',
            'salesChange', 'expensesChange', 'dailyBreakdown'
        ));
    }
    
    public function annual()
    {
        $currentYear = date('Y');
        $lastYear = date('Y', strtotime('-1 year'));
        
        // This year's data
        $annualSales = Penjualan::where('created_at', 'LIKE', "$currentYear%")->where('bayar', '>', 0)->get();
        $annualExpenses = Pengeluaran::where('created_at', 'LIKE', "$currentYear%")->get();
        $annualPurchases = Pembelian::where('created_at', 'LIKE', "$currentYear%")->get();
        
        // Last year's data for comparison
        $lastYearSales = Penjualan::where('created_at', 'LIKE', "$lastYear%")->where('bayar', '>', 0)->sum('bayar');
        $lastYearExpenses = Pengeluaran::where('created_at', 'LIKE', "$lastYear%")->sum('nominal');
        
        // This year's totals
        $totalAnnualSales = $annualSales->sum('bayar');
        $totalAnnualExpenses = $annualExpenses->sum('nominal');
        $totalAnnualPurchases = $annualPurchases->sum('total_harga');
        $totalAnnualTransactions = $annualSales->count();
        $netIncomeAnnual = $totalAnnualSales - $totalAnnualExpenses;
        
        // Comparison percentages
        $salesChange = $lastYearSales > 0 ? (($totalAnnualSales - $lastYearSales) / $lastYearSales) * 100 : 0;
        $expensesChange = $lastYearExpenses > 0 ? (($totalAnnualExpenses - $lastYearExpenses) / $lastYearExpenses) * 100 : 0;
        
        // Monthly breakdown for the year
        $monthlyBreakdown = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
            $monthDate = "$currentYear-$monthStr";
            $monthSales = Penjualan::where('created_at', 'LIKE', "$monthDate%")->where('bayar', '>', 0)->sum('bayar');
            $monthExpenses = Pengeluaran::where('created_at', 'LIKE', "$monthDate%")->sum('nominal');
            $monthTransactions = Penjualan::where('created_at', 'LIKE', "$monthDate%")->where('bayar', '>', 0)->count();
            
            $monthlyBreakdown[] = [
                'month' => $month,
                'monthName' => date('F', mktime(0, 0, 0, $month, 1)),
                'sales' => $monthSales,
                'expenses' => $monthExpenses,
                'net' => $monthSales - $monthExpenses,
                'transactions' => $monthTransactions
            ];
        }
        
        return view('laporan.annual', compact(
            'currentYear', 'lastYear',
            'annualSales', 'annualExpenses', 'annualPurchases',
            'totalAnnualSales', 'totalAnnualExpenses', 'totalAnnualPurchases',
            'totalAnnualTransactions', 'netIncomeAnnual',
            'salesChange', 'expensesChange', 'monthlyBreakdown'
        ));
    }
    
    // ─── Periodic Report Data for AJAX ─────────────────────────────────────────
    
    public function dailyData($date)
    {
        $sales = Penjualan::whereDate('created_at', $date)->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::whereDate('created_at', $date)->get();
        
        return response()->json([
            'sales' => $sales,
            'expenses' => $expenses
        ]);
    }
    
    public function weeklyData($start, $end)
    {
        $sales = Penjualan::whereBetween('created_at', [$start, $end])->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::whereBetween('created_at', [$start, $end])->get();
        
        return response()->json([
            'sales' => $sales,
            'expenses' => $expenses
        ]);
    }
    
    public function monthlyData($year, $month)
    {
        $period = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);
        $sales = Penjualan::where('created_at', 'LIKE', "$period%")->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::where('created_at', 'LIKE', "$period%")->get();
        
        return response()->json([
            'sales' => $sales,
            'expenses' => $expenses
        ]);
    }
    
    public function annualData($year)
    {
        $sales = Penjualan::where('created_at', 'LIKE', "$year%")->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::where('created_at', 'LIKE', "$year%")->get();
        
        return response()->json([
            'sales' => $sales,
            'expenses' => $expenses
        ]);
    }
    
    // ─── Periodic Report PDFs ───────────────────────────────────────────────────
    
    public function dailyPDF($date)
    {
        $sales = Penjualan::whereDate('created_at', $date)->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::whereDate('created_at', $date)->get();
        
        $totalSales = $sales->sum('bayar');
        $totalExpenses = $expenses->sum('nominal');
        $netIncome = $totalSales - $totalExpenses;
        
        $pdf = PDF::loadView('laporan.daily_pdf', compact(
            'date', 'sales', 'expenses', 'totalSales', 'totalExpenses', 'netIncome'
        ));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream("Daily-Report-$date.pdf");
    }
    
    public function weeklyPDF($start, $end)
    {
        $sales = Penjualan::whereBetween('created_at', [$start, $end])->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::whereBetween('created_at', [$start, $end])->get();
        
        $totalSales = $sales->sum('bayar');
        $totalExpenses = $expenses->sum('nominal');
        $netIncome = $totalSales - $totalExpenses;
        
        $pdf = PDF::loadView('laporan.weekly_pdf', compact(
            'start', 'end', 'sales', 'expenses', 'totalSales', 'totalExpenses', 'netIncome'
        ));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream("Weekly-Report-$start-to-$end.pdf");
    }
    
    public function monthlyPDF($year, $month)
    {
        $period = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);
        $sales = Penjualan::where('created_at', 'LIKE', "$period%")->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::where('created_at', 'LIKE', "$period%")->get();
        
        $totalSales = $sales->sum('bayar');
        $totalExpenses = $expenses->sum('nominal');
        $netIncome = $totalSales - $totalExpenses;
        
        $pdf = PDF::loadView('laporan.monthly_pdf', compact(
            'year', 'month', 'sales', 'expenses', 'totalSales', 'totalExpenses', 'netIncome'
        ));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream("Monthly-Report-$year-$month.pdf");
    }
    
    public function annualPDF($year)
    {
        $sales = Penjualan::where('created_at', 'LIKE', "$year%")->where('bayar', '>', 0)->with('user')->get();
        $expenses = Pengeluaran::where('created_at', 'LIKE', "$year%")->get();
        
        $totalSales = $sales->sum('bayar');
        $totalExpenses = $expenses->sum('nominal');
        $netIncome = $totalSales - $totalExpenses;
        
        $pdf = PDF::loadView('laporan.annual_pdf', compact(
            'year', 'sales', 'expenses', 'totalSales', 'totalExpenses', 'netIncome'
        ));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream("Annual-Report-$year.pdf");
    }

    public function index(Request $request)
    {
        $tanggalAwal  = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal  = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $total_pendapatan = 0;

        $cursor = $awal;
        while (strtotime($cursor) <= strtotime($akhir)) {
            $tanggal = $cursor;
            $cursor  = date('Y-m-d', strtotime("+1 day", strtotime($cursor)));

            $total_penjualan  = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pembelian  = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

            $pendapatan        = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $total_pendapatan += $pendapatan;

            $data[] = [
                'DT_RowIndex'  => $no++,
                'tanggal'      => tanggal_indonesia($tanggal, false),
                'penjualan'    => format_uang($total_penjualan),
                'pembelian'    => format_uang($total_pembelian),
                'pengeluaran'  => format_uang($total_pengeluaran),
                'pendapatan'   => format_uang($pendapatan),
            ];
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal'     => '',
            'penjualan'   => '',
            'pembelian'   => '',
            'pengeluaran' => 'Total Income',
            'pendapatan'  => format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        return datatables()->of($this->getData($awal, $akhir))->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Income-Report-'. date('Y-m-d') .'.pdf');
    }

    // ─── Sales Report ─────────────────────────────────────────────────────────

    public function sales(Request $request)
    {
        $tanggalAwal  = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "") {
            $tanggalAwal  = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir ?? $tanggalAkhir;
        }

        return view('laporan.sales', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function salesData($awal, $akhir)
    {
        $penjualan = Penjualan::with('user')
            ->where('bayar', '>', 0)
            ->whereDate('created_at', '>=', $awal)
            ->whereDate('created_at', '<=', $akhir)
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables()->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', fn($p) => tanggal_indonesia($p->created_at, false))
            ->addColumn('customer', fn($p) => $p->nama_pelanggan ?? '-')
            ->addColumn('total_item', fn($p) => format_uang($p->total_item))
            ->addColumn('total_harga', fn($p) => 'UGX ' . format_uang($p->total_harga))
            ->addColumn('bayar_fmt', fn($p) => 'UGX ' . format_uang($p->bayar))
            ->addColumn('kasir', fn($p) => $p->user->name ?? '-')
            ->make(true);
    }

    public function salesPDF($awal, $akhir)
    {
        $penjualan = Penjualan::with('user')
            ->where('bayar', '>', 0)
            ->whereDate('created_at', '>=', $awal)
            ->whereDate('created_at', '<=', $akhir)
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $penjualan->sum('bayar');
        $pdf   = PDF::loadView('laporan.sales_pdf', compact('awal', 'akhir', 'penjualan', 'total'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Sales-Report-'. date('Y-m-d') .'.pdf');
    }

    // ─── Expenses Report ───────────────────────────────────────────────────────

    public function expenses(Request $request)
    {
        $tanggalAwal  = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "") {
            $tanggalAwal  = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir ?? $tanggalAkhir;
        }

        return view('laporan.expenses', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function expensesData($awal, $akhir)
    {
        $pengeluaran = Pengeluaran::whereDate('created_at', '>=', $awal)
            ->whereDate('created_at', '<=', $akhir)
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables()->of($pengeluaran)
            ->addIndexColumn()
            ->addColumn('tanggal', fn($e) => tanggal_indonesia($e->created_at, false))
            ->addColumn('deskripsi_fmt', fn($e) => $e->deskripsi)
            ->addColumn('nominal_fmt', fn($e) => 'UGX ' . format_uang($e->nominal))
            ->make(true);
    }

    public function expensesPDF($awal, $akhir)
    {
        $pengeluaran = Pengeluaran::whereDate('created_at', '>=', $awal)
            ->whereDate('created_at', '<=', $akhir)
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $pengeluaran->sum('nominal');
        $pdf   = PDF::loadView('laporan.expenses_pdf', compact('awal', 'akhir', 'pengeluaran', 'total'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Expenses-Report-'. date('Y-m-d') .'.pdf');
    }

    // ─── Products / Stock Report ───────────────────────────────────────────────

    public function products()
    {
        return view('laporan.products');
    }

    public function productsData()
    {
        $produk = Produk::with('kategori')->orderBy('nama_produk')->get();

        return datatables()->of($produk)
            ->addIndexColumn()
            ->addColumn('kategori_nama', fn($p) => $p->kategori->nama_kategori ?? '-')
            ->addColumn('harga_beli_fmt', fn($p) => 'UGX ' . format_uang($p->harga_beli))
            ->addColumn('harga_jual_fmt', fn($p) => 'UGX ' . format_uang($p->harga_jual))
            ->addColumn('stok_badge', function($p) {
                $color = $p->stok > 10 ? 'success' : ($p->stok > 0 ? 'warning' : 'danger');
                return '<span class="label label-'.$color.'">'.$p->stok.'</span>';
            })
            ->rawColumns(['stok_badge'])
            ->make(true);
    }

    public function productsPDF()
    {
        $produk = Produk::with('kategori')->orderBy('nama_produk')->get();
        $pdf    = PDF::loadView('laporan.products_pdf', compact('produk'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Products-Report-'. date('Y-m-d') .'.pdf');
    }
}
