<form action="index.php?c=users&a=add" method="post">
  <tr>
    <td><input type="text" name="login" value=""></td>
    <td><input type="text" name="password" value=""></td>
          
    <td>
      <select name="type">
<?php foreach (USER_TYPES as $key => $value) : ?>
    <?php $selectedAttribute = ($value == USER_CODE) ? 'selected ' : '';?>
        <option <?= $selectedAttribute ?>value="<?=$value?>"><?=$key?></option>
<?php endforeach ?>
      </select>
    </td>

    <td><input type="email" name="email" value=""></td>
    <td>
      <div class="user-ops-div">
        <ul class="user-ops-list">  
          <li><input class=user-update-button type="submit" value="Добавить"></li>
          <li><a href="index.php?c=users&a=list">Отменить</a></li>
        </ul>
      </div>
    </td>         
           
  </tr>
</form>