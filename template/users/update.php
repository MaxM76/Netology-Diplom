<form action="index.php?c=users&a=update&id=<?= $user['id']?>" method="post">
  <tr>

    <td><input type="text" name="login" value="<?=$user['login']?>"></td>
    <td><input type="text" name="password" value="<?=$user['password']?>"></td>
          
    <td>
      <select name="type">
<?php foreach (USER_TYPES as $key => $value) : ?>
        <?php $selectedAttribute = ($value == $user['type']) ? 'selected ' : '';?>
        <option <?= $selectedAttribute ?>value="<?=$value?>"><?=$key?></option>
<?php endforeach ?>
      </select>
    </td>

    <td>
      <input type="email" name="email" value="<?=$user['email']?>">
    </td>

    <td>
      <div class="user-ops-div">
        <ul class="user-ops-list">  
          <li><a href="index.php?c=users&a=delete&id=<?= $user['id']?>">Удалить</a></li>
          <li><input class=user-update-button type="submit" value="Применить"></li>
          <li><a href="index.php?c=users&a=list">Отменить</a></li>
        </ul>
      </div>
    </td>

  </tr>
</form>
