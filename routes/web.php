<?php

use App\Http\Controllers\{
    ChatController,
    DashboardController,
    KategoriController,
    LaporanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    QRCodeController,
    SettingController,
    SupplierController,
    UserController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Public QR Code Verification Routes
Route::get('/scan', [QRCodeController::class, 'scan'])->name('qrcode.scan');
Route::get('/verify/{receiptId}', [QRCodeController::class, 'verify'])->name('qrcode.verify');
Route::get('/api/verify/{receiptId}', [QRCodeController::class, 'apiVerify'])->name('qrcode.api_verify');
Route::post('/lookup', [QRCodeController::class, 'lookup'])->name('qrcode.lookup');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Chat Routes (accessible by all authenticated users)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/contacts', [ChatController::class, 'getContacts'])->name('chat.contacts');
    Route::get('/chat/messages/{userId}', [ChatController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::get('/kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
        Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');

        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}/header', [PenjualanController::class, 'showHeader'])->name('penjualan.header');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });

    Route::group(['middleware' => 'level:1,2,3'], function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');
        Route::get('/transaksi/invoice', [PenjualanController::class, 'invoice'])->name('transaksi.invoice');
        Route::get('/transaksi/aktif', [PenjualanController::class, 'active'])->name('transaksi.aktif');
        Route::post('/transaksi/resume/{id}', [PenjualanController::class, 'resume'])->name('transaksi.resume');

        Route::get('/transaksi/produk/{kode}', [PenjualanDetailController::class, 'lookupByBarcode'])->name('transaksi.lookup');
        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/riwayat-saya', [PenjualanController::class, 'history'])->name('penjualan.history');
        Route::get('/riwayat-saya/data', [PenjualanController::class, 'historyData'])->name('penjualan.history.data');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        // Reports Hubq1`   
        Route::get('/laporan', [LaporanController::class, 'hub'])->name('laporan.hub');

        // Income Report
        Route::get('/laporan/income', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/income/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/income/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        // Sales Report
        Route::get('/laporan/sales', [LaporanController::class, 'sales'])->name('laporan.sales');
        Route::get('/laporan/sales/data/{awal}/{akhir}', [LaporanController::class, 'salesData'])->name('laporan.sales_data');
        Route::get('/laporan/sales/pdf/{awal}/{akhir}', [LaporanController::class, 'salesPDF'])->name('laporan.sales_pdf');

        // Expenses Report
        Route::get('/laporan/expenses', [LaporanController::class, 'expenses'])->name('laporan.expenses');
        Route::get('/laporan/expenses/data/{awal}/{akhir}', [LaporanController::class, 'expensesData'])->name('laporan.expenses_data');
        Route::get('/laporan/expenses/pdf/{awal}/{akhir}', [LaporanController::class, 'expensesPDF'])->name('laporan.expenses_pdf');

        // Products/Stock Report
        Route::get('/laporan/products', [LaporanController::class, 'products'])->name('laporan.products');
        Route::get('/laporan/products/data', [LaporanController::class, 'productsData'])->name('laporan.products_data');
        Route::get('/laporan/products/pdf', [LaporanController::class, 'productsPDF'])->name('laporan.products_pdf');

        // Periodic Reports (Daily, Weekly, Monthly, Annual)
        Route::get('/laporan/daily', [LaporanController::class, 'daily'])->name('laporan.daily');
        Route::get('/laporan/daily/data/{date}', [LaporanController::class, 'dailyData'])->name('laporan.daily_data');
        Route::get('/laporan/daily/pdf/{date}', [LaporanController::class, 'dailyPDF'])->name('laporan.daily_pdf');
        
        Route::get('/laporan/weekly', [LaporanController::class, 'weekly'])->name('laporan.weekly');
        Route::get('/laporan/weekly/data/{start}/{end}', [LaporanController::class, 'weeklyData'])->name('laporan.weekly_data');
        Route::get('/laporan/weekly/pdf/{start}/{end}', [LaporanController::class, 'weeklyPDF'])->name('laporan.weekly_pdf');
        
        Route::get('/laporan/monthly', [LaporanController::class, 'monthly'])->name('laporan.monthly');
        Route::get('/laporan/monthly/data/{year}/{month}', [LaporanController::class, 'monthlyData'])->name('laporan.monthly_data');
        Route::get('/laporan/monthly/pdf/{year}/{month}', [LaporanController::class, 'monthlyPDF'])->name('laporan.monthly_pdf');
        
        Route::get('/laporan/annual', [LaporanController::class, 'annual'])->name('laporan.annual');
        Route::get('/laporan/annual/data/{year}', [LaporanController::class, 'annualData'])->name('laporan.annual_data');
        Route::get('/laporan/annual/pdf/{year}', [LaporanController::class, 'annualPDF'])->name('laporan.annual_pdf');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });
 
    Route::group(['middleware' => 'level:1,2,3'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});