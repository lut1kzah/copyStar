<?php
if (isset($_SESSION['login']['id'])) {
    header('Location: index.php');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = htmlspecialchars($_POST["surname"]);
    $name = htmlspecialchars($_POST["name"]);
    $patronymic = htmlspecialchars($_POST["patronymic"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $password_repeat = htmlspecialchars($_POST["password_repeat"]);
    $login = htmlspecialchars($_POST["login"]);

    //Proverka porolya
    if ($password != $password_repeat) {
        $error = "Не совпадают пороли!";
    }else {
        // Proverka login/email Unique
        $sql_check = "SELECT * FROM `users` WHERE `login` = '$login' OR `email` = '$email'";
        $result = $conn->query($sql_check);
        if ($result->rowCount()) {
            $error = "уже есть такой пользователь";
        } else {
            $sql = "SELECT `id` FROM `roles` WHERE `code` = 'client'";
            $result = $conn->query($sql);
            $role_id = $result->fetchColumn();
            // plus login
            $sql = "INSERT INTO `users` (`id`, `surname`, `name`, `patronymic`, `login`, `email`, `password`, `role_id`) 
            VALUES (NULL, '$surname', '$name', $patronymic, '$login', '$email', '$password', '$role_id')";
            $conn->query($sql);

            $sql = "SELECT * FROM `users` WHERE `login` = $login AND `password` = '$password'";
            $result = $conn->query($sql);
            if ($result->rowCount()) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $_SESSION['login'] = $row;
                header('Location: index.php');
            }
            $error = "Неправильные данные Х_х!";
        }
    }
}


?>
<section class="p-4 d-flex flex-column gap-3">
    <h2>Регистрация</h2>
    <?= isset($error) ? $error : "" ?>
    <form class="d-flex flex-column gap-3" action="index.php?page=reg" method="post">
        <input class="form-control" type="text" name="surname" placeholder="Фамилия" required>
        <input class="form-control" type="text" name="name" placeholder="Имя" required>
        <input class="form-control" type="text" name="patronomic" placeholder="Отчество" required>
        <input class="form-control" type="text" name="login" placeholder="Логин" required>
        <input class="form-control" type="password" name="password" placeholder="Введите пороль" required>
        <input class="form-control" type="password" name="password_repeat" placeholder="Проверка пороль" required>
        <input class="form-control" type="email" name="email" placeholder="емаил" required>
        <label><input type="checkbox" required>Я ознакомлен с<a href="#"> правилами сайта</a></label>
        <button class="btn btn-dark" type="submit">Вход</button>
    </form>
</section>
