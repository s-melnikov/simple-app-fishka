<?php

$error = null;
$message = null;

if ($_POST['db-host']) {

  // Create connection
  $conn = new mysqli($_POST['db-host'], $_POST['db-user'], $_POST['db-pass']);

  // Check connection
  if ($conn->connect_error) {

    $error = 'Connection failed: ' . $conn->connect_error;
  } else {

    $sql = file_get_contents('database.sql');
    $sql = preg_replace('/{{dbname}}/', $_POST['db-name'], $sql);

    if ($conn->multi_query($sql)) {
      $config = array(
        'db.host' => $_POST['db-host'],
        'db.user' => $_POST['db-user'],
        'db.pass' => $_POST['db-pass'],
        'db.name' => $_POST['db-name']
      );

      $conf_string = '';
      foreach ($config as $key => $value) {
        $conf_string .= ($conf_string ? ",\n  " : "") . "'{$key}' => '{$value}'";
      }
      $conf_string = "<?php\n\n\$configs = array(\n\t{$conf_string}\n);\n\n?>";
      file_put_contents('../sys/configs.php', $conf_string);
      $message = 'Database created successfully';

    } else {
      $error = 'Error creating database: ' . $conn->error;
    }

    $conn->close();
  }
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Установка приложения</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic">
  <link rel="stylesheet" href="../assets/css/scooter.css">
</head>
<body>

<div class="o-wrap">
  <div class="o-grid">

  <?php if ($error || $message) { ?>

    <div class="o-grid__col--1-of-2 o-grid__col--push-1-of-4">
      <?php if ($err) { ?>
        <div class="c-banner c-banner--error">
          <?php echo $error ?> <br>
          <a href="">Аоаробовать еще раз</a>
        </div>
      <?php } ?>

      <?php if ($message) { ?>
        <div class="c-banner c-banner--success">
          <?php echo $message ?> <br>
          <a href="../">На главную</a>
        </div>
      <?php } ?>
    </div>

  <?php } else { ?>

    <div class="o-grid__col--1-of-3 o-grid__col--push-1-of-6">
      <form method="POST">
        <h1>Установка</h1>
        <h3>Параметры подключения к базе данных</h3>
        <label class="c-label">
          Хост базы данных
          <input class="c-input" name="db-host" value="localhost" required>
        </label>
        <label class="c-label">
          Пользователь базы данных
          <input class="c-input" name="db-user" value="root" required>
        </label>
        <label class="c-label">
          Пароль базы данных
          <input class="c-input" name="db-pass" value="">
        </label>
        <label class="c-label">
          Имя базы данных
          <input class="c-input" name="db-name" value="my_app_database" required>
        </label>
        <button class="c-btn c-btn--primary">Установка</button>
      </form>
    </div>

  <?php } ?>
  </div>
</div>

</body>
</html>