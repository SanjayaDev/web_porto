<div class="container-fluid">
  <h4><?= $title; ?></h4>
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-4" data-toggle="modal" data-target="#addSkill">Tambah Skill</button>
      <div class="table-responsive">
        <table class="table table-striped table-dark w-100" id="skillTable">
          <thead>
            <tr>
              <th>No</th>
              <th>Skill</th>
              <th>Kategori</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-body" id="addSkill">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Skill</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open("add_skill", "id='formAdd'"); ?>
        <div class="form-group">
          <label>Skill Name</label>
          <input type="text" class="form-control" name="skill_name" placeholder="Skill Name">
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select name="kategori_id" class="form-control">
            <?php foreach ($list_kategori as $item) {
              echo "<option value='$item->kategori_id'>$item->kategori</option>";
            } ?>
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

<div class="modal fade text-body" id="editSkill">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Skill</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open("add_skill", "id='formEdit'"); ?>
        <div class="form-group">
          <label>Skill Name</label>
          <input type="hidden" name="skill_id" id="skillId">
          <input type="text" class="form-control" name="skill_name" placeholder="Skill Name" id="skillName">
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select name="kategori_id" class="form-control" id="kategoriId">
            <?php foreach ($list_kategori as $item) {
              echo "<option value='$item->kategori_id'>$item->kategori</option>";
            } ?>
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


<script>
  let table = $("#skillTable").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "<?= base_url("get_skill") ?>",
      "type": "POST"
    },
    "columnDefs": [{
      "targets": [0],
      "orderable": false
    }]
  });

  $("#formAdd").submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: "<?= base_url("add_skill") ?>",
      type: "POST",
      cache: false,
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(result) {
        $("[name='X-CSRF-TOKEN']").val(result.csrf);
        if (result.success == 200) {
          $("#addSkill").modal("hide");
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
    $.ajax({
      url: "<?= base_url("edit_skill") ?>",
      type: "POST",
      cache: false,
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(result) {
        $("[name='X-CSRF-TOKEN']").val(result.csrf);
        if (result.success == 200) {
          $("#editSkill").modal("hide");
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

  function getSkillDetail(skillId) {
    $.ajax({
      url: `<?= base_url("get_skill_detail?id=") ?>${skillId}`,
      cache: false,
      type: "GET",
      dataType: "JSON",
      success: function(result) {
        // console.log(result);
        $("#skillId").val(result.id);
        $("#skillName").val(result.skillName);
        $("#kategoriId").val(result.kategoriId);
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