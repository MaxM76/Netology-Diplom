	<div class="errors-div">
		<p>Блок ошибок</p>
<?php if (count($errors) != 0):?>
		<h2>Возникли ошибки:</h2>
<?php	  foreach ($errors as $errorName => $errorValue):?>
    	<p><?= $errorName?> :: <?= $errorValue?></p>
<?php     endforeach;?> 
<?php endif;?>
	</div>