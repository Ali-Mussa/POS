<?php

namespace App\Http\Controllers;

use App\Mail\SaleNotification;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Penjualan::with('member')->orderBy('id_penjualan', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'UGX '. format_uang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'UGX '. format_uang($penjualan->bayar);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('kode_member', function ($penjualan) {
                if (!empty($penjualan->nama_pelanggan)) {
                    return '<span class="label label-info"><i class="fa fa-user"></i> '. e($penjualan->nama_pelanggan) .'</span>';
                }
                $member = $penjualan->member->kode_member ?? '';
                if ($member) {
                    return '<span class="label label-success">'. $member .'</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('payment_method', function ($penjualan) {
                $method = $penjualan->payment_method ?? 'cash';
                if ($method === 'cash') {
                    return '<span class="label label-success"><i class="fa fa-money"></i> Cash</span>';
                } elseif ($method === 'card') {
                    return '<span class="label label-primary"><i class="fa fa-credit-card"></i> Card</span>';
                } elseif ($method === 'mobile_money') {
                    $provider = $penjualan->mobile_money_provider ?? '';
                    if ($provider === 'mtn_momo') {
                        $providerLabel = 'MTN MoMo';
                    } elseif ($provider === 'airtel_money') {
                        $providerLabel = 'Airtel Money';
                    } else {
                        $providerLabel = 'Mobile Money';
                    }
                    return '<span class="label label-warning"><i class="fa fa-mobile"></i> '.$providerLabel.'</span>';
                }
                return '<span class="label label-default">'. ucfirst($method) .'</span>';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member', 'payment_method'])
            ->make(true);
    }
    // visit "codeastro" for more projects!
    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->nama_pelanggan = $request->customer_name ?? $request->nama_pelanggan;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->payment_method = $request->payment_method ?? 'cash';
        $penjualan->mobile_money_provider = ($request->payment_method === 'mobile_money')
            ? $request->mobile_money_provider
            : null;
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }

        // Send sale notification email to admin
        try {
            $details = PenjualanDetail::with('produk')
                ->where('id_penjualan', $penjualan->id_penjualan)
                ->get();
            $cashierName = auth()->user()->name ?? 'Unknown';
            $admin = User::where('level', 1)->first();

            if ($admin) {
                Mail::to($admin->email)->send(new SaleNotification($penjualan, $details, $cashierName));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send sale notification email: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.selesai');
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'UGX '. format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'UGX '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }
    public function showHeader($id)
    {
        $p = Penjualan::with('member', 'user')->find($id);
        if (! $p) {
            return response()->json(null, 404);
        }

        $method = $p->payment_method ?? 'cash';
        if ($method === 'card') {
            $type  = $p->card_type ?? 'Card';
            $last4 = $p->card_last_four ? ' ···· ' . $p->card_last_four : '';
            $methodLabel = $type . $last4;
        } elseif ($method === 'mobile_money') {
            $mm = $p->mobile_money_provider ?? '';
            $methodLabel = $mm === 'mtn_momo' ? 'MTN MoMo' : ($mm === 'airtel_money' ? 'Airtel Money' : 'Mobile Money');
        } else {
            $methodLabel = 'Cash';
        }

        $customer = $p->nama_pelanggan ?? ($p->member->nama ?? '-');

        return response()->json([
            'id_penjualan' => $p->id_penjualan,
            'tanggal'      => tanggal_indonesia($p->created_at, false),
            'customer'     => $customer,
            'cashier'      => $p->user->name ?? '-',
            'total_item'   => format_uang($p->total_item),
            'total_harga'  => 'UGX ' . format_uang($p->total_harga),
            'diskon'       => $p->diskon . '%',
            'bayar'        => 'UGX ' . format_uang($p->bayar),
            'diterima'     => 'UGX ' . format_uang($p->diterima ?? 0),
            'kembali'      => 'UGX ' . format_uang(max(0, ($p->diterima ?? 0) - $p->bayar)),
            'payment'      => $methodLabel,
        ]);
    }

    // visit "codeastro" for more projects!
    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaction-'. date('Y-m-d-his') .'.pdf');
    }

    public function invoice()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.invoice', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Invoice-'. $penjualan->id_penjualan .'-'. date('Y-m-d-his') .'.pdf');
    }

    public function active()
    {
        $items = Penjualan::with('member')
            ->where('id_user', auth()->id())
            ->where(function($q){
                $q->where('bayar', 0)->orWhereNull('bayar');
            })
            ->orderBy('id_penjualan', 'desc')
            ->get();

        return view('penjualan.aktif', compact('items'));
    }

    public function resume($id)
    {
        $penjualan = Penjualan::where('id_penjualan', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();
        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function history()
    {
        return view('penjualan.history');
    }

    public function historyData()
    {
        $penjualan = Penjualan::with('member')
            ->where('id_user', auth()->id())
            ->where('bayar', '>', 0)
            ->orderBy('id_penjualan', 'desc')
            ->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('kode_member', function ($penjualan) {
                if (!empty($penjualan->nama_pelanggan)) {
                    return '<span class="label label-info"><i class="fa fa-user"></i> '. e($penjualan->nama_pelanggan) .'</span>';
                }
                $member = $penjualan->member->kode_member ?? '';
                if ($member) {
                    return '<span class="label label-success">'. $member .'</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'UGX '. format_uang($penjualan->total_harga);
            })
            ->addColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'UGX '. format_uang($penjualan->bayar);
            })
            ->rawColumns(['kode_member'])
            ->make(true);
    }
}
// visit "codeastro" for more projects!