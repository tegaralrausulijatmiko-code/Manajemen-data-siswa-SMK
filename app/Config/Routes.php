<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::proses');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Default redirect
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    $routes->group('', ['filter' => 'role:guru'], static function ($routes) {
        $routes->get ('guru/absensi', 'Absensi::guruIndex');
        $routes->get ('guru/absensi/jadwal/(:num)', 'Absensi::guruJadwal/$1');
        $routes->post('guru/absensi/jadwal/(:num)/simpan', 'Absensi::guruSimpanJadwal/$1');
        $routes->get ('guru/absensi/rekap', 'Absensi::guruRekap');
    });

    $routes->group('', ['filter' => 'role:bk'], static function ($routes) {
        $routes->get('bk/rekap', 'Absensi::bkRekap');
        $routes->get('bk/rekap/export', 'Absensi::bkExportRekap');
    });

    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {
        // routes untuk jurusan
        $routes->get ('jurusan', 'Jurusan::index');
        $routes->get ('jurusan/tambah', 'Jurusan::tambah');
        $routes->post('jurusan/simpan', 'Jurusan::simpan');
        $routes->get ('jurusan/edit/(:num)', 'Jurusan::edit/$1');
        $routes->post('jurusan/update/(:num)', 'Jurusan::update/$1');
        $routes->post('jurusan/hapus/(:num)', 'Jurusan::hapus/$1');

    // routes untuk kelas
    $routes->get ('kelas', 'Kelas::index');
    $routes->get ('kelas/tambah', 'Kelas::tambah');
    $routes->post('kelas/simpan', 'Kelas::simpan');
    $routes->get ('kelas/edit/(:num)', 'Kelas::edit/$1');
    $routes->get('kelas/show/(:num)', 'Kelas::show/$1');
    $routes->post('kelas/update/(:num)', 'Kelas::update/$1');
    $routes->post('kelas/hapus/(:num)', 'Kelas::hapus/$1');

    // routes untuk siswa
    $routes->get ('siswa', 'Siswa::index');
    $routes->get ('siswa/tambah', 'Siswa::tambah');
    $routes->post('siswa/simpan', 'Siswa::simpan');
    $routes->get('siswa/show/(:num)', 'Siswa::show/$1');
    $routes->get ('siswa/edit/(:num)', 'Siswa::edit/$1');
    $routes->post('siswa/update/(:num)', 'Siswa::update/$1');
    $routes->post('siswa/hapus/(:num)', 'Siswa::hapus/$1');

    // routes untuk mapel
    $routes->get ('mapel', 'Mapel::index');
    $routes->get ('mapel/tambah', 'Mapel::tambah');
    $routes->post('mapel/simpan', 'Mapel::simpan');
    $routes->get ('mapel/edit/(:num)', 'Mapel::edit/$1');
    $routes->post('mapel/update/(:num)', 'Mapel::update/$1');
    $routes->post('mapel/hapus/(:num)', 'Mapel::hapus/$1');

    // routes untuk guru
    $routes->get ('guru', 'Guru::index');
    $routes->get ('guru/tambah', 'Guru::tambah');
    $routes->post('guru/simpan', 'Guru::simpan');
    $routes->get ('guru/edit/(:num)', 'Guru::edit/$1');
    $routes->post('guru/update/(:num)', 'Guru::update/$1');
    $routes->post('guru/buat-user/(:num)', 'Guru::buatUser/$1');
    $routes->post('guru/hapus/(:num)', 'Guru::hapus/$1');

    // routes untuk jadwal
    $routes->get ('jadwal', 'Jadwal::index');
    $routes->get ('jadwal/tambah', 'Jadwal::tambah');
    $routes->post('jadwal/simpan', 'Jadwal::simpan');
    $routes->get ('jadwal/edit/(:num)', 'Jadwal::edit/$1');
    $routes->post('jadwal/update/(:num)', 'Jadwal::update/$1');
    $routes->post('jadwal/hapus/(:num)', 'Jadwal::hapus/$1');

    // routes untuk absensi
    $routes->get ('absensi', 'Absensi::index');
    $routes->post('absensi/simpan', 'Absensi::simpanAbsensi');
    $routes->post('absensi/point/(:num)', 'Absensi::point/$1');
    $routes->get ('absensi/rekap', 'Absensi::rekap');
    $routes->get ('absensi/rekap/export', 'Absensi::exportRekap');
    $routes->get ('absensi/jadwal/(:num)', 'Absensi::jadwal/$1');
    $routes->post('absensi/jadwal/(:num)/simpan', 'Absensi::simpanJadwal/$1');
    $routes->get ('absensi/tambah', 'Absensi::tambah');
    $routes->post('absensi/simpan', 'Absensi::simpan');
    $routes->get ('absensi/edit/(:num)', 'Absensi::edit/$1');
    $routes->post('absensi/update/(:num)', 'Absensi::update/$1');
    $routes->post('absensi/hapus/(:num)', 'Absensi::hapus/$1');
    });

    $routes->get('user', 'User::index');
    $routes->get('user/tambah', 'User::tambah');
    $routes->post('user/simpan', 'User::simpan');
    $routes->get('user/edit/(:num)', 'User::edit/$1');
    $routes->post('user/update/(:num)', 'User::update/$1');
    $routes->post('user/toggle-status/(:num)', 'User::toggleStatus/$1');
    $routes->post('user/reset-password/(:num)','User::resetPassword/$1');
    $routes->post('user/hapus/(:num)', 'User::hapus/$1');
});
