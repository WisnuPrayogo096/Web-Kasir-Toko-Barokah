<!DOCTYPE html>
<html>
<head>
   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-d3JJQPp0PzwR5V6VlVwR2GhTGlzDkljbknfcoC1qFxOJ/RYh6HZAG5Dpjz5gmvK6+PEdijj3dXq6uavq7mD7Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<style>
  .container {
    display: flex;
    justify-content: space-between;
    max-width: 800px;
    margin: 0 auto;
  }

  .left-container {
    width: 48%;
  }

  .right-container {
    width: 48%;
  }

  .total-container, #kembalian-container {
    background-color: #dcdcdc;
    padding: 6px;
    border-radius: 3px;
    margin-bottom: 10px;
  }

  h3 {
    font-weight: bold;
    text-align: center;
    margin-left: 10px;
    margin-top: 0;
    font-size: 16px;
  }

  #inputBayar {
    width: 100%;
    height: 40px;
    font-size: 18px;
    margin-top: 10px;
    padding: 5px;
    box-sizing: border-box;
  }

  .btn-container {
    margin-top: 10px;
    display: flex;
    justify-content: space-between;
  }

  .btn {
    flex: 1;
    margin-right: 10px;
  }
</style>

<body>
    <?php include "template/header.php"; ?>
    <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1.2.0</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="well">
    <div class="container-fluid">
          
    <input type="text" id="searchInput" placeholder="Search product" style="margin-bottom: 10px; width: 300px; height: 30px;">
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Add to Cart</th>
            </tr>
        </thead>
        <tbody id="productList">
           
        </tbody>
    </table>
    </div>

    <div class="row">
        <b style="font-size: 20px; margin-left: 20px; text-decoration: underline;">Keranjang Belanja</b>
        <div class="col-sm-12">
          <div class="well">
             <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
    <thead>
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="transactionList">
    
    <?php
include "../koneksi.php";
$query_transaksi = "SELECT * FROM transaksi_temp";
$result_transaksi = mysqli_query($connection, $query_transaksi);
$total = 0;
if (mysqli_num_rows($result_transaksi) > 0) {
    while ($row = mysqli_fetch_assoc($result_transaksi)) {
        $total += $row['total'];
    }
}
?>
</tbody>
</table>
<div class="container">
  <div class="left-container">
    <div class="total-container">
      <h3 style="font-size: 30px; font-weight:bold;">Total : Rp <span id="total"><?php echo $total; ?></span></h3>
    </div>
    <div id="kembalian-container">
      <h3 style="font-size: 25px">Kembalian : Rp <span id="kembalian"></span></h3>
    </div>
  </div>
  <div class="right-container">
    <input type="number" id="inputBayar" placeholder="Masukkan Nominal">
    <b style="font-size: 13px;">**Jika pelanggan hutang, tidak perlu dimasukkan nominal bayar nya. Langsung klik button hutang.</b>
    <div class="btn-container">
      <button id="btnBayar" class="btn btn-success">Bayar</button>
      <button id="btnHutang" class="btn btn-warning">Hutang</button>
      <button id="printButton" class="btn btn-primary" disabled>Print</button>
      <button id="btnReset" class="btn btn-info">Reload</button>
    </div>
  </div>
</div>

<form action="print.php" method="post" target="_blank" id="printForm">
  <input type="hidden" name="total" id="totalHidden">
  <input type="hidden" name="inputBayar" id="inputBayarHidden">
  <input type="hidden" name="kembalian" id="kembalianHidden">
</form>

          </div>
        </div>
              </div> 
          </section>
           </div>

    <script>

        function resetCart() {
            $.ajax({
                url: 'reset_cart.php', // Ganti ini dengan file PHP yang menangani reset keranjang belanja
                type: 'POST',
                success: function(response) {
                    alert(response); 
                    loadTransactionList(); 
                    loadProductList($('#searchInput').val().trim()); // Load ulang produk setelah reset
                },
                error: function() {
                    alert('Failed to reset cart.');
                }
            });
        }

        function loadProductList(searchTerm) {
            $.ajax({
                url: `search_products.php?search=${searchTerm}`,
                type: 'GET',
                success: function(response) {
                    const productList = $('#productList');
                    productList.html(response);
                    addAddToCartListeners();
                },
                error: function() {
                    const productList = $('#productList');
                    productList.html('Error loading products.');
                }
            });
        }

        function loadTransactionList() {
            $.ajax({
                url: 'get_transactions.php',
                type: 'GET',
                success: function(response) {
                    const transactionList = $('#transactionList');
                    transactionList.html(response);
                    addUpdateListeners(); 
                },
                error: function() {
                    const transactionList = $('#transactionList');
                    transactionList.html('Error loading transactions.');
                }
            });
        }

        function addAddToCartListeners() {
            $('.add-button').on('click', function() {
                const kdproduk = $(this).data('kdproduk');
                addToCart(kdproduk);
            });
        }

        function addUpdateListeners() {
    $('.update-button').on('click', function() {
        const tr = $(this).closest('tr');
        const id = tr.find('.update-button').data('id');
        const newQuantity = tr.find('.quantity-input').val().trim();

        updateQuantity(id, newQuantity);
    });
}

        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().trim();
            loadProductList(searchTerm);
        });

    
        function addToCart(kdproduk) {
            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: {
                    kdproduk: kdproduk,
                    jumlah_beli: 1
                },
                success: function(response) {
                    alert(response); 
                    loadProductList($('#searchInput').val().trim()); 
                    loadTransactionList();
                    location.reload();
                },
                error: function() {
                    alert('Failed to add to cart.');
                }
            });
        }

     
        function updateQuantity(id, newQuantity) {
    $.ajax({
        url: 'update_quantity.php',
        type: 'POST',
        data: {
            id: id,
            newQuantity: newQuantity
        },
        success: function(response) {
            alert(response); 
            loadTransactionList();
            location.reload();
        },
        error: function() {
            alert('Failed to update quantity.');
        }
    });
}

       
        $(document).ready(function() {
            loadTransactionList();
        });

        function updateTotal() {
    let total = 0;
    const rows = document.querySelectorAll("#transactionList tr");
    rows.forEach((row) => {
      const price = parseFloat(row.querySelector(".price").innerText);
      const quantity = parseInt(row.querySelector(".quantity").innerText);
      total += price * quantity;
    });
    document.getElementById("total").innerText = total.toFixed(2);
  }

  
  const quantityInputs = document.querySelectorAll(".quantity-input");
  quantityInputs.forEach((input) => {
    input.addEventListener("change", () => {
      updateTotal();
    });
  });

 
  const addToCartButtons = document.querySelectorAll(".add-button");
  addToCartButtons.forEach((button) => {
    button.addEventListener("click", () => {
      updateTotal();
    });
  });

  document.getElementById("btnReset").addEventListener("click", function () {
        location.reload(); // Refresh the page
    });

  document.getElementById("btnHutang").addEventListener("click", function () {
    const total = parseFloat(document.getElementById("total").innerText);
    // Automatically fill or synchronize the inputBayar with the total value
    document.getElementById("inputBayar").value = total;
    const inputBayar = parseFloat(document.getElementById("inputBayar").value);
    const printButton = document.getElementById("printButton");
    printButton.disabled = true;
    if (isNaN(inputBayar) || inputBayar < total) {
        alert("Pembayaran tidak valid. Pastikan jumlah bayar mencukupi.");
        return;
    }

    const kembalian = inputBayar - total;

    // Assuming you have an AJAX function to insert data into the "hutang" table
    // Prompt for the name before inserting data into the "hutang" table
    const nama = prompt("Masukkan nama untuk transaksi hutang:");

    if (!nama) {
        alert("Nama harus diisi untuk melanjutkan transaksi.");
        return;
    }
    // AJAX untuk menyimpan data hutang
    $.ajax({
            url: "hutang/insert_hutang.php",
            type: "POST",
            data: {
                nama: nama,
                nominal: inputBayar,
                status: "Belum Lunas"
            },
            success: function(response) {
                alert("Transaksi hutang berhasil dengan atas nama: " + nama);
                document.getElementById("kembalian").innerText = kembalian.toFixed(2);

                // Setelah transaksi hutang berhasil, jalankan operasi lain seperti update stok
                $.ajax({
                    url: "update_stok.php",
                    type: "POST",
                    data: { action: "update_stok" },
                    success: function(response) {
                        alert("Transaksi berhasil. Data telah siap di print sebagai nota.");
                        // ...

                        // Setelah update stok berhasil, pindahkan data ke laporan penjualan
                        $.ajax({
                            url: "move_to_laporan.php",
                            type: "POST",
                            data: { action: "move_to_laporan" },
                            success: function(moveResponse) {
                                alert("Transaksi berhasil. Data telah dipindahkan ke laporan_penjualan.");
                                // ...

                                // Aktifkan tombol "Print" setelah data berhasil dipindahkan ke laporan penjualan
                                printButton.disabled = false;
                                btnHutang = document.getElementById("btnHutang");
                                btnHutang.disabled = true;

                                // resetCart(); // Panggil fungsi reset setelah aksi hutang berhasil
                            },
                            error: function() {
                                alert("Gagal memindahkan data ke laporan_penjualan.");
                            }
                        });

                    },
                    error: function() {
                        alert("Gagal mengupdate stok.");
                    }
                });

            },
            error: function() {
                alert("Gagal menyimpan hutang.");
            }
        });
        
        document.getElementById("printButton").addEventListener("click", function() {
        const total = parseFloat(document.getElementById("total").innerText);
        const inputBayar = parseFloat(document.getElementById("inputBayar").value);
        const kembalian = inputBayar - total;
        document.getElementById("kembalian").innerText = kembalian.toFixed(2);

        document.getElementById("totalHidden").value = total;
        document.getElementById("inputBayarHidden").value = inputBayar;
        document.getElementById("kembalianHidden").value = kembalian.toFixed(2);

        document.getElementById("printForm").submit();

        });
});

        document.getElementById("btnBayar").addEventListener("click", function () {
        const total = parseFloat(document.getElementById("total").innerText);
        const bayar = parseFloat(document.getElementById("inputBayar").value);
        const printButton = document.getElementById("printButton");
        printButton.disabled = true;
        if (isNaN(bayar) || bayar < total) {
            alert("Pembayaran tidak valid. Pastikan jumlah bayar mencukupi.");
            printButton.disabled = true;
            return;
        }

        const kembalian = bayar - total;
        document.getElementById("kembalian").innerText = kembalian.toFixed(2);

        document.getElementById("totalHidden").value = total;
        document.getElementById("inputBayarHidden").value = inputBayar;
        document.getElementById("kembalianHidden").value = kembalian.toFixed(2);

        $.ajax({
            url: "update_stok.php",
            type: "POST",
            data: { action: "update_stok" },
            success: function(response) {
                alert("Transaksi berhasil. Data telah siap di print sebagai nota.");

                $.ajax({
                    url: "move_to_laporan.php",
                    type: "POST",
                    data: { action: "move_to_laporan" },
                    success: function(moveResponse) {
                        alert("Transaksi berhasil. Data telah dipindahkan ke laporan_penjualan.");
                        printButton.disabled = false;
                        btnBayar = document.getElementById("btnBayar");
                        btnBayar.disabled = true;
                    },
                    error: function() {
                        alert("Gagal memindahkan data ke laporan_penjualan.");
                    }
                });

            },
            error: function() {
                alert("Gagal mengupdate stok.");
            }
        });

        document.getElementById("printButton").addEventListener("click", function() {
        const total = parseFloat(document.getElementById("total").innerText);
        const inputBayar = parseFloat(document.getElementById("inputBayar").value);
        const kembalian = inputBayar - total;
        document.getElementById("kembalian").innerText = kembalian.toFixed(2);

        document.getElementById("totalHidden").value = total;
        document.getElementById("inputBayarHidden").value = inputBayar;
        document.getElementById("kembalianHidden").value = kembalian.toFixed(2);

        document.getElementById("printForm").submit();

        });
    });

        function handleDeleteButtonClick(id) {
    if (confirm("Apakah Anda yakin ingin menghapus transaksi ini?")) {
        $.ajax({
            url: 'delete_transaction.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                alert(response); 
                loadTransactionList(); 
            },
            error: function() {
                alert('Failed to delete transaction.');
            }
        });
    }
}


$('.delete-button').on('click', function() {
    const id = $(this).data('id');
    handleDeleteButtonClick(id);
});
    </script>
    <?php include "template/footer.php"; ?>
</body>
</html>
