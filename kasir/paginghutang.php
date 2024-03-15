<?php 
$koneksi = mysqli_connect('localhost', 'root', '', 'kasir_zibran');
global $koneksi;

$data = mysqli_query($koneksi, "SELECT * FROM hutang");
$jumlah_data = mysqli_num_rows($data);

// Set nilai $batas sesuai dengan jumlah data yang ada
$batas = $jumlah_data;

$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

$total_halaman = ceil($jumlah_data / $batas);

$nomor = $halaman_awal + 1;


// cari
if (isset($_POST['go'])) {
  $cari = $_POST['cari'];
  $data_laporan = mysqli_query($koneksi, "SELECT * FROM hutang WHERE nama LIKE '%".$cari."%'");
}else{
  $data_laporan = mysqli_query($koneksi, "SELECT * FROM hutang LIMIT $halaman_awal, $batas");
}


foreach ($data_laporan as $pro):
  ?>
    



<tr>
                              <td><?= $i++;  ?></td>
                              <!-- <td><*/?=  $pro['id'];?></td> -->
                              <td><?= $pro['nama'];?></td>
                              <td><?= $pro['tanggal'];?></td>
                              <td>Rp <?= $pro['nominal'];?></td>
                              <td>Rp <?= $pro['bayar'];?></td>
                              <td style="color: <?= ($pro['status'] === 'Lunas') ? 'green' : 'red'; ?>; font-weight: bold;">
                                  <?= $pro['status']; ?>
                                </td>
                                <td>
                                  <?php if ($pro['status'] === 'Lunas') : ?>
                                    <button class="btn btn-secondary" disabled>Lunas</button>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?= $pro['id']; ?>"><i class="fa fa-trash"></i></button>
                                  <?php else : ?>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bayarModal<?= $pro['id']; ?>">Bayar</button>
                                    
                                  <?php endif; ?>
                                </td>
                              </tr>
                              <!-- Modal untuk form pelunasan -->
    <div class="modal fade" id="bayarModal<?= $pro['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="bayarModalLabel<?= $pro['id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bayarModalLabel<?= $pro['id']; ?>">Form Pelunasan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Isi form dengan input yang dibutuhkan untuk melakukan pelunasan -->
                    <form method="post" action="proses_pelunasan.php">
                        <div>
                          <input type="hidden" name="hutang_id" value="<?= $pro['id']; ?>">
                          <input style="width: 300px; height: 30px;" type="number" name="jumlah_pelunasan" placeholder="Jumlah Pelunasan" required>
                        </div>
                        <div style="padding-top: 10px;" >
                          <button type="submit" class="btn btn-primary">Bayar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal untuk konfirmasi delete -->
    <div class="modal fade" id="deleteModal<?= $pro['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $pro['id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel<?= $pro['id']; ?>">Konfirmasi Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="proses_delete.php?id=<?= $pro['id']; ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
                              <?php endforeach; ?>
