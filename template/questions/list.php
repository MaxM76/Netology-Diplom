<?php $isIntrusionExist = isset($intrusion['block']) && ($intrusion['block'] != '');?>
<?php $addQuestion = $isIntrusionExist
    && isset($intrusion['question_id'])
    && ($intrusion['question_id'] == UNKNOWN_ITEM_ID); ?>
<?php $isReplaceIntrusion = $isIntrusionExist && isset($intrusion['type']) && ($intrusion['type'] == 'replace')?>
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
        <?php $intrusionHere = $isIntrusionExist
                && isset($intrusion['question_id'])
                && ($question['id'] == $intrusion['question_id']); ?>
        <?php if ($intrusionHere) : ?>
          <a name="current-question"></a>
        <?php endif;?>

        <?php if ($intrusionHere && $isReplaceIntrusion) : ?>
            <?= $intrusion['block']?>
        <?php else : ?>
          <tr class="questions-list-table-row">
            <td><?= $question['text']?></td>
            <td><?= $question['created_at']?></td>
            <td>
            <?php $emailHref = 'mailto:'.$question['email'].'?subject=Относительно вопроса '.$question['id'];?>
              <a href="<?= $emailHref ?>"><?= $question['login']?></a>
            </td>

            <?php if ($question['status'] == QUESTION_ANSWERED) : ?>
            <td>Отвечен</td>
            <?php else : ?>
            <td>Нет ответа</td>
            <?php endif;?>

            <?php if ($userType == ADMIN_CODE) : ?>
                <?php $checkedAttribute = ($question['is_published'] == QUESTION_PUBLISHED) ? 'checked ' : '';?>
            <td>
              <input type="checkbox" name="is_published" <?= $checkedAttribute ?> disabled>
            </td>
            <?php endif;?>
                
            <td>
              <div class="questions-list-item-ops-div">
                <ul class="question-list-ops-list">
            <?php if ($userType == ADMIN_CODE) : ?>
                  <li>
                    <a href="index.php?c=questions&a=delete&id=<?= $question['id']?>&filter=<?= $filter?>#current-topic">
                      Удалить
                    </a>
                  </li>
                <?php if (!($intrusion['hideEditQuestionButton'] && $intrusionHere)) : ?>
                  <li>
                    <a href="index.php?c=questions&a=item&id=<?= $question['id']?>&filter=<?= $filter?>#current-question">
                      Редактировать
                    </a>
                  </li>
                <?php endif;?>
            <?php endif; ?>
                
            <?php if ($question['status'] == QUESTION_ANSWERED) : ?>
                <?php if ($intrusionHere && !$isReplaceIntrusion) : ?>
                  <li>
                    <a href="index.php?c=questions&a=list&topic_id=<?= $question['topic_id']?>&filter=<?= $filter?>#current-question">
                        Скрыть ответ
                    </a>
                  </li>
                <?php else : ?>
                  <li>
                    <a href="index.php?c=answers&a=list&question_id=<?= $question['id']?>&filter=<?= $filter?>#current-question">
                        Показать ответ
                    </a>
                  </li>
                <?php endif; ?>
            <?php else : ?>
                <?php if (($userType == ADMIN_CODE)
                        && ($question['status'] == QUESTION_NOT_ANSWERED)
                        && !(!($intrusion['hideAnswerQuestionButton']) && $intrusionHere)) : ?>
                  <li>
                    <a href="index.php?c=answers&a=item&answer_id=-1&question_id=<?= $question['id']?>&filter=<?= $filter?>#new-answer">
                       Ответить
                    </a>
                  </li>
                <?php endif;?>
            <?php endif;?>
                </ul>                 
              </div>
            </td>
          </tr>
            <?php if ($intrusionHere && !$isReplaceIntrusion) : ?>
          <tr>
            <td colspan="6">
                <?= $intrusion['block']?>
            </td>
          </tr>
            <?php endif; ?>
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
        <p>
          <a href="index.php?c=questions&a=item&id=-1&topic_id=<?= $topic_id?>&filter=<?= $filter?>#new-question">
            Добавить вопрос
          </a>
        </p>
      </div>
<?php endif;?>
    </div>
  </div>
</div>