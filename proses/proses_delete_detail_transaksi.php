<?php 
require_once '../config/db.php';

// cek detail transaksi
$id_detail = $_GET['id_detail'];

$cari_id_trans = "SELECT id_transaksi, id_produk, jumlah_beli FROM `detail_transaksi` WHERE id_detail = $id_detail limit 1";
$data_cari = mysqli_query($koneksi, $cari_id_trans);

// var_dump($data_cari);

foreach ($data_cari as $row) {
	
	$id_transaksi = $row['id_transaksi'];
	$id_produk = $row['id_produk'];
	$jumlah = $row['jumlah_beli'];
}

// hapus detail
$sql_delete = "DELETE FROM `detail_transaksi` WHERE id_detail = $id_detail";

$detele = mysqli_query($koneksi, $sql_delete);

// nambahi stok setelah dibatalkan
$sql_update_stok = "UPDATE `produk` SET `stok_produk`=`stok_produk`+$jumlah WHERE id_produk = $id_produk";
$update_stok = mysqli_query($koneksi, $sql_update_stok);

header("location:../index.php?id=$id_transaksi");
?>