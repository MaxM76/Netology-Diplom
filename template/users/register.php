<div>
  <h1>Регистрация</h1>

    <form action="index.php?c=users&a=register" method="post">
      <p>Логин: <input type="text" name="login" value=""></p>
      <p>Пароль: <input type="password" name="password1" value=""></p>
      <p>Еще раз: <input type="password" name="password2" value=""></p>
      <p>Электронная почта: <input type="email" name="email" value=""></p>
      <p><input type="submit" value="Подтвердить"></p>
    </form>
    <p><a href="index.php?c=topics&a=list">Войти как гость</a></p>
    <p><a href="index.php?c=users&a=gotologin">Авторизироваться</a></p>
</div>index.php