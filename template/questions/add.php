<div class="question-list-section-add">
  <div class="question-add-wrapper">  
    <div class="question-add-nav-div">
      <p><a href="?c=questions&a=list&topic_id=<?= $question['topic_id'] ?>">Закрыть</a></p>    
    </div>
    
    <form class="question-add-form" action="" method="get">
      <input type="hidden" name="c" value="questions">
      <input type="hidden" name="a" value="add">
      <input type="hidden" name="topic_id" value="<?= $question['topic_id'] ?>">
      <div class="question-add-form-header">
        <h2 class="questions-add-form-heading">Добавить вопрос:</h2>  
      </div>      
      <div class="question-add-form-body">
        <textarea class="question-add-textarea" rows="10" name="text"></textarea>

<?php if (!(isset($_SESSION['user']['mail']))):?>
        <p>Имя:</p>
        <p><input type="text" name="login" value=""></p>
        <p>Электронная почта:</p>
        <p><input type="email" name="mail" value=""></p>        
<?php endif; ?>    

      </div>
      <div class="question-add-form-footer">
        <input type="submit" value="Добавить">
      </div>
        
    </form>
  </div>  
</div>