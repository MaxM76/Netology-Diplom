<?php if (count($errors) != 0) : ?>
    <div class="errors-div">
      <h2>Возникли ошибки:</h2>
    <?php foreach ($errors as $errorSource => $errorValue) : ?>
      <p><?= $errorValue?> (<?= $errorSource?>)</p>
    <?php endforeach;?>
    </div>
<?php endif;?>
