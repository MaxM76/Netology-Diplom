<div class="users-list-section">
  <div class="users-list-section-wrapper">
    <div class="users-list-section-header">
      <h1 class="users-list-heading">Список пользователей</h1>
    </div>

    <div class="users-list-section-body">
      <table class="users-list-table">
        <tr class="users-list-table-header">
          <td>Логин</td>
          <td>Пароль</td>
          <td>Тип</td>
          <td>Адрес</td>
          <td>Операции</td>
        </tr>

<?php foreach ($users as $user) : ?>
    <?php if ((isset($intrusion['user_id']) && ($user['id'] != $intrusion['user_id']))
            || (isset($intrusion['type']) && ($intrusion['type'] == 'insert'))
            || !(isset($intrusion['user_id']))) : ?>
        <!--[c] слишком сложная конструкция, проверка бы куда-то на уровень контроллера убрать, либо скрыть за какой-то фукнцией -->
        <tr>
          <td><?= $user['login']?></td>
          <td><?= $user['password']?></td>
          <td><?= INV_USER_TYPES[$user['type']]?></td>
          <td><a href="mailto:<?= $user['email']?>"><?= $user['email']?></a></td>
          <td>
            <div class="user-ops-div">
              <ul class="user-ops-list">  
                <li><a href="?c=users&a=delete&id=<?= $user['id']?>">Удалить</a></li>
                <li><a href="?c=users&a=item&id=<?= $user['id']?>">Изменить</a></li>
              </ul>
            </div>
          </td>         
            
        </tr>
               
    <?php endif;?>

    <?php if (isset($intrusion['user_id']) && ($user['id'] == $intrusion['user_id'])) : ?>
        <?= $intrusion['block'] ?>
    <?php endif;?> 

<?php endforeach; ?>

<?php if (isset($intrusion['user_id']) && ($intrusion['user_id'] == -1) && ($intrusion['block'] != '')) : ?>
    <?= $intrusion['block'] ?>
<?php endif;?>

      </table>
    </div>  

    <div class="users-list-section-footer">
      <div class="users-list-ops-div">  
        <p><a href="?c=users&a=item&id=-1">Добавить пользователя</a></p>
      </div>
    </div>
  </div>
</div>