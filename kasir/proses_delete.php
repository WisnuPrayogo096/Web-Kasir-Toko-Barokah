<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id_to_delete = $_GET["id"];

    // Lakukan koneksi ke database dan lakukan penghapusan data sesuai dengan ID
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kasir_zibran";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Hapus data sesuai dengan ID
    $sql_delete = "DELETE FROM hutang WHERE id = $id_to_delete";

    if ($conn->query($sql_delete) === TRUE) {
        echo '<script>alert("Data berhasil dihapus."); window.location.href = "hutang.php";</script>';
    } else {
        echo '<script>alert("Error: ' . $conn->error . '"); window.location.href = "hutang.php";</script>';
    }

    $conn->close();
} else {
    // Redirect jika tidak ada ID yang diberikan
    header("Location: hutang.php");
    exit();
}
?>
