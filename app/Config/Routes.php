<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');

$routes->get('login', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

$routes->get('register', 'Auth::register');
$routes->post('auth/register', 'Auth::processRegister');

// ========== ADMIN ROUTES ==========

$routes->get('dashboard', 'Dashboard::index');

// ===== DATA MASTER =====

// Data Siswa
$routes->get('siswa', 'Siswa::index');
$routes->get('siswa/create', 'Siswa::create');
$routes->post('siswa/store', 'Siswa::store');
$routes->get('siswa/edit/(:num)', 'Siswa::edit/$1');
$routes->post('siswa/update/(:num)', 'Siswa::update/$1');
$routes->get('siswa/delete/(:num)', 'Siswa::delete/$1');

// Data Kelas
$routes->get('kelas', 'Kelas::index');
$routes->get('kelas/create', 'Kelas::create');
$routes->post('kelas/store', 'Kelas::store');
$routes->get('kelas/edit/(:num)', 'Kelas::edit/$1');
$routes->post('kelas/update/(:num)', 'Kelas::update/$1');
$routes->get('kelas/delete/(:num)', 'Kelas::delete/$1');

// Data SPP (Master + Kirim Tagihan)
$routes->get('data-spp', 'DataSpp::index');
$routes->get('data-spp/create', 'DataSpp::create');
$routes->post('data-spp/store', 'DataSpp::store');
$routes->get('data-spp/edit/(:num)', 'DataSpp::edit/$1');
$routes->post('data-spp/update/(:num)', 'DataSpp::update/$1');
$routes->get('data-spp/delete/(:num)', 'DataSpp::delete/$1');
$routes->get('data-spp/kirim-tagihan', 'DataSpp::kirimTagihan');
$routes->post('data-spp/proses-kirim-tagihan', 'DataSpp::prosesKirimTagihan');
$routes->get('data-spp/delete-tagihan/(:num)', 'DataSpp::deleteTagihan/$1');

// Jenis Pembayaran Lain (Master + Kirim Tagihan)
$routes->get('jenis-pembayaran-lain', 'JenisPembayaranLain::index');
$routes->get('jenis-pembayaran-lain/create', 'JenisPembayaranLain::create');
$routes->post('jenis-pembayaran-lain/store', 'JenisPembayaranLain::store');
$routes->get('jenis-pembayaran-lain/edit/(:num)', 'JenisPembayaranLain::edit/$1');
$routes->post('jenis-pembayaran-lain/update/(:num)', 'JenisPembayaranLain::update/$1');
$routes->get('jenis-pembayaran-lain/delete/(:num)', 'JenisPembayaranLain::delete/$1');

// TAMBAH INI - untuk tagihan
$routes->get('jenis-pembayaran-lain/tagihan', 'JenisPembayaranLain::tagihan');
$routes->get('jenis-pembayaran-lain/create-tagihan', 'JenisPembayaranLain::kirimTagihan');
$routes->post('jenis-pembayaran-lain/store-tagihan', 'JenisPembayaranLain::prosesKirimTagihan');
$routes->get('jenis-pembayaran-lain/delete-tagihan/(:num)', 'JenisPembayaranLain::deleteTagihan/$1');

// ===== TRANSAKSI =====

// Pembayaran SPP (Verifikasi)
$routes->get('pembayaran-spp', 'PembayaranSpp::index');
$routes->get('pembayaran-spp/verifikasi/(:num)', 'PembayaranSpp::verifikasi/$1');
$routes->post('pembayaran-spp/tolak/(:num)', 'PembayaranSpp::tolak/$1');
$routes->get('pembayaran-spp/delete/(:num)', 'PembayaranSpp::delete/$1');

// Pembayaran Lain (Verifikasi)
$routes->get('pembayaran-lain-transaksi', 'PembayaranLainTransaksi::index');
$routes->get('pembayaran-lain-transaksi/verifikasi/(:num)', 'PembayaranLainTransaksi::verifikasi/$1');
$routes->post('pembayaran-lain-transaksi/tolak/(:num)', 'PembayaranLainTransaksi::tolak/$1');
$routes->get('pembayaran-lain-transaksi/delete/(:num)', 'PembayaranLainTransaksi::delete/$1');

// ========== SISWA ROUTES ==========

$routes->group('siswa', function($routes) {
    $routes->get('dashboard', 'DashboardSiswa::index');
    
    // Tagihan
    $routes->get('tagihan-spp', 'DashboardSiswa::tagihanSpp');
    $routes->get('tagihan-lain', 'DashboardSiswa::tagihanLain');
    
    // Upload Bukti
    $routes->get('upload-bukti', 'DashboardSiswa::uploadBukti');
    $routes->post('proses-upload-spp', 'DashboardSiswa::prosesUploadSpp');
    $routes->post('proses-upload-lain', 'DashboardSiswa::prosesUploadLain');
    
    // Riwayat
    $routes->get('riwayat-pembayaran', 'DashboardSiswa::riwayatPembayaran');
    
    // Profil
    $routes->get('profil', 'DashboardSiswa::profil');
});
// Routes Guru
$routes->get('guru', 'guru::index');
$routes->get('guru/create', 'guru::create');
$routes->post('guru/store', 'guru::store');
$routes->get('guru/edit/(:num)', 'guru::edit/$1');
$routes->post('guru/update/(:num)', 'guru::update/$1');
$routes->get('guru/delete/(:num)', 'guru::delete/$1');
$routes->get('guru/detail/(:num)', 'guru::detail/$1');

// Routes untuk Admin - Transaksi Tunggakan
// Routes untuk Admin - Transaksi Tunggakan (HAPUS FILTER DULU)
$routes->group('tunggakan-transaksi', function($routes) {
    $routes->get('/', 'TunggakanTransaksi::index');
    $routes->get('detail/(:num)', 'TunggakanTransaksi::detail/$1');
    $routes->post('approve/(:num)', 'TunggakanTransaksi::approve/$1');
    $routes->post('reject/(:num)', 'TunggakanTransaksi::reject/$1');
    $routes->post('edit-jatuh-tempo/(:num)', 'TunggakanTransaksi::editJatuhTempo/$1');
    $routes->get('delete/(:num)', 'TunggakanTransaksi::delete/$1');
});

// Routes untuk Siswa - Pengajuan Tunggakan (HAPUS FILTER DULU)
$routes->group('siswa/tunggakan', function($routes) {
    $routes->get('/', 'PengajuanTunggakan::index');
    $routes->get('form', 'PengajuanTunggakan::form');
    $routes->get('form/(:num)', 'PengajuanTunggakan::form/$1');
    $routes->post('submit', 'PengajuanTunggakan::submit');
    $routes->get('detail/(:num)', 'PengajuanTunggakan::detail/$1');
    $routes->get('cancel/(:num)', 'PengajuanTunggakan::cancel/$1');
});

// Laporan Tunggakan
$routes->get('laporan-tunggakan', 'LaporanTunggakan::index');
$routes->get('laporan-tunggakan/export-excel', 'LaporanTunggakan::exportExcel');
$routes->get('laporan-tunggakan/export-pdf', 'LaporanTunggakan::exportPdf');
$routes->get('laporan-tunggakan/export-csv', 'LaporanTunggakan::exportCsv');

// Laporan Pembayaran SPP
$routes->get('laporan-pembayaran-spp', 'LaporanPembayaranSpp::index');
$routes->get('laporan-pembayaran-spp/export-csv', 'LaporanPembayaranSpp::exportCsv');
$routes->get('laporan-pembayaran-spp/export-excel', 'LaporanPembayaranSpp::exportExcel');
$routes->get('laporan-pembayaran-spp/export-pdf', 'LaporanPembayaranSpp::exportPdf');

// Laporan Pembayaran Lain
$routes->get('laporan-pembayaran-lain', 'LaporanPembayaranLain::index');
$routes->get('laporan-pembayaran-lain/export-csv', 'LaporanPembayaranLain::exportCsv');
$routes->get('laporan-pembayaran-lain/export-excel', 'LaporanPembayaranLain::exportExcel');
$routes->get('laporan-pembayaran-lain/export-pdf', 'LaporanPembayaranLain::exportPdf');