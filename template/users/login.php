<div class="user-login-div">
    <h1>Аутентификация</h1>

    <form action="/?c=users&a=login" method="post">
        <!--
        <input type="hidden" name="c" value="users">
        <input type="hidden" name="a" value="login">
        -->

        <p>Логин: <input type="text" name="login" value=""></p>
        <p>Пароль: <input type="password" name="password" value=""></p>
    
        <p><input type="submit" value="Подтвердить"></p>
    </form>

    <p><a href="?c=topics&a=list">Продолжить как гость</a></p>
    <p><a href="?c=users&a=register">Зарегистрироваться</a></p>
    <p><a href="index.php">Перейти на стартовую страницу</a></p>

</div>