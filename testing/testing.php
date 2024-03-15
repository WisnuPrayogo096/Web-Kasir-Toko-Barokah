<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Hutang</title>
</head>
<body>
    <h1>Data Hutang</h1>
    <?php
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

    // Menampilkan data hutang
    $sql = "SELECT id, nama, nominal, bayar, status FROM hutang";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Nominal</th>
                    <th>Bayar</th>
                    <th>Status</th>
                </tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["id"]."</td>
                    <td>".$row["nama"]."</td>
                    <td>".$row["nominal"]."</td>
                    <td>".$row["bayar"]."</td>
                    <td>".$row["status"]."</td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "Tidak ada data hutang";
    }

    // Tutup koneksi
    $conn->close();
    ?>

    <h2>Form Pembayaran</h2>
    <form action="proses_pembayaran.php" method="post">
        <label for="id_pembayaran">ID Pembayaran:</label>
        <input type="text" id="id_pembayaran" name="id_pembayaran" required>

        <label for="nominal_pembayaran">Nominal Pembayaran:</label>
        <input type="text" id="nominal_pembayaran" name="nominal_pembayaran" required>

        <button type="submit">Bayar</button>
    </form>
</body>
</html>
