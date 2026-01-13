<div class="container">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Article
    </button>
    <div class="row">
        <div class="table-responsive" id="article_data">
           
        </div>
        <!-- Awal Modal Tambah-->
        <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary-subtle text-white">
                        <h1 class="modal-title fs-5 text-dark" id="staticBackdropLabel">
                            <i class="bi bi-robot"></i> Tambah Article dengan AI
                        </h1>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <!-- AI Disclaimer -->
                            <div class="alert alert-warning alert-dismissible fade show py-2 mb-2" role="alert">
                                <h6 class="alert-heading mb-1" style="font-size: 0.9rem;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Disclaimer Penggunaan AI
                                </h6>
                                <small style="font-size: 0.75rem;">
                                    <ul class="mb-0 ps-3">
                                        <li>Artikel dihasilkan oleh <strong>Artificial Intelligence (Gemini API)</strong></li>
                                        <li>Hasil AI mungkin <strong>tidak 100% akurat</strong> dan perlu verifikasi</li>
                                        <li>Anda dapat <strong>mengedit hasil</strong> sebelum menyimpan artikel</li>
                                        <li>AI hanya sebagai <strong>alat bantu</strong>, bukan pengganti kreativitas manusia</li>
                                    </ul>
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label fw-bold mb-1">Judul</label>
                                <input type="text" class="form-control form-control-sm" id="judulInput" name="judul" placeholder="Tuliskan Judul Artikel" required>
                            </div>
                            
                            <!-- Tombol Generate AI -->
                            <div class="mb-2">
                                <button type="button" class="btn btn-primary btn-sm" id="btnGenerateAI">
                                    <i class="bi bi-magic"></i> Generate Isi Artikel dengan AI
                                </button>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label fw-bold mb-1">Isi</label>
                                <textarea class="form-control form-control-sm" id="isiTextarea" placeholder="Tuliskan Isi Artikel atau klik 'Generate dengan AI'" name="isi" rows="5" required></textarea>
                            </div>
                            
                            <!-- Alert untuk AI status -->
                            <div id="aiAlert" class="alert alert-info py-2 mb-2" style="display:none;">
                                <small><span id="aiMessage"></span></small>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label mb-1">Gambar (Opsional)</label>
                                <input type="file" class="form-control form-control-sm" name="gambar">
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
        <!-- Akhir Modal Tambah-->
    </div>
</div>

<script>
$(document).ready(function(){
    load_data();
    function load_data(hlm){
        $.ajax({
            url : "article_data.php",
            method : "POST",
            data : {
                hlm: hlm
            },
            success : function(data){
                $('#article_data').html(data);
            }
        })
    } 
    $(document).on('click', '.halaman', function(){
        var hlm = $(this).attr("id");
        load_data(hlm);
    });
    
    // AI Article Generator untuk Modal TAMBAH
    $('#btnGenerateAI').click(function() {
        var judul = $('#judulInput').val().trim();
        
        if (judul.length < 5) {
            alert('Masukkan judul terlebih dahulu (minimal 5 karakter)');
            $('#judulInput').focus();
            return;
        }
        
        
        // Show loading
        $('#aiAlert').show().css('display', 'block').removeClass('alert-success alert-danger').addClass('alert-info');
        $('#aiMessage').text('⏳ AI sedang generate artikel, tunggu sebentar...');
        $('#btnGenerateAI').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Generating...');
        
        // Call AI API
        $.ajax({
            url: 'ai_article_generator.php',
            method: 'POST',
            data: { judul: judul },
            dataType: 'json',
            timeout: 60000,
            success: function(response) {
                if (response.success) {
                    $('#isiTextarea').val(response.article);
                    $('#aiAlert').removeClass('alert-info').addClass('alert-success');
                    $('#aiMessage').text(response.message);
                    
                    // Hide alert after 5 seconds
                    setTimeout(function() {
                        $('#aiAlert').fadeOut();
                    }, 5000);
                } else {
                    $('#aiAlert').removeClass('alert-info').addClass('alert-danger');
                    $('#aiMessage').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#aiAlert').removeClass('alert-info').addClass('alert-danger');
                $('#aiMessage').text('❌ Error: ' + error);
            },
            complete: function() {
                $('#btnGenerateAI').prop('disabled', false).html('<i class="bi bi-magic"></i> Generate Isi Artikel dengan AI');
            }
        });
    });
    
    // AI Article Generator untuk Modal EDIT (menggunakan event delegation)
    $(document).on('click', '.btnGenerateEditAI', function() {
        var articleId = $(this).data('id');
        var judul = $('#judulEdit' + articleId).val().trim();
        
        if (judul.length < 5) {
            alert('Masukkan judul terlebih dahulu (minimal 5 karakter)');
            $('#judulEdit' + articleId).focus();
            return;
        }
        
        var $btn = $(this);
        var $alert = $('#aiAlertEdit' + articleId);
        var $textarea = $('#isiEditTextarea' + articleId);
        
        
        // Show loading
        $alert.show().css('display', 'block').removeClass('alert-success alert-danger').addClass('alert-info');
        $alert.find('.aiEditMessage').text('⏳ AI sedang generate artikel, tunggu sebentar...');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Generating...');
        
        // Call AI API
        $.ajax({
            url: 'ai_article_generator.php',
            method: 'POST',
            data: { judul: judul },
            dataType: 'json',
            timeout: 60000,
            success: function(response) {
                if (response.success) {
                    $textarea.val(response.article);
                    $alert.removeClass('alert-info').addClass('alert-success');
                    $alert.find('.aiEditMessage').text(response.message);
                    
                    // Hide alert after 5 seconds
                    setTimeout(function() {
                        $alert.fadeOut();
                    }, 5000);
                } else {
                    $alert.removeClass('alert-info').addClass('alert-danger');
                    $alert.find('.aiEditMessage').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                $alert.removeClass('alert-info').addClass('alert-danger');
                $alert.find('.aiEditMessage').text('❌ Error: ' + error);
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-magic"></i> Generate Ulang Isi Artikel dengan AI');
            }
        });
    });
});
</script>

<?php
include "upload_foto.php";

//jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    //jika ada file yang dikirim  
    if ($nama_gambar != '') {
        //panggil function upload_foto untuk cek spesifikasi file yg dikirimkan user
        //function ini memiliki 2 keluaran yaitu status dan message
        $cek_upload = upload_foto($_FILES["gambar"]);

        //cek status true/false
        if ($cek_upload['status']) {
            //jika true maka message berisi nama file gambar
            $gambar = $cek_upload['message'];
        } else {
            //jika true maka message berisi pesan error, tampilkan dalam alert
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='admin.php?page=article';
            </script>";
            die;
        }
    }

    //cek apakah ada id yang dikirimkan dari form
    if (isset($_POST['id'])) {
        //jika ada id, lakukan update data dengan id tersebut
        $id = $_POST['id'];

        if ($nama_gambar == '') {
            //jika tidak ganti gambar
            $gambar = $_POST['gambar_lama'];
        } else {
            //jika ganti gambar, hapus gambar lama
            unlink("img/" . $_POST['gambar_lama']);
        }

        $stmt = $conn->prepare("UPDATE article 
                                SET 
                                judul =?,
                                isi =?,
                                gambar = ?,
                                tanggal = ?,
                                username = ?
                                WHERE id = ?");

        $stmt->bind_param("sssssi", $judul, $isi, $gambar, $tanggal, $username, $id);
        $simpan = $stmt->execute();
    } else {
        //jika tidak ada id, lakukan insert data baru
        $stmt = $conn->prepare("INSERT INTO article (judul,isi,gambar,tanggal,username)
                                VALUES (?,?,?,?,?)");

        $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);
        $simpan = $stmt->execute();
    }

    if ($simpan) {
        echo "<script>
            alert('Simpan data sukses');
            document.location='admin.php?page=article';
        </script>";
    } else {
        echo "<script>
            alert('Simpan data gagal');
            document.location='admin.php?page=article';
        </script>";
    }

    $stmt->close();
    $conn->close();
}

//jika tombol hapus diklik
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        //hapus file gambar
        unlink("img/" . $gambar);
    }

    $stmt = $conn->prepare("DELETE FROM article WHERE id =?");

    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    if ($hapus) {
        echo "<script>
            alert('Hapus data sukses');
            document.location='admin.php?page=article';
        </script>";
    } else {
        echo "<script>
            alert('Hapus data gagal');
            document.location='admin.php?page=article';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>