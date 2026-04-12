<?php

function hitung_total_anggota($anggota_list) {
    return count($anggota_list);
}

function hitung_anggota_aktif($anggota_list) {
    $count = 0;
    foreach ($anggota_list as $a) {
        if ($a['status'] == "Aktif") $count++;
    }
    return $count;
}

function hitung_rata_rata_pinjaman($anggota_list) {
    $total = 0;
    foreach ($anggota_list as $a) {
        $total += $a['total_pinjaman'];
    }
    return $total / count($anggota_list);
}

function cari_anggota_by_id($anggota_list, $id) {
    foreach ($anggota_list as $a) {
        if ($a['id'] == $id) return $a;
    }
    return null;
}

function cari_anggota_teraktif($anggota_list) {
    $max = $anggota_list[0];
    foreach ($anggota_list as $a) {
        if ($a['total_pinjaman'] > $max['total_pinjaman']) {
            $max = $a;
        }
    }
    return $max;
}

function filter_by_status($anggota_list, $status) {
    $hasil = [];
    foreach ($anggota_list as $a) {
        if ($a['status'] == $status) {
            $hasil[] = $a;
        }
    }
    return $hasil;
}

function validasi_email($email) {
    return !empty($email) && strpos($email, '@') && strpos($email, '.');
}

function format_tanggal_indo($tanggal) {
    $bulan = [
        "01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April",
        "05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus",
        "09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember"
    ];

    $pecah = explode('-', $tanggal);
    return $pecah[2] . " " . $bulan[$pecah[1]] . " " . $pecah[0];
}

function sort_nama($anggota_list) {
    usort($anggota_list, function($a, $b){
        return strcmp($a['nama'], $b['nama']);
    });
    return $anggota_list;
}

function search_nama($anggota_list, $keyword) {
    $hasil = [];
    foreach ($anggota_list as $a) {
        if (stripos($a['nama'], $keyword) !== false) {
            $hasil[] = $a;
        }
    }
    return $hasil;
}