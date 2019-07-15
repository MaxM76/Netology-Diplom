<div class="answers-add-div">
  <div class="answers-add-wrapper"> 
    <div class="answers-add-nav-div">
      
    </div>

    <form class="answer-add-form" action="/" method="get">
      <input type="hidden" name="c" value="answers">
      <input type="hidden" name="a" value="add">
      <input type="hidden" name="question_id" value="<?= $answer['question_id']?>">
      
      <div class="answer-add-form-header">
        <h3 class="answer-add-form-heading">Добавление ответа</h3>        
      </div>

      <div class="answer-add-form-body">
        <textarea class="answer-add-textarea" rows="10" name="text"></textarea>
      </div>

      <div class="answer-add-form-footer">
        <p><input type="submit" value="Добавить"></p>    
      </div>

    </form>
  </div> 
</div>