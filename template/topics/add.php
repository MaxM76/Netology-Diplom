<div class="topics-list-section-add">
  <a name="new-topic"></a>
  <h1 class="topics-add-heading">Новая категория вопросов</h1>
  <div class="topics-add-div">
    <form action="index.php" method="get">
      <input type="hidden" name="c" value="topics">
      <input type="hidden" name="a" value="add">
      <p class="topics-add-text">Название:</p>
      <p><input class="topic-description-input" type="text" name="text" value=""></p>
      <p class="topics-add-text">Описание:</p>
      <p>
        <textarea class="topic-description-textarea" rows="10" name="description"></textarea>
      </p>
      <p><input type="submit" value="Добавить"></p>
    </form>
  </div>
  <p><a href="index.php?c=topics&a=list">Закрыть</a></p>
</div>
