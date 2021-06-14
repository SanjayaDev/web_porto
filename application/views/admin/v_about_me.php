<script src="<?= base_url(); ?>assets/vendor/vendors/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
  <h4><?= $title ?></h4>
  <div class="card mt-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <img src="<?= $aboutme->photo_path ?>" alt="My Foto" class="img-fluid">
        </div>
        <div class="col-md-6">
          <?= form_open_multipart("validation_about_me_add") ?>
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" class="form-control" name="aboutme_fullName" placeholder="Fullname" value="<?= $aboutme->aboutme_fullName ?>">
            </div>
            <div class="form-group">
              <label>Profesional Name</label>
              <input type="text" class="form-control" name="aboutme_profesionalName" placeholder="Profesional Name" value="<?= $aboutme->aboutme_profesionalName ?>">
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea name="aboutme_description" id="aboutme_description" cols="30" rows="10" ><?= $aboutme->aboutme_description ?></textarea>
            </div>
            <div class="form-group">
              <label>Linkedin</label>
              <input type="text" class="form-control" name="aboutme_linkedin" placeholder="Linkedin Url's" value="<?= $aboutme->aboutme_linkedin ?>">
            </div>
            <div class="form-group">
              <label>Github</label>
              <input type="text" class="form-control" name="aboutme_github" placeholder="Github Url's" value="<?= $aboutme->aboutme_github ?>">
            </div>
            <div class="form-group">
              <label>Dribbble</label>
              <input type="text" class="form-control" name="aboutme_dribbble" placeholder="Dribbble Url's" value="<?= $aboutme->aboutme_dribbble ?>">
            </div>
            <div class="form-group">
              <label>Image</label>
              <input type="file" class="form-control" name="photo_path">
            </div>
            <input type="submit" class="btn btn-success" value="Save">
          <?= form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  CKEDITOR.replace('aboutme_description');
</script>