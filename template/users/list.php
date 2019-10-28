<?php $isIntrusionExist = isset($intrusion['block']) && ($intrusion['block'] != '');?>
<?php $addUser = $isIntrusionExist
    && isset($intrusion['user_id'])
    && ($intrusion['user_id'] == UNKNOWN_ITEM_ID); ?>
<?php $isInsertIntrusion = $isIntrusionExist && isset($intrusion['type']) && ($intrusion['type'] == 'insert'); ?>
<?php $isReplaceIntrusion = $isIntrusionExist && isset($intrusion['type']) && ($intrusion['type'] == 'replace')?>
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

    <?php $intrusionHere = $isIntrusionExist
        && isset($intrusion['user_id'])
        && ($user['id'] == $intrusion['user_id']); ?>

    <?php if ($intrusionHere && $isReplaceIntrusion) : ?>
        <?= $intrusion['block']?>
    <?php else : ?>
        <tr>
          <td><?= $user['login']?></td>
          <td><?= $user['password']?></td>
          <td><?= INV_USER_TYPES[$user['type']]?></td>
          <td><a href="mailto:<?= $user['email']?>"><?= $user['email']?></a></td>
          <td>
            <div class="user-ops-div">
              <ul class="user-ops-list">  
                <li><a href="index.php?c=users&a=delete&id=<?= $user['id']?>">Удалить</a></li>
                <li><a href="index.php?c=users&a=item&id=<?= $user['id']?>">Изменить</a></li>
              </ul>
            </div>
          </td>         
            
        </tr>
               
    <?php endif;?>

<?php endforeach; ?>

<?php if ($addUser) : ?>
    <?= $intrusion['block'] ?>
<?php endif;?>

      </table>

    </div>  

    <div class="users-list-section-footer">
      <div class="users-list-ops-div">  
        <p><a href="index.php?c=users&a=item&id=-1">Добавить пользователя</a></p>
      </div>
    </div>
  </div>
</div>