<!-- Tombol Tambah -->
<button type="button" class="btn btn-secondary mb-2"
        data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-lg"></i> Tambah Gallery
</button>

<!-- Data Gallery -->
<div id="dataGallery"></div>

<!-- ================= MODAL TAMBAH ================= -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <form action="gallery_data.php" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Gallery</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="file" name="gambar" class="form-control" required>
        </div>

        <div class="modal-footer">
          <button type="submit" name="tambah" class="btn btn-secondary">
            Simpan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form action="gallery_data.php" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Gallery</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <input type="hidden" name="gambar_lama" id="gambar_lama">
          <small class="text-muted">
            Biarkan kosong jika tidak ganti gambar
          </small>
          <input type="file" name="gambar" class="form-control mt-2">
        </div>

        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-secondary">
            Update
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function () {
    loadData(1);

    function loadData(page) {
        $("#dataGallery").load("gallery_data.php?page=" + page);
    }

    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        let page = $(this).data("page");
        loadData(page);
    });

    $(document).on("click", ".btn-edit", function () {
        $("#edit_id").val($(this).data("id"));
        $("#gambar_lama").val($(this).data("gambar"));
    });
});
</script>
