<div class="topics-list-section">
  <div class="topics-list-section-wrapper">
<?php $topicsItemAction = ($controller == 'topicsController') && ($action == 'item'); ?>
<?php $addTopic = isset($intrusion['topic_id']) && ($intrusion['topic_id'] == -1) && ($intrusion['block'] != ''); ?>

<?php if ($userType == ADMIN_CODE) : ?>
    <?php if ($addTopic) : ?>
        <?= $intrusion['block'] ?>
    <?php else : ?>
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
    <?php $intrusionHere = isset($intrusion['topic_id']) && ($topic['id'] == $intrusion['topic_id']); ?>
    <?php if ($intrusionHere) : ?>
          <li class="topics-list-item" id="current-item">
            <a name="current-topic">Текущая категория</a>
    <?php else : ?>
          <li class="topics-list-item">
    <?php endif;?>
    <?php if (!(isset($intrusion['type']) && ($topic['type'] == 'replace'))) : ?>
            <div class="topic-header-div">
              <h3 class="topic-header"><?= $topic['text']?></h3>
            </div>

            <div class="topic-stat-div">
              <table class="topic-stat-table">
                <tr>
                  <td><p>Всего вопросов - <?= $topic['total']?></p></td>
                  <td><p>Опубликованных вопросов - <?= $topic['published']?></p></td>
                  <td><p>Вопросов без ответов - <?= $topic['total'] - $topic['answered']?></p></td>
                </tr>
              </table>
            </div>

            <div class="topic-date-div">
              <p class="topic-date">Добавлена: <?= $topic['created_at']?></p>
            </div>

            <div class="topic-description-div">
              <p class="topic-description"><?= $topic['description']?></p>
            </div>

            <div class="topic-item-ops-div">
              <ul class="topic-item-ops-list">
        <?php if ($userType == ADMIN_CODE) : ?>
                <li><a href="?c=topics&a=delete&id=<?= $topic['id']?>">Удалить</a></li>
            <?php if (!($topicsItemAction && $intrusionHere)) : ?>
                <li><a href="?c=topics&a=item&id=<?= $topic['id']?>">Изменить</a></li>
            <?php endif;?>
        <?php endif;?>

        <?php if ($intrusionHere && (!($topicsItemAction))) : ?>
                <li><a href="?c=topics&a=list">Скрыть вопросы</a></li>
        <?php else :?>
                <li><a href="?c=questions&a=list&topic_id=<?= $topic['id']?>">Показать вопросы</a></li>
            <?php if ($userType == ADMIN_CODE) : ?>
                <li>
                <?php $href = '"?c=questions&a=list&topic_id='. $topic['id'].'&filter=unanswered"'; ?>
                  <a href=<?= $href ?>>Показать неотвеченные вопросы</a>
                </li>
                <li>
                    <?php $href = '"?c=questions&a=list&topic_id='. $topic['id'].'&filter=unpublished"'; ?>
                    <a href=<?= $href ?>>Показать неопубликованные вопросы</a>
                </li>
            <?php endif;?>
        <?php endif;?>
              </ul>
            </div>
    <?php endif;?>
    <?php if ($intrusionHere) : ?>
        <?= $intrusion['block'] ?>
    <?php endif;?>
          </li>
<?php endforeach; ?>
        </ul>
      </div>

      <div class="topics-list-section-footer">

<?php if ($userType == ADMIN_CODE) : ?>
    <?php if (!$addTopic) : ?>
      <div class="topics-list-ops-div">
        <p><a href="?c=topics&a=item&id=-1">Добавить категорию</a></p>
      </div>
    <?php endif;?>
<?php endif;?>
    </div>
  </div>
</div>