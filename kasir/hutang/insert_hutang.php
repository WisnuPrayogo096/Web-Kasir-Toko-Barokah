<?php
include "../../koneksi.php";

if (isset($_POST['nama']) && isset($_POST['nominal']) && isset($_POST['status'])) {
    $nama = $_POST['nama'];
    $nominal = $_POST['nominal'];
    $status = $_POST['status'];

    // Menetapkan zona waktu ke WIB
    date_default_timezone_set('Asia/Jakarta');

    // Mendapatkan tanggal dan waktu saat ini dalam format H:M | DD/MM/YY
    $tanggal_waktu = date('H:i | d/m/y');

    // Assuming you have a table structure with columns: id, nama, nominal, status, tanggal
    $insert_query = "INSERT INTO hutang (nama, nominal, status, tanggal) VALUES ('$nama', $nominal, '$status', '$tanggal_waktu')";

    if (mysqli_query($connection, $insert_query)) {
        echo "Transaksi hutang berhasil disimpan.";
    } else {
        echo "Gagal menyimpan hutang: " . mysqli_error($connection);
    }
} else {
    echo "Invalid request.";
}
?>
