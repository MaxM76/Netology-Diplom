<div class="topics-list-section-update">

  <h1 class="topics-update-heading">Редактирование темы</h1>
  <div class="topics-update-div">
    <form action="/" method="get">
      <input type="hidden" name="c" value="topics">
      <input type="hidden" name="a" value="update">
	  <input type="hidden" name="id" value="<?= $topic['id']?>">

      <p class="topics-update-text">Название:</p>
      <p><input class="topic-description-input" type="text" name="text" value="<?= $topic['text']?>"></p>
      <p class="topics-update-text">Описание:</p> 
	  <p>
        <textarea class="topic-description-textarea" rows="10" name="description"><?= $topic['description']?></textarea>
	  </p>
      <p>Добавлена: <?= $topic['created_at']?></p>
      <p><input type="submit" value="Изменить"></p>
    </form>
  </div>
  <p><a href="?c=topics&a=list">Закрыть</a></p>

</div>