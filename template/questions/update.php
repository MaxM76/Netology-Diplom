<form action="index.php#current-topic" method="get">
  <input type="hidden" name="c" value="questions">
  <input type="hidden" name="a" value="update">
  <input type="hidden" name="id" value="<?= $question['id'] ?>">
  <input type="hidden" name="filter" value="<?= $filter ?>">
  <input type="hidden" name="status" value="<?=$question['status']?>">

  <tr class="questions-list-table-updating-row">
    <td><input type="text" name="text" value="<?= $question['text']?>"></td>
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
      <input type="checkbox" name="is_published" <?= $checkedAttribute ?>>
    </td>
    <?php endif;?>

    <td>
      <div class="questions-list-item-ops-div">
        <ul class="question-list-ops-list">
    <?php if ($userType == ADMIN_CODE) : ?>
          <li><a href="index.php?c=questions&a=delete&id=<?= $question['id']?>&filter=<?= $filter?>">Удалить</a></li>

          <li>
            <input type="submit" class="question-update-button" value="Переместить в:">
              <select name="topic_id">
        <?php foreach ($topics as $item) : ?>
            <?php $selectedAttribute = ($question['topic_id'] == $item['id']) ? 'selected ' : '';?>
                <option <?= $selectedAttribute ?>value="<?=$item['id']?>">
                  <?= substr($item['text'], 0, 30); ?>
                </option>
        <?php endforeach ?>
              </select>
          </li>

          <li>
            <input type="submit" class="question-update-button" value="Применить изменения">
          </li>

          <li>
            <a href="index.php?c=questions&a=list&topic_id=<?= $question['topic_id']?>&filter=<?= $filter?>#current-topic">
                Отменить изменения
            </a>
          </li>
    <?php endif; ?>

    <?php if ($question['status'] == QUESTION_ANSWERED) : ?>
          <li>
            <a href="index.php?c=answers&a=list&question_id=<?= $question['id']?>&filter=<?= $filter?>">
              Показать ответ
            </a>
          </li>
    <?php endif;?>

                <?php if (($userType == ADMIN_CODE)
                    && ($question['status'] == QUESTION_NOT_ANSWERED)
                    && !(!($intrusion['hideAnswerQuestionButton']) && $intrusionHere)) : ?>
          <li>
            <a href="index.php?c=answers&a=item&answer_id=-1&question_id=<?= $question['id']?>&filter=<?= $filter?>">
              Ответить
            </a>
          </li>
                <?php endif;?>
        </ul>
      </div>
    </td>
  </tr>
</form>
