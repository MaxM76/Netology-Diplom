<div class="answers-list-section">
	<div class="answers-list-section-wrapper">

		<div class="answers-list-nav-div">
      <p><a href="?c=questions&a=list&topic_id=<?= $topic_id?>">Закрыть</a></p>
      <!-- c=questions&a=list&topic_id=3 -->
    </div>
    
<?php if (isset($intrusion['type']) && ($intrusion['type'] == 'replace')):?>
    <?= $intrusion['block']?>
<?php else: ?>

    <div class="answers-list-section-header">
      <h3 class="answers-list-section-heading">Ответы</h3>    	
    </div>

    <div class="answers-list-section-body">    	
    	<div class="answers-list-div">
    		<ul class="answers-list">

	  <?php if (isset($answers) && (count($answers)) > 0): ?>
	      <?php foreach ($answers as $answer) : ?>
        
          <li>
            <p class="answers-list-text"><?= $answer['text']?></p>
            <p>Добавлен: <?= $answer['date']?></p>
            <p>Автор: 
              <a href="mailto:<?= $answer['mail']?>?subject=Относительно ответа <?= $answer['answer_id']?>"><?= $answer['login']?></a>
            </p>
            
            
            <div class="answers-list-ops-div">
            
            <?php if (isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == 0)):?>
              <ul class="answers-list-ops-list">
                <li><a href="?c=answers&a=delete&answer_id=<?= $answer['answer_id']?>">Удалить</a></li>
                <li><a href="?c=answers&a=item&answer_id=<?= $answer['answer_id']?>">Изменить</a></li>	
              </ul>

            <?php endif;?>        

            </div>                
          </li>
      
	      <?php endforeach ?>

    		</div>    	
    	</ul>
    </div>      

    <div class="answers-list-section-footer">

    </div>
        
	  <?php else: ?>
	  <div class="answers-list-section-body">
      <div class="answers-list-div">
        <p class="answers-list-text">Ответов нет</p>
      </div>         
    </div>

    <div class="answers-list-section-footer">
      <div class="answers-list-ops-div">
        <p><a href="?c=answers&a=item&answer_id=-1&question_id=<?= $question['question_id']?>">Ответить</a></p>    
      </div>    	
    </div>
	  <?php endif; ?>        
<?php endif; ?>
  		
	</div>
</div>