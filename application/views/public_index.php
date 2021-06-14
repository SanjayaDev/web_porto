<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ===== CSS ===== -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/sweetalert2/sweetalert2.min.css">
  <!-- FontAwesome CSS-->
	<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/fontawesome/css/all.css">

  <title>helloega_</title>
</head>

<body>
  <!--===== HEADER =====-->
  <header class="l-header">
    <nav class="nav bd-grid">
      <div>
        <a href="#" class="nav__logo">Herln</a>
      </div>

      <div class="nav__menu" id="nav-menu">
        <ul class="nav__list">
          <li class="nav__item"><a href="#home" class="nav__link active">Home</a></li>
          <li class="nav__item"><a href="#about" class="nav__link">About</a></li>
          <li class="nav__item"><a href="#skills" class="nav__link">Skills</a></li>
          <li class="nav__item"><a href="#portfolio" class="nav__link">Portfolio</a></li>
          <li class="nav__item"><a href="#contact" class="nav__link">Contact</a></li>
        </ul>
      </div>

      <div class="nav__toggle" id="nav-toggle">
        <i class='bx bx-menu'></i>
      </div>
    </nav>
  </header>

  <main class="l-main">
    <!--===== HOME =====-->
    <section class="home" id="home">
      <div class="home__container bd-grid">
        <h1 class="home__title"><span>HE</span><br>LLO.</h1>

        <div class="home__scroll">
          <a href="#about" class="home__scroll-link"><i class='bx bx-up-arrow-alt'></i>Scroll down</a>
        </div>

        <img src="<?= $aboutme->photo_path . "?v=$anticache" ?>" alt="" class="home__img">
      </div>
    </section>

    <!--===== ABOUT =====-->
    <section class="about section" id="about">
      <h2 class="section-title">About</h2>

      <div class="about__container bd-grid">
        <div class="about__img">
          <img src="<?= $aboutme->photo_path . "?v=$anticache"; ?>" alt="">
        </div>

        <div>
          <h2 class="about__subtitle">I'am <?= $aboutme->aboutme_fullName; ?></h2>
          <span class="about__profession"><?= $aboutme->aboutme_profesionalName; ?></span>
          <p class="about__text"><?= $aboutme->aboutme_description; ?></p>

          <div class="about__social">
            <a href="<?= $aboutme->aboutme_linkedin; ?>" class="about__social-icon"><i class='bx bxl-linkedin'></i></a>
            <a href="<?= $aboutme->aboutme_github; ?>" class="about__social-icon"><i class='bx bxl-github'></i></a>
            <a href="<?= $aboutme->aboutme_dribbble; ?>" class="about__social-icon"><i class='bx bxl-dribbble'></i></a>
          </div>
        </div>
      </div>
    </section>

    <!--===== SKILLS =====-->
    <section class="skills section" id="skills">
      <h2 class="section-title">Skills</h2>

      <div class="skills__container bd-grid">
        <div class="skills__box">
          <?php
          foreach ($list_skill_kategori as $item) {
            echo "<h3 class='skills_subtitle'>$item->kategori</h3>";
            foreach ($list_skill as $items) {
              if ($item->kategori_id == $items->kategori_id) {
                echo "<span class='skills__name'>$items->skill_name</span>";
              }
            }
          }
          ?>
        </div>

        <div class="skills__img">
          <img src="assets/img/skill1.jpg" alt="">
        </div>
      </div>
    </section>

    <!--===== PORTFOLIO =====-->
    <section class="portfolio section" id="portfolio">
      <h2 class="section-title">Portfolio</h2>

      <div class="portfolio__container bd-grid">
        <?php foreach ($list_portofolio as $item) : ?>
          <div class="portfolio__img">
            <img src="<?= $item->portofolio_image ?>" alt="">

            <div class="portfolio__link">
              <a href="#" class="portfolio__link-name">View details</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!--===== CONTACT =====-->
    <section class="contact section" id="contact">
      <h2 class="section-title">Contact</h2>

      <div class="contact__container bd-grid">
        <div class="contact__info">
          <?php foreach ($list_social as $item) : ?>
            <h3 class="contact__subtitle"><?= $item->kontak_name ?></h3>
            <a href="<?= $item->kontak_link; ?>"><span class="contact__text"><?= $item->kontak_value; ?></span></a>
          <?php endforeach; ?>
        </div>

        <?= form_open("add_inquiry", "class='contact__form'") ?>
        <div class="contact__inputs">
          <input type="text" placeholder="Name" class="contact__input" name="inquiry_name">
          <input type="email" placeholder="Email" class="contact__input" name="inquiry_email">
        </div>

        <textarea name="inquiry_message" id="" cols="0" rows="10" class="contact__input"></textarea>

        <input type="submit" value="Enviar" class="contact__button">
        <?= form_close(); ?>
      </div>
    </section>
  </main>

  <!--===== FOOTER =====-->
  <footer class="footer section">
    <div class="footer__container bd-grid">
      <div class="footer__data">
        <h2 class="footer__title">Herlangga Maulana M</h2>
        <p class="footer__text">I'm Herlangga and this is my personal website</p>
      </div>

      <div class="footer__data">
        <h2 class="footer__title">EXPLORE</h2>
        <ul>
          <li><a href="#home" class="footer__link">Home</a></li>
          <li><a href="#about" class="footer__link">About</a></li>
          <li><a href="#skills" class="footer__link">Skills</a></li>
          <li><a href="#portfolio" class="footer__link">Portfolio</a></li>
          <li><a href="#Contact" class="footer__link">Contact</a></li>
        </ul>
      </div>

      <div class="footer__data">
        <h2 class="footer__title">FOLLOW</h2>
        <?php 
          foreach ($list_social as $item) :
            echo "<a href='$item->kontak_link' class='footer__social'><i class='$item->kontak_icon'></i></a>";
          endforeach;
        ?>
      </div>


    </div>
  </footer>

  <!--===== SCROLL REVEAL =====-->
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="<?= base_url(); ?>assets/vendor/vendors/sweetalert2/sweetalert2.min.js"></script>
  <script>
    function sweet(icon, title, text) {
      Swal.fire({
        icon: icon,
        title: title,
        text: text
      })
    }
  </script>
  <?= $this->session->flashdata("pesan"); ?>

  <!--===== MAIN JS =====-->
  <script src="<?= base_url() ?>assets/js/main.js"></script>
</body>

</html>