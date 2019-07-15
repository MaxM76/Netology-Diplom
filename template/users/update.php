  <tr>
    <td><input type="text" name="login" value="<?=$user['login']?>"></td>
    <td><input type="text" name="password" value="<?=$user['password']?>"></td>
          
    <td>
      <select name="type">
<?php foreach (USER_TYPES as $key => $value) : ?>
    <?php if ($value == $user['type']) { $sel = 'selected ';} else {$sel = '';}?>
        <option <?= $sel ?>value="<?=$value?>"><?=$key?></option>
<?php endforeach ?>
      </select>
    </td>

    <td><input type="email" name="mail" value="<?=$user['mail']?>"></td>
    <td>
      <div class="user-ops-div">
        <ul class="user-ops-list">  
          <li><a href="?c=users&a=delete&id=<?= $user['user_id']?>">Удалить</a></li>
          <li><a href="?c=users&a=update&id=<?= $user['user_id']?>">Изменить</a></li>
          <li><a href="?c=users&a=list">Отменить</a></li>
        </ul>
      </div>
    </td>         
           
  </tr>