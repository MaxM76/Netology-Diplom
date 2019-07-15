<div class="answers-update-div">
  <div class="answers-update-wrapper">
    <div class="answers-update-nav-div">
  
    </div>

    <form class="answer-update-form" action="index.php" method="get">
      <input type="hidden" name="c" value="answers">
      <input type="hidden" name="a" value="update">
      <input type="hidden" name="answer_id" value="<?= $answer['answer_id']?>">        
      <input type="hidden" name="question_id" value="<?= $answer['question_id']?>">
      
      <div class="answer-update-form-header">
        <h3 class="answer-update-form-heading">Изменение ответа</h3>        
      </div>

      <div class="answer-update-form-body">
        <textarea class="answer-update-textarea" rows="10" name="text"><?= $answer['text']?></textarea>
      </div>

      <div class="answer-update-form-footer">
        <p><input type="submit" value="Изменить"></p>  
      </div>

    </form>
  </div>  
</div>