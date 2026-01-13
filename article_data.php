 <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th class="w-25">Judul</th>
                        <th class="w-75">Isi</th>
                        <th class="w-25">Gambar</th>
                        <th class="w-25">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "koneksi.php";
                    $hlm = (isset($_POST['hlm'])) ? $_POST['hlm'] : 1;
                    $limit = 3;
                    $limit_start = ($hlm - 1) * $limit;
                    $no = $limit_start + 1;

                    $sql = "SELECT * FROM article ORDER BY tanggal DESC LIMIT $limit_start, $limit";
                    $hasil = $conn->query($sql);
  
                    while ($row = $hasil->fetch_assoc()) {
                        // Buat excerpt untuk isi artikel - maksimal 150 karakter
                        $excerpt = substr($row["isi"], 0, 150);
                        if (strlen($row["isi"]) > 150) {
                            $excerpt .= "...";
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= $row["judul"] ?></strong>
                                <br>pada : <?= $row["tanggal"] ?>
                                <br>oleh : <?= $row["username"] ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= $excerpt ?></small>
                            </td>
                            <td>
                                <?php
                                if ($row["gambar"] != '') {
                                    if (file_exists('img/' . $row["gambar"] . '')) {
                                ?>
                                        <img src="img/<?= $row["gambar"] ?>" width="100">
                                <?php
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a href="#" title="edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row["id"] ?>"><i class="bi bi-pencil"></i></a>
                                <a href="#" title="delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row["id"] ?>"><i class="bi bi-x-circle"></i></a>
                                <!-- Awal Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary-subtle">
                                            <h1 class="modal-title fs-5 text-dark" id="staticBackdropLabel">
                                                <i class="bi bi-pencil-square"></i> Edit Article dengan AI
                                            </h1>
                                            <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <!-- AI Disclaimer for Edit -->
                                                <div class="alert alert-warning alert-dismissible fade show py-2 mb-2" role="alert">
                                                    <h6 class="alert-heading mb-1" style="font-size: 0.9rem;">
                                                        <i class="bi bi-info-circle-fill"></i> AI untuk Re-generate Artikel
                                                    </h6>
                                                    <small style="font-size: 0.75rem;">
                                                        <ul class="mb-0 ps-3">
                                                            <li>Klik tombol <strong>"Generate Ulang dengan AI"</strong> untuk membuat artikel baru berdasarkan judul</li>
                                                            <li>Artikel lama akan <strong>diganti</strong> dengan hasil AI</li>
                                                            <li>Anda dapat <strong>mengedit hasil AI</strong> sebelum menyimpan</li>
                                                        </ul>
                                                    </small>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold mb-1">Judul</label>
                                                    <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                                    <input type="text" class="form-control form-control-sm judulEdit" id="judulEdit<?= $row["id"] ?>" name="judul" value="<?= $row["judul"] ?>" required>
                                                </div>
                                                
                                                <!-- Tombol Generate AI untuk Edit -->
                                                <div class="mb-2">
                                                    <button type="button" class="btn btn-primary btn-sm btnGenerateEditAI" data-id="<?= $row["id"] ?>">
                                                        <i class="bi bi-magic"></i> Generate Ulang dengan AI
                                                    </button>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold mb-1">Isi</label>
                                                    <textarea class="form-control form-control-sm isiEditTextarea" id="isiEditTextarea<?= $row["id"] ?>" name="isi" rows="5" required><?= $row["isi"] ?></textarea>
                                                </div>
                                                
                                                <!-- Alert untuk AI status di Edit Modal -->
                                                <div id="aiAlertEdit<?= $row["id"] ?>" class="alert alert-info py-2 mb-2" style="display:none;">
                                                    <small><i class="bi bi-hourglass-split"></i> <span class="aiEditMessage">AI sedang generate...</span></small>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label mb-1">Ganti Gambar</label>
                                                        <input type="file" class="form-control form-control-sm" name="gambar">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label mb-1">Gambar Lama</label>
                                                        <div>
                                                            <?php
                                                            if ($row["gambar"] != '') {
                                                                if (file_exists('img/' . $row["gambar"] . '')) {
                                                            ?>
                                                                    <img src="img/<?= $row["gambar"] ?>" width="80" class="img-thumbnail">
                                                            <?php
                                                                }
                                                            } else {
                                                                echo '<small class="text-muted">Tidak ada gambar</small>';
                                                            }
                                                            ?>
                                                            <input type="hidden" name="gambar_lama" value="<?= $row["gambar"] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                <input type="submit" value="Simpan" name="simpan" class="btn btn-primary btn-sm">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Modal Edit -->

                            <!-- Awal Modal Hapus -->
                            <div class="modal fade" id="modalHapus<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Article</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="formGroupExampleInput" class="form-label">Yakin akan menghapus artikel "<strong><?= $row["judul"] ?></strong>"?</label>
                                                    <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                                    <input type="hidden" name="gambar" value="<?= $row["gambar"] ?>">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">batal</button>
                                                <input type="submit" value="hapus" name="hapus" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Modal Hapus -->
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <?php 
$sql1 = "SELECT * FROM article";
$hasil1 = $conn->query($sql1); 
$total_records = $hasil1->num_rows;
?>
<p>Total article : <?php echo $total_records; ?></p>
<nav class="mb-2">
    <ul class="pagination justify-content-end">
    <?php
        $jumlah_page = ceil($total_records / $limit);
        $jumlah_number = 1; //jumlah halaman ke kanan dan kiri dari halaman yang aktif
        $start_number = ($hlm > $jumlah_number)? $hlm - $jumlah_number : 1;
        $end_number = ($hlm < ($jumlah_page - $jumlah_number))? $hlm + $jumlah_number : $jumlah_page;

        if($hlm == 1){
            echo '<li class="page-item disabled"><a class="page-link" href="#">First</a></li>';
            echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
        } else {
            $link_prev = ($hlm > 1)? $hlm - 1 : 1;
            echo '<li class="page-item halaman" id="1"><a class="page-link" href="#">First</a></li>';
            echo '<li class="page-item halaman" id="'.$link_prev.'"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
        }

        for($i = $start_number; $i <= $end_number; $i++){
            $link_active = ($hlm == $i)? ' active' : '';
            echo '<li class="page-item halaman '.$link_active.'" id="'.$i.'"><a class="page-link" href="#">'.$i.'</a></li>';
        }

        if($hlm == $jumlah_page){
            echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
            echo '<li class="page-item disabled"><a class="page-link" href="#">Last</a></li>';
        } else {
        $link_next = ($hlm < $jumlah_page)? $hlm + 1 : $jumlah_page;
            echo '<li class="page-item halaman" id="'.$link_next.'"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
            echo '<li class="page-item halaman" id="'.$jumlah_page.'"><a class="page-link" href="#">Last</a></li>';
        }
    ?>
    </ul>
</nav>