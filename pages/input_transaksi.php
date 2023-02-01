<?php
require_once 'config/db.php';

if (isset($_GET['id'])) {
	$id_transaksi = $_GET['id'];
} else {
	$id_transaksi = 0;
}

// $id_transaksi = $_GET['id'];

$produk = mysqli_query($koneksi, "SELECT * FROM produk");

$sql_detail = "SELECT detail_transaksi.*, produk.nama_produk FROM detail_transaksi LEFT JOIN produk ON detail_transaksi.id_produk = produk.id_produk WHERE id_transaksi = $id_transaksi";

$data_detail = mysqli_query($koneksi, $sql_detail);
?>

<div class="row">
	<div class="col-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Form Input Pembelian</h4>
				<p class="card-description">
					Masukkan Produk Yang Akan Dibeli
				</p>
				<form class="forms-sample" action="proses/proses_input_transaksi.php" method="POST">
					<div class="form-group">
						<label for="id_produk">Pilih Produk</label>
						<select class="form-control" id="id_produk" name="id_produk" required="">
							<option value="">--Pilih Salah Satu Produk--</option>

							<?php 
							foreach ($produk as $row) {
								echo '<option value="'. $row['id_produk'] .'||'. $row['harga_produk'] .'">'. $row['nama_produk'] .'</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="harga_beli">Harga Beli</label>
						<input type="number" class="form-control" id="harga_beli" name="harga_beli" placeholder="Harga Beli" readonly="" required="">
					</div>
					<div class="form-group">
						<label for="jumlah_beli">Jumlah</label>
						<input type="number" class="form-control" placeholder="Jumlah Beli" id="jumlah_beli" name="jumlah_beli" required="">
					</div>

					<input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">

					<button type="submit" class="btn btn-primary me-2">Beli Produk</button>
					<button type="reset" class="btn btn-light">Reset</button>
				</form>
			</div>
		</div>
	</div>

	<div class="col-lg-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Produk Yang Dibeli</h4>
				<p class="card-description">
					Silahkan edit atau hapus produk yang sudah dibeli, jika sudah selesai maka klik Bayar
				</p>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>
									Produk
								</th>
								<th>
									Jumlah Beli
								</th>
								<th>
									Harga Beli
								</th>
								<th>
									Subtotal
								</th>
								<th>
									Aksi
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 

							$total = 0;

							foreach ($data_detail as $row) {
								$total = $total + ($row['jumlah_beli']*$row['harga_beli']);
							?>

							<tr>
								<form action="proses/proses_edit_detail.php" method="POST">
									<td><?= $row['nama_produk'] ?></td>
									<td style="width: 10%;">
										<input class="form-control" type="number" name="jml_beli" value="<?= $row['jumlah_beli'] ?>">
									</td>
									<td><?= number_format($row['harga_beli']) ?></td>
									<td><?= number_format($row['jumlah_beli']*$row['harga_beli']) ?></td>
									<td>
										<input type="hidden" name="id_detail" value="<?= $row['id_detail'] ?>">
										<button class="btn btn-warning" type="submit">Edit</button>
										<a class="btn btn-danger" href="proses/proses_delete_detail_transaksi.php?id_detail=<?= $row['id_detail'] ?>">Delete</a>
									</td>
								</form>
							</tr>

							<?php 
							
							}
							?>
														
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3">Total Pembelian</td>
								<td colspan="2">
									<?= number_format($total,0,',','.') ?>
								</td>
							</tr>
						</tfoot>
					</table>

					<a style="float:right;" href="index.php" class="btn btn-primary">Bayar</a>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	var produk = document.getElementById('id_produk');

	produk.onchange = gantiHarga;

	function gantiHarga() {
		var value_produk = produk.value;
		var harga = document.getElementById('harga_beli');

		var arr_data = value_produk.split("||");

		harga.value = arr_data[1];
	}
</script>