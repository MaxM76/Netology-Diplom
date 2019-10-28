<?php $isIntrusionExist = isset($intrusion['block']) && ($intrusion['block'] != '');?>
<?php $isInsertIntrusion = $isIntrusionExist && isset($intrusion['type']) && ($intrusion['type'] == 'insert'); ?>
<?php $addTopic = $isIntrusionExist
    && isset($intrusion['topic_id'])
    && ($intrusion['topic_id'] == UNKNOWN_ITEM_ID)
    && ($intrusion['block'] != ''); ?>

<div class="topics-list-section">
  <div class="topics-list-section-wrapper">
<?php if ($userType == ADMIN_CODE) : ?>
    <?php if ($addTopic) : ?>
        <?= $intrusion['block'] ?>
    <?php else : ?>
    <div class="topics-list-ops-div">
      <p><a href="index.php?c=topics&a=item&id=-1#new-topic">Добавить категорию</a></p>
    </div>
    <?php endif;?>
<?php endif;?>
    <div class="topics-list-section-header">
      <h1 class="topics-list-heading">Существующие категории вопросов</h1>
    </div>

    <div class="topics-list-section-body">
      <ul class="topics-list">

<?php foreach ($topics as $topic) : ?>
    <?php $intrusionHere = $isIntrusionExist
        && isset($intrusion['topic_id'])
        && ($topic['id'] == $intrusion['topic_id']); ?>

    <?php if ($intrusionHere) : ?>
        <li class="topics-list-item" id="current-item">
          <a name="current-topic">Текущая категория</a>
    <?php else : ?>
          <li class="topics-list-item">
    <?php endif;?>

    <?php if (!$isIntrusionExist || ($isInsertIntrusion) || (!$intrusionHere)) : ?>
            <div class="topic-item-ops-div">
        <?php if ($userType == ADMIN_CODE) : ?>
              <ul class="topic-item-ops-list">
                <li><a href="index.php?c=topics&a=delete&id=<?= $topic['id']?>">Удалить</a></li>
            <?php if (!($intrusion['hideUpdateTopicButton'] && $intrusionHere)) : ?>
                <li><a href="index.php?c=topics&a=item&id=<?= $topic['id']?>#current-topic">Изменить</a></li>
            <?php endif; ?>
              </ul>
        <?php endif;?>
            </div>
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

            <div class="questions-list-view-ops-div">
              <ul class="questions-list-view-ops">
                <li>
                  <form action="index.php#current-topic" method="get">
                    <input type="hidden" name="c" value="questions">
                    <input type="hidden" name="a" value="list">
                    <input type="hidden" name="topic_id" value="<?= $topic['id']?>">
                    <input type="submit" value="Показать вопросы" class=question-view-button>
        <?php if ($userType == ADMIN_CODE) : ?>
                    <label for="filter">Фильтр</label>
                    <select name="filter">
            <?php foreach (FILTERS as $filterName => $filterCaption) : ?>
                <?php $selectedAttribute =
                    ($intrusionHere && ($intrusion['filter'] == $filterName )) ? 'selected ' : ''; ?>
                      <option <?= $selectedAttribute ?>value="<?= $filterName ?>">
                        <?= $filterCaption ?>
                      </option>
            <?php endforeach; ?>
                    </select>
        <?php else :?>
                    <input type="hidden" name="filter" value="<?= PUBLISHED_QUESTIONS ?>">
        <?php endif;?>
                  </form>
                </li>
        <?php if ($intrusionHere && !($intrusion['hideUpdateTopicButton'])) : ?>
                <li><a href="index.php?c=topics&a=list">Убрать список вопросов</a></li>
        <?php endif;?>
              </ul>
            </div>
    <?php endif; ?>
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
        <p><a href="index.php?c=topics&a=item&id=-1#new-topic">Добавить категорию</a></p>
      </div>
    <?php endif;?>
<?php endif;?>
    </div>
  </div>
</div>