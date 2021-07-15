<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->add('/register', 'AuthUser::register');
$routes->add('/login', 'AuthUser::login');
$routes->get('/verifikasi_email/(:any)', 'AuthUser::verifikasi_email/$1');
$routes->add('/resend_email_verifikasi', 'AuthUser::resend_email_verifikasi');
$routes->get('/verifikasi_lupa_password/(:any)', 'AuthUser::verifikasi_forgot_password/$1');
$routes->add('/lupa_password', 'AuthUser::forgot_password');
$routes->add('/verifikasi_lupa_password/(:any)', 'AuthUser::verifikasi_forgot_password/$1');
$routes->get('/logout', 'AuthUser::logout');
$routes->get('/admin', 'Home::admin');

// User
$routes->add('/admin/daftar_user', 'User::daftar_user');
$routes->post('/admin/get_daftar_user', 'User::get_daftar_user');
$routes->add('/admin/tambah_user', 'User::add_user');
$routes->add('/admin/detail_user/(:any)', 'User::detail_user/$1');
$routes->post('/admin/ubah_user/(:any)', 'User::update_user/$1');
$routes->post('/admin/hapus_user/(:any)', 'user::delete_user/$1');
$routes->get('/admin/profile', 'User::profile');
$routes->post('/admin/ubah_profile', 'User::update_profile');
$routes->post('/admin/ubah_password', 'User::change_password');

// Buku
$routes->get('/admin/daftar_buku', 'Buku::daftar_buku');
$routes->post('/admin/get_daftar_buku', 'Buku::get_daftar_buku');
$routes->add('/admin/tambah_buku', 'Buku::add_buku');
$routes->add('/admin/detail_buku/(:num)', 'Buku::detail_buku/$1');
$routes->post('/admin/ubah_buku/(:num)', 'Buku::update_buku/$1');
$routes->post('/admin/hapus_buku/(:num)', 'Buku::delete_buku/$1');

// Kategori
$routes->add('/admin/daftar_kategori', 'Kategori::daftar_kategori');
$routes->post('/admin/get_daftar_kategori', 'Kategori::get_daftar_kategori');
$routes->add('/admin/tambah_kategori', 'Kategori::add_kategori');
$routes->add('/admin/detail_kategori/(:num)', 'Kategori::detail_kategori/$1');
$routes->post('/admin/ubah_kategori/(:num)', 'Kategori::update_kategori/$1');
$routes->post('/admin/hapus_kategori/(:num)', 'Kategori::delete_kategori/$1');

// Pinjam
$routes->add('/admin/daftar_pinjaman', 'Pinjaman::daftar_pinjaman');
$routes->post('/admin/get_daftar_pinjaman', 'Pinjaman::get_daftar_pinjaman');
$routes->add('/admin/tambah_pinjaman', 'Pinjaman::add_pinjaman');
$routes->add('/admin/detail_pinjaman/(:any)', 'Pinjaman::detail_pinjaman/$1');
$routes->post('/admin/konfirmasi_pinjaman/(:any)', 'Pinjaman::confirm_pinjaman/$1');
$routes->post('/admin/ubah_pinjaman/(:any)', 'Pinjaman::update_pinjaman/$1');
$routes->post('/admin/hapus_pinjaman/(:any)', 'Pinjaman::delete_pinjaman/$1');
// Pinjam -> User -> Buku
$routes->post('/admin/get_user_pinjaman', 'Pinjaman::get_user_pinjaman');
$routes->post('/admin/get_buku_pinjaman', 'Pinjaman::get_buku_pinjaman');

// Denda
$routes->add('/admin/daftar_denda', 'Denda::daftar_denda');
$routes->post('/admin/get_daftar_denda', 'Denda::get_daftar_denda');
$routes->add('/admin/detail_denda/(:num)', 'Denda::detail_denda/$1');
$routes->post('/admin/ubah_denda/(:num)', 'denda::update_denda/$1');
$routes->post('/admin/hapus_denda/(:num)', 'denda::delete_denda/$1');

// Laporan
$routes->add('/admin/laporan/user', 'User::laporan_user');
$routes->add('/admin/laporan/pinjaman', 'Pinjaman::laporan_pinjaman');
$routes->add('/admin/laporan/denda', 'Denda::laporan_denda');

// Pengaturan
$routes->get('/admin/pengaturan', 'Home::pengaturan');
$routes->post('/admin/ubah_pengaturan', 'Home::update_setting');

// User Interface
$routes->get('/buku/(:num)', 'Buku::detail_buku/$1');
$routes->post('/pinjam_buku', 'Buku::pinjam_buku');
$routes->get('/keranjang', 'Buku::keranjang');
$routes->post('/buang_keranjang', 'Buku::delete_keranjang');
$routes->post('/ajukan_pinjaman', 'Pinjaman::ajukan_pinjaman');
$routes->get('/pinjaman', 'Pinjaman::daftar_pinjaman');
$routes->get('/pinjaman/(:any)', 'Pinjaman::detail_pinjaman/$1');
$routes->post('/pinjaman/(:any)', 'Pinjaman::delete_pinjaman/$1');
$routes->get('/profile', 'User::profile');
$routes->post('/ubah_profile', 'User::update_profile');
$routes->post('/ubah_password', 'User::change_password');

// Email
// $routes->get('/email', 'Email::sendEmail');


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
