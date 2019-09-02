<?php if (count($messages) != 0) : ?>
  <div class="messages-div">
    <h2>Сообщения:</h2>
    <?php foreach ($messages as $messageCause => $message) : ?>
    <p><?= $messageCause ?>: <?= $message ?></p>
    <?php endforeach;?>
  </div>
<?php endif;?>