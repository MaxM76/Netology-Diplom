<?php if (count($errors) != 0) : ?>
    <div class="errors-div">
      <h2>Возникли ошибки:</h2>
    <?php foreach ($errors as $errorName => $errorValue) : ?>
      <p><?= $errorName?> :: <?= $errorValue?></p>
    <?php endforeach;?>
    </div>
<?php endif;?>

