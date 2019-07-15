<div class="topics-list-section">
  <div class="topics-list-section-wrapper">

<?php if (isset($_SESSION['user']['type']) && ($_SESSION['user']['type']) == 0):?>
    
    <?php if (isset($intrusion['topic_id'])
            && ($intrusion['topic_id'] == -1)
            && ($intrusion['block'] != '')):?>

    <?= $intrusion['block'] ?>			

    <?php else:?>
    <div class="topics-list-ops-div">
      <p><a href="?c=topics&a=item&id=-1">Добавить категорию</a></p>
    </div>
    <?php endif;?>		  
<?php endif;?>	

    <div class="topics-list-section-header">
      <h1 class="topics-list-heading">Существующие категории вопросов</h1>        
    </div>

	  <div class="topics-list-section-body">
			<ul class="topics-list">
<?php foreach ($topics as $topic) : ?>
		<?php if (isset($intrusion['topic_id']) && ($topic['topic_id'] == $intrusion['topic_id'])):?>
			   
        <li class="topics-list-item" id="current-item">
        	<a name="current-topic">Текущая категория</a>
		<?php else:?>
				<li class="topics-list-item">	
		<?php endif;?>				

	  <?php if (!(isset($intrusion['type'])) || (isset($intrusion['type']) && !($topic['type'] == 'replace'))):?>

					<div class="topic-header-div">
						<h3 class="topic-header"><?= $topic['text']?></h3>	
					</div>

					<div class="topic-stat-div">
						<table class="topic-stat-table">
							<tr>
								<td><p>Всего вопросов - <?= $topic['total']?></p></td>
								<td><p>Опубликованных вопросов - <?= $topic['published']?></p></td>
								<td><p>Вопросов без ответов - <?= $topic['answered']?></p></td>
							</tr>
						</table>
					</div>
					
					<div class="topic-date-div">
						<p class="topic-date">Добавлена: <?= $topic['date']?></p>
					</div>	

					<div class="topic-description-div">
						<p class="topic-description"><?= $topic['description']?></p>
					</div>
					
					<div class="topic-item-ops-div">
						<ul class="topic-item-ops-list">

        <?php if (isset($_SESSION['user']['type']) && ($_SESSION['user']['type']) == 0):?>
              <li><a href="?c=topics&a=delete&id=<?= $topic['topic_id']?>">Удалить</a></li>

            <?php if (!(($GLOBALS['r'] -> controllerName == 'topicsController') 
                    && ($GLOBALS['r'] -> action == 'item')
                    && ($topic['topic_id'] == $intrusion['topic_id']))):?>
              <li><a href="?c=topics&a=item&id=<?= $topic['topic_id']?>">Изменить</a></li>
            <?php endif;?>
        <?php endif;?>    

	      <?php if (isset($intrusion['topic_id']) 
	              && ($topic['topic_id'] == $intrusion['topic_id'])
	              && (!(($GLOBALS['r'] -> controllerName == 'topicsController') 
                    && ($GLOBALS['r'] -> action == 'item')))):?>
              <li><a href="?c=topics&a=list">Скрыть вопросы</a></li>
        <?php else:?>
              <li><a href="?c=questions&a=list&topic_id=<?= $topic['topic_id']?>">Показать вопросы</a></li>
	      <?php endif;?>
            	
						</ul>
					</div>	
					
	  <?php endif;?>


	  <?php if (isset($intrusion['topic_id']) && ($topic['topic_id'] == $intrusion['topic_id'])):?>
	  <?= $intrusion['block'] ?>
	  <?php endif;?>
			
				</li>
<?php endforeach; ?>		
			</ul>
		</div>

	  <div class="topics-list-section-footer">

<?php if (isset($_SESSION['user']['type']) && ($_SESSION['user']['type']) == 0):?>    
    <?php if (!(isset($intrusion['topic_id'])
            && ($intrusion['topic_id'] == -1)
            && ($intrusion['block'] != ''))):?>

      <div class="topics-list-ops-div">
        <p><a href="?c=topics&a=item&id=-1">Добавить категорию</a></p>
      </div>

    <?php endif;?>		  
<?php endif;?>
	
		</div>
	</div>	
</div>