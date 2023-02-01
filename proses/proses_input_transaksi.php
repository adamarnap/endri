<?php
date_default_timezone_set('Asia/Jakarta');

require_once '../config/db.php';

$arr_data_produk = explode("||", $_POST['id_produk']);
// var_dump($arr_data_produk);
$id_produk = $arr_data_produk[0];
$harga = $arr_data_produk[1];
$jumlah = $_POST['jumlah_beli'];
$tgl = date("Y-m-d H:i:s");
$id_admin = 1;

// Insert transaksi baru
if ($_POST['id_transaksi'] == 0) {

	$id_transaksi = insert_trans_baru($koneksi, $tgl, $id_admin);

} else {
	$id_transaksi = $_POST['id_transaksi'];
}

$jumlah_produk = cek_barang($koneksi, $id_transaksi, $id_produk);

if ($jumlah_produk > 0) {
	update_detail($koneksi, $jumlah, $id_transaksi, $id_produk);
	
} else {
	insert_detail($koneksi, $id_transaksi, $id_produk, $jumlah, $harga);
}

// kurangi stok barang setelah dibeli
kurangi_stok($koneksi, $jumlah, $id_produk);

header("location:../index.php?id=$id_transaksi");


// Start Kumpulan Function
function insert_trans_baru($koneksi, $tgl, $id_admin)
{
	$query_transaksi = "INSERT INTO `transaksi`(`tgl_transaksi`, `id_admin`) VALUES ('$tgl',$id_admin)";
	$insert_transaksi = mysqli_query($koneksi, $query_transaksi);
	$id_transaksi = mysqli_insert_id($koneksi);

	return $id_transaksi;
}

function cek_barang($koneksi, $id_transaksi, $id_produk)
{
	$sql_cek_produk = "SELECT id_detail FROM detail_transaksi WHERE id_transaksi = $id_transaksi AND id_produk = $id_produk";
	$data_cek_produk = mysqli_query($koneksi, $sql_cek_produk);

	// insert / update detail transaksi
	// var_dump($data_cek_produk->num_rows);
	$jumlah_produk = $data_cek_produk->num_rows;
	return $jumlah_produk;
}

function update_detail($koneksi, $jumlah, $id_transaksi, $id_produk) {
	$query_update_detail_transaksi = "UPDATE `detail_transaksi` SET `jumlah_beli`=(`jumlah_beli`+$jumlah) WHERE id_transaksi = $id_transaksi AND id_produk = $id_produk";

	$update_detail_transaksi = mysqli_query($koneksi, $query_update_detail_transaksi);
}

function insert_detail($koneksi, $id_transaksi, $id_produk, $jumlah, $harga)
{
	$query_detail_transaksi = "INSERT INTO `detail_transaksi`(`id_transaksi`, `id_produk`, `jumlah_beli`, `harga_beli`) VALUES ($id_transaksi,$id_produk,$jumlah,$harga)";

	$insert_detail_transaksi = mysqli_query($koneksi, $query_detail_transaksi);
}

function kurangi_stok($koneksi, $jumlah, $id_produk)
{
	$sql_update_stok = "UPDATE `produk` SET `stok_produk`=`stok_produk`-$jumlah WHERE id_produk = $id_produk";
	$update_stok = mysqli_query($koneksi, $sql_update_stok);
}
?>