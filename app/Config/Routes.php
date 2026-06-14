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
    $routes->post('kelas/update/(:num)', 'Kelas::update/$1');
    $routes->post('kelas/hapus/(:num)', 'Kelas::hapus/$1');

    // routes untuk siswa
    $routes->get ('siswa', 'Siswa::index');
    $routes->get ('siswa/tambah', 'Siswa::tambah');
    $routes->post('siswa/simpan', 'Siswa::simpan');
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
    $routes->post('guru/hapus/(:num)', 'Guru::hapus/$1');

    // routes untuk jadwal
    $routes->get ('jadwal', 'Jadwal::index');
    $routes->get ('jadwal/tambah', 'Jadwal::tambah');
    $routes->post('jadwal/simpan', 'Jadwal::simpan');
    $routes->get ('jadwal/edit/(:num)', 'Jadwal::edit/$1');
    $routes->post('jadwal/update/(:num)', 'Jadwal::update/$1');
    $routes->post('jadwal/hapus/(:num)', 'Jadwal::hapus/$1');

    // routes untuk tahun ajaran
    $routes->get ('tahun-ajaran', 'TahunAjaran::index');
    $routes->get ('tahun-ajaran/tambah', 'TahunAjaran::tambah');
    $routes->post('tahun-ajaran/simpan', 'TahunAjaran::simpan');
    $routes->get ('tahun-ajaran/edit/(:num)', 'TahunAjaran::edit/$1');
    $routes->post('tahun-ajaran/update/(:num)', 'TahunAjaran::update/$1');
    $routes->post('tahun-ajaran/hapus/(:num)', 'TahunAjaran::hapus/$1');

    // routes untuk nilai
    $routes->get ('nilai', 'Nilai::index');
    $routes->get ('nilai/tambah', 'Nilai::tambah');
    $routes->post('nilai/simpan', 'Nilai::simpan');
    $routes->get ('nilai/edit/(:num)', 'Nilai::edit/$1');
    $routes->post('nilai/update/(:num)', 'Nilai::update/$1');
    $routes->post('nilai/hapus/(:num)', 'Nilai::hapus/$1');

    // routes untuk absensi
    $routes->get ('absensi', 'Absensi::index');
    $routes->get ('absensi/tambah', 'Absensi::tambah');
    $routes->post('absensi/simpan', 'Absensi::simpan');
    $routes->get ('absensi/edit/(:num)', 'Absensi::edit/$1');
    $routes->post('absensi/update/(:num)', 'Absensi::update/$1');
    $routes->post('absensi/hapus/(:num)', 'Absensi::hapus/$1');
});
