<script src="<?= base_url(); ?>assets/vendor/vendors/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
  <h4><?= $title ?></h4>
  <div class="card mt-4">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-4" data-toggle="modal" data-target="#addPortofolio">Tambah Portofolio</button>
      <div class="table-responsive">
        <table class="table table-striped table-dark w-100" id="portofolioTable">
          <thead>
            <tr>
              <th>No</th>
              <th>Judul</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-body" id="addPortofolio">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Portofolio</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open("add_skill", "id='formAdd'"); ?>
        <div class="form-group">
          <label>Nama Portofolio</label>
          <input type="text" name="portofolio_name" class="form-control" placeholder="Nama Portofolio...">
        </div>
        <div class="form-group">
          <label>Deksripsi Portofolio</label>
          <textarea name="portofolio_description" id="portofolioDescription" cols="30" rows="5"></textarea>
        </div>
        <div class="form-group">
          <label>Foto Portofolio</label>
          <input type="file" name="portofolio_image" class="form-control" onchange="readerImage(this)">
        </div>
        <img src="" alt="Foto Portofolio" id="imgLoad" class="img-fluid w-100 h-auto mb-3">
        <div class="form-group">
          <label>Kategori Portofolio</label>
          <select name="kategori_id" class="form-control">
            <?php foreach ($list_kategori as $item) {
              echo "<option value='$item->kategori_id'>$item->kategori</option>";
            } ?>
          </select>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="is_active" class="form-control">
            <option value="1">Aktif</option>
            <option value="0">Non Aktif</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-body" id="editPortofolio">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Portofolio</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open("add_skill", "id='formEdit'"); ?>
        <div class="form-group">
          <label>Nama Portofolio</label>
          <input type="hidden" name="portofolio_id" id="portofolioId">
          <input type="text" name="portofolio_name" id="portofolioNameEdit" class="form-control" placeholder="Nama Portofolio...">
        </div>
        <div class="form-group">
          <label>Deksripsi Portofolio</label>
          <textarea name="portofolio_description" id="portofolioDescriptionEdit" cols="30" rows="5"></textarea>
        </div>
        <div class="form-group">
          <label>Foto Portofolio</label>
          <input type="file" name="portofolio_image" class="form-control" onchange="readerImage(this, 'edit')">
        </div>
        <img src="" alt="Foto Portofolio" id="imgLoadEdit" class="img-fluid w-100 h-auto mb-3">
        <div class="form-group">
          <label>Kategori Portofolio</label>
          <select name="kategori_id" class="form-control" id="kategoriIdEdit">
            <?php foreach ($list_kategori as $item) {
              echo "<option value='$item->kategori_id'>$item->kategori</option>";
            } ?>
          </select>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="is_active" class="form-control" id="isActiveEdit">
            <option value="1">Aktif</option>
            <option value="0">Non Aktif</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-body" id="detailPortofolio">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Portofolio</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img src="" alt="Portofolio Image" id="portofolioImageDetail" class="img-fluid w-100 h-auto mb-4">
        <h4 id="portofolioNameDetail"></h4>
        <small class="d-block mb-3" id="portofolioCreatedDetail"></small>
        <div id="portofolioDescDetail"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
CKEDITOR.replace('portofolioDescription');
CKEDITOR.replace('portofolioDescriptionEdit');
  let table = $("#portofolioTable").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "<?= base_url("get_portofolio") ?>",
      "type": "POST"
    },
    "columnDefs": [{
      "targets": [0],
      "orderable": false
    }]
  });

  function readerImage(input, option = "add") {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        if (option == "add") {
          $("#imgLoad").attr("src", e.target.result);
        } else {
          $("#imgLoadEdit").attr("src", e.target.result);
        }
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#formAdd").submit(function(e) {
    e.preventDefault();
    CKEDITOR.instances["portofolioDescription"].updateElement();
    $.ajax({
      url: "<?= base_url("add_portofolio") ?>",
      type: "POST",
      cache: false,
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(result) {
        $("[name='X-CSRF-TOKEN']").val(result.csrf);
        if (result.success == 200) {
          $("#addPortofolio").modal("hide");
          sweet("success", "Sukses!", result.message);
          document.getElementById("formAdd").reset();
          table.ajax.reload();
        } else if (result.success == 201) {
          sweet("error", "Gagal!", result.message);
        } else {
          window.location.href = result.link;
        }
      }
    });
  })

  $("#formEdit").submit(function(e) {
    e.preventDefault();
    CKEDITOR.instances["portofolioDescriptionEdit"].updateElement();
    $.ajax({
      url: "<?= base_url("edit_portofolio") ?>",
      type: "POST",
      cache: false,
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(result) {
        $("[name='X-CSRF-TOKEN']").val(result.csrf);
        if (result.success == 200) {
          $("#editPortofolio").modal("hide");
          sweet("success", "Sukses!", result.message);
          document.getElementById("formEdit").reset();
          table.ajax.reload();
        } else if (result.success == 201) {
          sweet("error", "Gagal!", result.message);
        } else {
          window.location.href = result.link;
        }
      }
    });
  })

  function getPortofolioDetail(portofolioId, option) {
    $.ajax({
      url: `<?= base_url("get_portofolio_detail?id=") ?>${portofolioId}`,
      cache: false,
      type: "GET",
      dataType: "JSON",
      success: function(result) {
        // console.log(result.portofolioImg);
        if (option == "detail") {
          $("#portofolioImageDetail").attr("src", result.portofolioImg);
          $("#portofolioNameDetail").html(result.portofolioName);
          $("#portofolioDescDetail").html(result.portofolioDesc);
          $("#portofolioCreatedDetail").html(`Dibuat pada ${result.created} status ${result.isActive}`);
        } else {
          $("#portofolioId").val(result.id);
          $("#portofolioNameEdit").val(result.portofolioName);
          CKEDITOR.instances["portofolioDescriptionEdit"].setData(result.portofolioDesc);
          $("#imgLoadEdit").attr("src", result.portofolioImg);
          $("#kategoriIdEdit").val(result.kategoriId);
          $("#isActiveEdit").val(result.isActiveId);
        }
      }
    });
  }

  function promptDelete(link) {
	Swal.fire({
		title: 'Anda yakin?',
		text: "Item ini akan dihapus secara permanent!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya, Hapus ini!'
	}).then((result) => {
		if (result.value) {
		 $.ajax({
			 url: link,
			 type: "GET",
			 cache: false,
			 success: function(result) {
         let hasil = JSON.parse(result);
        //  console.log(hasil);
				 if (hasil.success == 200) {
          table.ajax.reload();
          sweet("success", "Sukses!", hasil.message);
				 } else {
					 Swal.fire({
						 title: "Gagal!",
						 text: hasil.message,
						 icon: "error"
					 })
				 }
			 },
			 error: function() {
				 Swal.fire({
						 title: "Gagal!",
						 text: "404 Not Found",
						 icon: "error"
					 })
			 }
		 })
		}
	})
}
</script>