<?php

$login = isset($_POST['login']) ? trim($_POST['login']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;

if ($login && $password) {

  # создаем подключение БД
  $connect = new mysqli($configs['db.host'], $configs['db.user'], $configs['db.pass'], $configs['db.name']);

  # если есть текст ошибки в connect_error, вызываем свою ошибку
  if ($connect->connect_error) {
    $error_message = 'Ошибка подключения к базе данных: ' . $connect->connect_error;
    include 'views/error.php';
    exit;
  }

  $login = $connect->real_escape_string($login);
  $sql = "SELECT * FROM users WHERE login = '{$login}'";
  $result = $connect->query($sql);

  $result_array = $result->fetch_all(MYSQLI_ASSOC);
  $user = $result_array[0];

  if (!$user || hash('sha256', $password) !== $user['hash']) {
    $error_message = 'Введен неправильный логин или пароль!';
  } else {
    $_SESSION['my_app_authenticated_user'] = $user['id'];
    header('Location: ./');
  }
}
include 'views/header.php';
?>

<div class="o-wrap">
  <div class="o-grid">
    <div class="o-grid__col o-grid__col--1-of-3 o-grid__col--push-1-of-3">
      <?php if ($error_message) { ?>
        <div class="c-banner c-banner--error u-l-p"><?php echo $error_message ?></div>
      <?php } ?>
      <div class="c-card u-l-b login-form">
        <form method="post">
          <label class="c-label">
            Логин
            <input class="c-input" type="text" name="login" value="<?php echo $login ?>" required>
          </label>
          <label class="c-label">
            Пароль
            <input class="c-input" type="password" name="password" value="<?php echo $password ?>" required>
          </label>
          <div class="u-font-center">
            <button class="c-btn c-btn--primary">Войти</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'views/footer.php' ?>