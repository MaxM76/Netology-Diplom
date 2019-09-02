<div class="question-update-div">
  <div class="question-update-wrapper">  
    <div class="question-update-nav-div">
      <p><a href="?c=questions&a=list&topic_id=<?= $question['topic_id']?>">Закрыть</a></p>
    </div>

    <form class="question-update-form" action="index.php/?c=questions&a=update" method="get">
      <input type="hidden" name="id" value="<?= $question['question_id'] ?>">
      <div class="question-update-form-header">
        <h2 class="questions-update-form-heading">Изменение вопроса</h2>
      </div>
      <div class="question-update-form-body">
        <p class="question-update-text">Текст вопроса:</p>
        <textarea class="question-update-textarea" rows="10" name="text"><?= $question['text']?></textarea>
        
<?php if ($question['is_published'] == 1) {
    $ch ='checked ';
} else {
    $ch ='';
}?>
        <p class="question-update-text">
          <input type="checkbox" name="published" <?= $ch ?>value="true">Опубликовать
        </p>

        <p class="question-update-text">Переместить в  категорию:
          <select name="topic_id">
<?php foreach ($topics as $item) : ?>
    <?php if ($topic['topic_id'] === $item['topic_id']) {
        $sel = 'selected ';
    } else {
        $sel = '';
    }?>
            <option <?= $sel ?>value="<?=$item['topic_id']?>"><?=$item['text']?></option>
<?php endforeach ?>
          </select>
        </p>
      </div>

      <div class="question-update-form-footer">
        <input type="submit" value="Изменить">  
      </div>
    </form>
  </div>  
</div>