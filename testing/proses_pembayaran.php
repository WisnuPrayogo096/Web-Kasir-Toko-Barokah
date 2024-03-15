<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_pembayaran = $_POST["id_pembayaran"];
    $nominal_pembayaran = $_POST["nominal_pembayaran"];

    // Koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kasir_zibran";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data hutang
    $sql_check_hutang = "SELECT nominal, bayar FROM hutang WHERE id = $id_pembayaran";
    $result = $conn->query($sql_check_hutang);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Perhitungan sisa hutang setelah pembayaran
        $nominal_hutang = (int)$row["nominal"];
        $bayar_hutang = (int)$row["bayar"];
        $sisa_hutang = $nominal_hutang - $bayar_hutang;

        // Update data pembayaran
        $nominal_pembayaran = (int)$nominal_pembayaran;
        $sql_update = "UPDATE hutang SET bayar = bayar + $nominal_pembayaran WHERE id = $id_pembayaran";
        
        if ($conn->query($sql_update) === TRUE) {
            echo "Pembayaran berhasil";

            // Periksa status hutang
            if ($sisa_hutang <= $nominal_pembayaran) {
                // Jika sisa hutang kurang dari atau sama dengan pembayaran, tandai sebagai Lunas
                $sql_update_status = "UPDATE hutang SET status = 'Lunas' WHERE id = $id_pembayaran";
                if ($conn->query($sql_update_status) !== TRUE) {
                    echo "Error updating status: " . $conn->error;
                }
            }
        } else {
            echo "Error: " . $sql_update . "<br>" . $conn->error;
        }
    } else {
        echo "Data hutang tidak ditemukan";
    }

    // Tutup koneksi
    $conn->close();
}
?>
