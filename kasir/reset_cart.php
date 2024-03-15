<?php
// reset_cart.php

include "../koneksi.php";

// Hapus semua data dari tabel transaksi_temp
$query_reset = "TRUNCATE TABLE transaksi_temp";
$result_reset = mysqli_query($connection, $query_reset);

if ($result_reset) {
    echo "Keranjang belanja berhasil direset.";
} else {
    echo "Gagal mereset keranjang belanja.";
}

mysqli_close($connection);
?>
