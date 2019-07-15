	<div class="messages-div">	
		<p>Блок сообщений</p>
<?php if (count($messages) != 0):?>
		<h2>Сообщения:</h2>
<?php	  foreach ($messages as $messageCause => $message):?>
    	<p><?= $messageCause ?>: <?= $message ?></p>
<?php     endforeach;?> 
<?php endif;?>
	</div>