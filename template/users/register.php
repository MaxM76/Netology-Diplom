<div style="border: 4px double black">
    <h1>Регистрация</h1>

    <form action="index.php/?c=users&a=register" method="post">
        <!--
        <input type="hidden" name="c" value="users">
        <input type="hidden" name="a" value="register">
    -->
        <p>Логин: <input type="text" name="login" value=""></p>
        <p>Пароль: <input type="password" name="password1" value=""></p>
        <p>Еще раз: <input type="password" name="password2" value=""></p>
        <p>Электронная почта: <input type="email" name="mail" value=""></p>    
    <p><input type="submit" value="Подтвердить"></p>
    </form>

    <p><a href="?c=topics&a=list">Войти как гость</a></p>
    <p><a href="?c=users&a=login">Авторизироваться</a></p>

</div>