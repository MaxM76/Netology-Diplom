<div class="user-info-div">

<?php if (isset($_SESSION['user'])) : ?>
  <p>Текущий пользователь: <?= $_SESSION['user']['login'] ?></p>
<?php else : ?>
    <p>Гостевой режим </p>
<?php endif; ?>
</div>