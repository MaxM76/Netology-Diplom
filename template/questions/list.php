<?php $questionsItemAction = ($controller == 'questionsController') && ($action == 'item'); ?>
<?php $answersItemAction = ($controller == 'answersController') && ($action == 'item'); ?>
<?php $addQuestion = isset($intrusion['question_id'])
    && ($intrusion['question_id'] == -1)
    && ($intrusion['block'] != ''); ?>
<?php $isInsertIntrusion = isset($intrusion['type']) && ($intrusion['type'] == 'insert')?>

<div class="questions-list-section">
  <div class="questions-list-section-wrapper">
    <div class="questions-list-section-header">
      <h2 class="questions-list-section-heading">Список вопросов</h2>
    </div>
    
    <div class="questions-list-section-body">
<?php if (isset($questions) && (count($questions)) > 0) : ?>
      <div class="questions-list-section-div">
        <table class="questions-list-table">
          <tr class="questions-list-table-header">
            <td>Вопрос</td>
            <td>Дата</td>
            <td>Автор</td>
            <td>Статус</td>
    <?php if ($userType == ADMIN_CODE) : ?>
            <td>Опубликован</td>           
    <?php endif;?>                  
            <td>Операции</td>
          </tr>
    <?php foreach ($questions as $question) : ?>
        <?php $intrusionHere = isset($intrusion['question_id']) && ($question['id'] == $intrusion['question_id']); ?>
        <?php if (!($intrusionHere && !($isInsertIntrusion))) : ?>
          <tr class="questions-list-table-row">
            <td><?= $question['text']?></td>
            <td><?= $question['created_at']?></td>
            <td>
            <?php $emailHref = 'mailto:'.$question['email'].'?subject=Относительно вопроса '.$question['id'];?>
              <a href="<?= $emailHref ?>"><?= $question['login']?></a>
            </td>

            <?php if ($question['status'] == 1) : ?>
            <td>Отвечен</td>
            <?php else : ?>
            <td>Нет ответа</td>
            <?php endif;?>

            <?php if ($userType == ADMIN_CODE) : ?>
                <?php if ($question['is_published'] == QUESTION_PUBLISHED) {
                    $ch ='checked ';
                } else {
                    $ch ='';
                } ?>

            <td>
              <input type="checkbox" name="published" <?= $ch ?> disabled>
            </td>

            <?php endif;?>     
                
            <td>
              <div class="questions-list-item-ops-div">
                <ul class="question-list-ops-list">
            <?php if ($userType == ADMIN_CODE) : ?>
                  <li><a href="?c=questions&a=delete&id=<?= $question['id']?>">Удалить</a></li>

                <?php if (!($questionsItemAction && $intrusionHere)) : ?>
                  <li><a href="?c=questions&a=item&id=<?= $question['id']?>">Редактировать</a></li>
                <?php endif;?>

            <?php endif;?>
                
            <?php if ($question['status'] == QUESTION_ANSWERED) : ?>
                <?php if ($intrusionHere) : ?>
                  <li>
                    <a href="?c=questions&a=list&topic_id=<?= $question['topic_id']?>">Скрыть ответ</a>
                  </li>
                <?php else : ?>
                  <li>
                    <a href="?c=answers&a=list&question_id=<?= $question['id']?>">Показать ответ</a>
                  </li>
                <?php endif;?>
            <?php endif;?>

            <?php if (($userType == ADMIN_CODE)
                && ($question['status'] == QUESTION_NOT_ANSWERED)
                && !($answersItemAction && $intrusionHere)) : ?>
                  <li>
                    <a href="?c=answers&a=item&answer_id=-1&question_id=<?= $question['id']?>">Ответить</a>
                  </li>
            <?php endif;?>                            
                           
                </ul>                 
              </div>
            </td>
          </tr>  
           
        <?php endif;?>
            
        <?php if ($intrusionHere) : ?>
          <tr class="questions-list-table-row">  
            <td colspan="6">
            <?= $intrusion['block']?>
            </td>
          </tr>
        <?php endif;?>
    <?php endforeach; ?>

        </table>
      </div>
      
<?php else : ?>
      <div class="questions-list-section-div">
        <p>Нет вопросов в данной категории</p>
      </div>
<?php endif;?>

    </div>
          
    <div class="questions-list-section-footer"> 

<?php if ($addQuestion) : ?>
      <div class="questions-list-intrusion">
    <?= $intrusion['block']?>      
      </div>

<?php else : ?>
      <div class="questions-list-ops-div">
        <p><a href="?c=questions&a=item&id=-1&topic_id=<?= $topic_id?>">Добавить вопрос</a></p>
      </div>

<?php endif;?>
    </div>
  </div>
</div>