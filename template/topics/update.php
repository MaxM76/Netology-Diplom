<form action="index.php" method="get">
  <input type="hidden" name="c" value="topics">
  <input type="hidden" name="a" value="update">
  <input type="hidden" name="id" value="<?= $topic['id'] ?>">

  <div class="topic-item-ops-div">
<?php if ($userType == ADMIN_CODE) : ?>
    <ul class="topic-item-ops-list">
      <li><a href="index.php?c=topics&a=delete&id=<?= $topic['id']?>">Удалить</a></li>
    </ul>
<?php endif;?>
  </div>

  <div class="topic-header-div">
    <h3 class="topic-header">
      <input type="text" name="text" value="<?= $topic['text']?>">
    </h3>
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
    <p class="topics-update-text">Описание:</p>
    <p class="topic-description">
      <textarea class="topic-description-textarea" rows="10" name="description"><?= $topic['description']?></textarea>
    </p>
  </div>

  <div class="topic-item-ops-div">
<?php if ($userType == ADMIN_CODE) : ?>
    <ul class="topic-item-ops-list">
      <li>
        <input type="submit" class="topic-update-button" value="Применить изменения">
      </li>
      <li>
        <a href="index.php?c=topics&a=list">Отменить изменения</a>
      </li>
    </ul>
<?php endif;?>
  </div>
</form>
