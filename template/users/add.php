  <tr>
    <td><input type="text" name="login" value=""></td>
    <td><input type="text" name="password" value=""></td>
          
    <td>
      <select name="type">
<?php foreach (USER_TYPES as $key => $value) : ?>
    <?php if ($value == '1') { $sel = 'selected ';} else {$sel = '';}?>
        <option <?= $sel ?>value="<?=$value?>"><?=$key?></option>
<?php endforeach ?>
      </select>
    </td>

    <td><input type="email" name="mail" value=""></td>
    <td>
      <div class="user-ops-div">
        <ul class="user-ops-list">  
          <li><a href="?c=users&a=add">Добавить</a></li>
          <li><a href="?c=users&a=list">Отменить</a></li>
        </ul>
      </div>
    </td>         
           
  </tr>