<?php
include "koneksi.php";

/* ===================================================
   PROSES AKSI (POST / GET hapus)
   =================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // TAMBAH
    if(isset($_POST['tambah'])){
        $nama = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "img/".$nama);
        mysqli_query($conn,"INSERT INTO gallery (gambar) VALUES ('$nama')");
    }

    // EDIT
    if(isset($_POST['edit'])){
        $id = $_POST['id'];
        if($_FILES['gambar']['name']!=""){
            $nama = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "img/".$nama);
        } else {
            $nama = $_POST['gambar_lama'];
        }
        mysqli_query($conn,"UPDATE gallery SET gambar='$nama' WHERE id='$id'");
    }

    header("location:admin.php?page=gallery");
    exit;
}

// HAPUS
if(isset($_GET['hapus'])){
    mysqli_query($conn,"DELETE FROM gallery WHERE id='".$_GET['hapus']."'");
    header("location:admin.php?page=gallery");
    exit;
}

/* ===================================================
   BAGIAN AJAX (HANYA TABEL & PAGINATION)
   =================================================== */

$batas = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$posisi = ($page-1)*$batas;

$data = mysqli_query($conn,"SELECT * FROM gallery ORDER BY id DESC LIMIT $posisi,$batas");
$no = $posisi + 1;
?>

<table class="table">
<thead class="table-secondary">
<tr>
    <th>No</th>
    <th>Gambar</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while($d=mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $no++ ?></td>
    <td><img src="img/<?= $d['gambar'] ?>" width="120"></td>
    <td>
        <button class="btn btn-warning btn-sm btn-edit"
                data-id="<?= $d['id'] ?>"
                data-gambar="<?= $d['gambar'] ?>"
                data-bs-toggle="modal"
                data-bs-target="#modalEdit">Edit</button>

        <a href="gallery_data.php?hapus=<?= $d['id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Hapus data?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</tbody>
</table>

<?php
$jmldata = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM gallery"));
$jmlhal = ceil($jmldata/$batas);
?>

<ul class="pagination justify-content-center">
<li class="page-item"><a class="page-link" data-page="1">First</a></li>
<li class="page-item"><a class="page-link" data-page="<?= ($page>1)?$page-1:1 ?>">«</a></li>

<?php for($i=1;$i<=$jmlhal;$i++){ ?>
<li class="page-item <?= ($page==$i)?'active':'' ?>">
    <a class="page-link" data-page="<?= $i ?>"><?= $i ?></a>
</li>
<?php } ?>

<li class="page-item"><a class="page-link" data-page="<?= ($page<$jmlhal)?$page+1:$jmlhal ?>">»</a></li>
<li class="page-item"><a class="page-link" data-page="<?= $jmlhal ?>">Last</a></li>
</ul>
