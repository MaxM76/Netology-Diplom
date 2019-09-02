  <body>
    <header>
      <div class="header-wrapper">
<?php include 'user.php';?>
<?php
    include 'menu.php';
    echo $eblock;
    echo $mblock;
?>
      </div> <!--  -->
    </header> <!-- header -->

    <section class="main-section">
      <div class="main-section-wrapper">
<?php
    echo $oblock;
?>
      </div>
    </section> <!-- nav-section -->

    <footer>
      <div class="footer-wrapper">
        <p>© 2019  Максим Маркелов, по всем вопросам пишите по адресу m.v.markelov@mail.ru</p>
      </div>
    </footer> <!-- -->
  </body>