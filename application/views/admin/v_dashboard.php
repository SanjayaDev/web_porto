<div class="container-fluid dashboard">
  <h3>Dashboard</h3>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-4 d-flex align-items-center">
              <i class="fas fa-magic fa-3x text-light"></i>
            </div>
            <div class="col-8">
              <h4>Skill</h4>
              <h5><?= $statistik->count_skill; ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-4 d-flex align-items-center">
              <i class="fab fa-creative-commons-share fa-3x text-light"></i>
            </div>
            <div class="col-8">
              <h4>Portofolio</h4>
              <h5><?= $statistik->count_portofolio; ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-4 d-flex align-items-center">
              <i class="fas fa-thumbs-up fa-3x text-light"></i>
            </div>
            <div class="col-8">
              <h4>Inquiry</h4>
              <h5><?= $statistik->count_inquiry; ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>