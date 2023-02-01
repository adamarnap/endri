<?php 

require_once '../config/db.php';

$jumlah_baru = $_POST['jml_beli'];
$id_detail = $_POST['id_detail'];

// cari transaksi
$cari_id_trans = "SELECT id_transaksi, id_produk, jumlah_beli FROM `detail_transaksi` WHERE id_detail = $id_detail limit 1";
$data_cari = mysqli_query($koneksi, $cari_id_trans);

foreach ($data_cari as $row) {	
	$id_transaksi = $row['id_transaksi'];
	$id_produk = $row['id_produk'];
	$jumlah_lama = $row['jumlah_beli'];

	if ($jumlah_baru >= $jumlah_lama) {
		$selisih = $jumlah_baru - $jumlah_lama;
		$sql_stok = "UPDATE `produk` SET `stok_produk`=`stok_produk`-$selisih WHERE id_produk = $id_produk";
	} else {
		$selisih = $jumlah_lama - $jumlah_baru;
		$sql_stok = "UPDATE `produk` SET `stok_produk`=`stok_produk`+$selisih WHERE id_produk = $id_produk";
	}
}

// update detail
$sql_update_detail = "UPDATE `detail_transaksi` SET `jumlah_beli`= $jumlah_baru WHERE id_detail = $id_detail";
$update_detail = mysqli_query($koneksi, $sql_update_detail);


$update_stok = mysqli_query($koneksi, $sql_stok);

header("location:../index.php?id=$id_transaksi");

?>