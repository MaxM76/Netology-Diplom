<div class="main-menu">
  <ul class="main-menu-list">

<?php foreach ($buttons as $button) : ?>
      <li>
        <a href="<?=MENU_BUTTONS[$button]['href']?>"><?=MENU_BUTTONS[$button]['caption']?></a>
      </li>
<?php endforeach ?>

  </ul>
</div>