<?php
include "../koneksi.php";

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchTerm = mysqli_real_escape_string($connection, $searchTerm);

    $query = "SELECT tb_produk.*, barcode.no_barcode FROM tb_produk 
              LEFT JOIN barcode ON tb_produk.id = barcode.id_barang
              WHERE tb_produk.nm_produk LIKE '%$searchTerm%' 
              OR tb_produk.kategori LIKE '%$searchTerm%'
              OR barcode.no_barcode LIKE '%$searchTerm%'";

    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['nm_produk'] . "</td>";
            echo "<td>" . $row['kategori'] . "</td>";
            echo "<td>" . $row['stok'] . "</td>";
            echo "<td>" . $row['harga'] . "</td>";
            $stok = $row['stok'];
            if ($stok <= 1) {
                echo "<td><button class='btn btn-primary add-button' disabled><i class='fa fa-plus'></button></td>";
            } else {
                echo "<td><button class='btn btn-primary add-button' data-kdproduk='" . $row['kdproduk'] . "'><i class='fa fa-plus'></button></td>";
            }
            // echo "<td>" . $row['no_barcode'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No products found.";
    }
}
?>
