<div class="container-fluid">
  <h4><?= $title ?></h4>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#kontak">Kontak</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#inquiry">Inquiry</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane container active" id="kontak">
          <button class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#addKontak">Tambah Kontak</button>
          <div class="table-responsive">
            <table class="table table-striped table-dark w-100" id="tableKontak">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kontak</th>
                  <th>Value</th>
                  <th>Icon</th>
                  <th>Link</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="tab-pane container fade" id="inquiry">
          <div class="table-responsive">
            <table class="table table-striped table-dark w-100" id="tableInquiry">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Message</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-body" id="addKontak">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Kontak</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open("add_skill", "id='formAdd'"); ?>
        <div class="form-group">
          <label>Nama Kontak</label>
          <input type="text" name="kontak_name" class="form-control" placeholder="Nama Kontak...">
        </div>
        <div class="form-group">
          <label>Value Kontak</label>
          <input type="text" name="kontak_value" class="form-control" placeholder="Value Kontak...">
        </div>
        <div class="form-group">
          <label>Icon Kontak</label>
          <input type="text" name="kontak_icon" class="form-control" placeholder="Icon Kontak...">
        </div>
        <div class="form-group">
          <label>Link Kontak</label>
          <input type="text" name="kontak_link" class="form-control" placeholder="Link Kontak...">
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
<div class="modal fade text-body" id="editKontak">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Kontak</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open("add_skill", "id='formEdit'"); ?>
        <div class="form-group">
          <label>Nama Kontak</label>
          <input type="text" name="kontak_name" class="form-control" placeholder="Nama Kontak..." id="kontakName">
          <input type="hidden" name="kontak_id" id="kontakId">
        </div>
        <div class="form-group">
          <label>Value Kontak</label>
          <input type="text" name="kontak_value" class="form-control" placeholder="Value Kontak..." id="kontakValue">
        </div>
        <div class="form-group">
          <label>Icon Kontak</label>
          <input type="text" name="kontak_icon" class="form-control" placeholder="Icon Kontak..." id="kontakIcon">
        </div>
        <div class="form-group">
          <label>Link Kontak</label>
          <input type="text" name="kontak_link" class="form-control" placeholder="Link Kontak..." id="kontakLink">
        </div>
        <button type="submit" class="btn btn-success btn-sm">Simpan Perubahan</button>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
  let table = $("#tableKontak").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "<?= base_url("get_kontak") ?>",
      "type": "POST"
    },
    "columnDefs": [{
      "targets": [0],
      "orderable": false
    }]
  });

  let tableInquiry = $("#tableInquiry").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "<?= base_url("get_inquiry") ?>",
      "type": "POST"
    },
    "columnDefs": [{
      "targets": [0],
      "orderable": false
    }]
  });

  function responseInquiry(inquiryId) {
    $.ajax({
      url: "<?= base_url("response_inquiry?id=") ?>"+inquiryId,
      cache: false,
      type: "GET",
      dataType: "JSON",
      success: function(result) {
        if (result.success == 200) {
          sweet("success", "Sukses!", result.message);
          tableInquiry.ajax.reload();
        } else {
          sweet("error", "Gagal!", result.message);
        }
      }
    });
  }

  $("#formAdd").submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: "<?= base_url("add_kontak") ?>",
      type: "POST",
      cache: false,
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(result) {
        $("[name='X-CSRF-TOKEN']").val(result.csrf);
        if (result.success == 200) {
          $("#addKontak").modal("hide");
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
      url: "<?= base_url("edit_kontak") ?>",
      type: "POST",
      cache: false,
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(result) {
        $("[name='X-CSRF-TOKEN']").val(result.csrf);
        if (result.success == 200) {
          $("#editKontak").modal("hide");
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

  function getKontakDetail(kontakId) {
    $.ajax({
      url: `<?= base_url("get_kontak_detail?id=") ?>${kontakId}`,
      cache: false,
      type: "GET",
      dataType: "JSON",
      success: function(result) {
        // console.log(result);
        $("#kontakId").val(result.id);
        $("#kontakName").val(result.kontakName);
        $("#kontakValue").val(result.kontakValue);
        $("#kontakIcon").val(result.kontakIcon);
        $("#kontakLink").val(result.kontakLink);
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