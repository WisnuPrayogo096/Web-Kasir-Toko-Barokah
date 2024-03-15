<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_pembayaran = $_POST["hutang_id"];
    $nominal_pembayaran = $_POST["jumlah_pelunasan"];

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
            // Periksa status hutang
        if ($sisa_hutang == $nominal_pembayaran) {
            // Jika sisa hutang kurang dari atau sama dengan pembayaran, tandai sebagai Lunas
            echo '<script>alert("Pelunasan berhasil."); window.location.href = "hutang.php";</script>';
            $sql_update = "UPDATE hutang SET bayar = bayar + $nominal_pembayaran WHERE id = $id_pembayaran";
            if ($conn->query($sql_update) === TRUE) {
                $sql_update_status = "UPDATE hutang SET status = 'Lunas' WHERE id = $id_pembayaran";
                if ($conn->query($sql_update_status) !== TRUE) {
                    echo '<script>alert("Error: ' . $conn->error . '"); window.location.href = "hutang.php";</script>';
                }
            }
        } else if ($sisa_hutang < $nominal_pembayaran) {
            echo '<script>alert("Tidak boleh melebihi nominal hutang, harus pas."); history.back();;</script>';
        } else if ($sisa_hutang > $nominal_pembayaran) {
            $sql_update = "UPDATE hutang SET bayar = bayar + $nominal_pembayaran WHERE id = $id_pembayaran";
            if ($conn->query($sql_update) === TRUE) {
                $sql_update_status = "UPDATE hutang SET status = 'Belum Lunas' WHERE id = $id_pembayaran";
                if ($conn->query($sql_update_status) !== TRUE) {
                    echo '<script>alert("Error: ' . $conn->error . '"); window.location.href = "hutang.php";</script>';
                }
            }
            echo '<script>alert("Pembayaran secara cicil berhasil."); window.location.href = "hutang.php";</script>';
        }
    } else {
        echo '<script>alert("Data hutang tidak ditemukan."); window.location.href = "hutang.php";</script>';
    }

    // Tutup koneksi
    $conn->close();
}
?>
