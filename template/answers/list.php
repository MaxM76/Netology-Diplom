<div class="answers-list-section">
  <div class="answers-list-section-wrapper">
    <div class="answers-list-nav-div">
      <p>
        <a href="index.php?c=questions&a=list&topic_id=<?= $topic_id?>#current-topic">Закрыть</a>
      </p>
    </div>
<?php $isReplaceIntrusion = isset($intrusion['type']) && ($intrusion['type'] == 'replace'); ?>
<?php if ($isReplaceIntrusion) : ?>
    <?= $intrusion['block']?>
<?php else : ?>
    <div class="answers-list-section-header">
      <h3 class="answers-list-section-heading">Ответы</h3>
    </div>
    <?php if (isset($answers) && (count($answers)) > 0) : ?>
    <div class="answers-list-section-body">
      <div class="answers-list-div">
        <ul class="answers-list">
        <?php foreach ($answers as $answer) : ?>
          <li>
            <p class="answers-list-text"><?= $answer['text']?></p>
            <p>Добавлен: <?= $answer['created_at']?></p>
            <p>Автор:
            <?php $emailHref = 'mailto:'.$answer['email'].'?subject=Относительно ответа '.$answer['id'];?>
              <a href="<?= $emailHref ?>"><?= $answer['login']?></a>
            </p>
            
            <div class="answers-list-ops-div">
            <?php if ($userType == ADMIN_CODE) : ?>
              <ul class="answers-list-ops-list">
                <li><a href="index.php?c=answers&a=delete&answer_id=<?= $answer['id']?>">Удалить</a></li>
                <li><a href="index.php?c=answers&a=item&answer_id=<?= $answer['id']?>">Изменить</a></li>
              </ul>
            <?php endif;?>
            </div>
          </li>
        <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="answers-list-section-footer">
    </div>
        
    <?php else : ?>
    <div class="answers-list-section-body">
      <div class="answers-list-div">
        <p class="answers-list-text">Ответов нет</p>
      </div>
    </div>

    <div class="answers-list-section-footer">
      <div class="answers-list-ops-div">
        <p><a href="index.php?c=answers&a=item&answer_id=-1&question_id=<?= $question['question_id']?>#new-answer">Ответить</a></p>
      </div>
    </div>
    <?php endif; ?>
<?php endif; ?>
  </div>
</div>