<?php

if (!$permissions['users:edit']) {
  include 'views/error403.php';
  exit;
}

$edited_user = null;
$insert_id = null;

$edited_user_id = isset($_GET['id']) ? $_GET['id'] : null;
$edited_user_id = (int) $edited_user_id;

$user_types = array('user', 'editor', 'administrator', 'superuser');
$user_statuses = array('active', 'inactive');

if ($edited_user_id) {
  $sql = "SELECT * FROM users WHERE id = {$edited_user_id}";
  $result = $connect->query($sql);

  $result_array = $result->fetch_all(MYSQLI_ASSOC);
  $edited_user = $result_array[0];
}

if (isset($_POST['login'])) {

  $edited_user = array();

  $edited_user['login'] = trim($_POST['login']);
  $edited_user['firstname'] = trim($_POST['firstname']);
  $edited_user['lastname'] = trim($_POST['lastname']);
  $edited_user['birthday'] = $_POST['birthday'];
  $edited_user['email'] = $_POST['email'];
  $edited_user['phonenumber'] = $_POST['phonenumber'];
  $edited_user['status'] = $_POST['status'];
  $edited_user['type'] = $_POST['type'];

  $edited_user['password'] = $_POST['password'];
  $edited_user['password_confirm'] = $_POST['password-confirm'];

  $hash = null;

  if ($edited_user_id) {

    if ($edited_user['password'] && $edited_user['password'] !== $edited_user['password_confirm']) {
      $error_message = 'Пароли не совпадают.';
    } else {
      if ($edited_user['password']) $hash = hash('sha256', $edited_user['password']);
      $sql = "UPDATE users
        SET
          login = '{$edited_user['login']}',
          firstname = '{$edited_user['firstname']}',
          lastname = '{$edited_user['lastname']}',
          birthday = '{$edited_user['birthday']}',
          email = '{$edited_user['email']}',
          phonenumber = '{$edited_user['phonenumber']}',
          status = '{$edited_user['status']}',
          type = '{$edited_user['type']}'".
          ($hash ? ", hash = '{$hash}'" : "").
        " WHERE id = {$edited_user_id}";
      $result = $connect->query($sql);
      if ($result !== false) {
        $message = 'Изменения успешно сохранены';
      }
    }
  } else {

    if (!$edited_user['password']) {
      $error_message = 'Пароль не введен!';
    } elseif ($edited_user['password'] !== $edited_user['password_confirm']) {
      $error_message = 'Пароли не совпадают!';
    } else {
      $hash = hash('sha256', $edited_user['password']);
      $sql = "INSERT INTO users
        (login, firstname, lastname, birthday, email, phonenumber, status, type, hash)
        VALUES (
          '{$edited_user['login']}',
          '{$edited_user['firstname']}',
          '{$edited_user['lastname']}',
          '{$edited_user['birthday']}',
          '{$edited_user['email']}',
          '{$edited_user['phonenumber']}',
          '{$edited_user['status']}',
          '{$edited_user['type']}',
          '{$hash}')";
      $result = $connect->query($sql);
      $insert_id = $connect->insert_id;

      if ($result !== false) {
        $message = 'Новый пользователь успешно создан';
      }
    }
  }
}

include 'views/header.php' ?>

<header>
  <div class="o-wrap">
    <div class="o-grid">
      <div class="o-grid__col o-grid__col-1of-2">
        <a href="./" class="logo">ExampleApp</a>
        <nav>
          <?php if ($permissions['ideas:show']): ?>
            <a href="?page=ideas-list">Идеи</a>
          <?php endif?>
          <?php if ($permissions['users:show']): ?>
            <a href="?page=users-list">Пользователи</a>
          <?php endif?>
        </nav>
      </div>
      <div class="o-grid__col o-grid__col-1of-2 u-l-fr">
        <nav>
          <a href="?page=logout">Выход</a>
        </nav>
      </div>
    </div>
  </div>
</header>

<div class="o-wrap">

  <?php if ($insert_id): ?>
    <div class="c-banner u-l-p">
      Пользователь
        <a href="?page=user-edit&id=<?php echo $insert_id ?>">
          <?php echo $edited_user['firstname']?>
          <?php echo $edited_user['lastname']?>
          (<?php echo $edited_user['login']?>)
        </a> успешно создан <br>
      <a href="?page=user-edit">создать нового пользователя</a> |
      <a href="?page=users-list">перейти к списку пользователей</a>
    </div>
  <?php else: ?>

    <?php if ($edited_user_id): ?>
      <h1>Редактирование данных</h1>
    <?php else: ?>
      <h1>Добавление пользователя</h1>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="c-banner u-l-p"><?php echo $message ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
      <div class="c-banner c-banner--error u-l-p"><?php echo $error_message ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="o-grid">
        <div class="o-grid__col o-grid__col--1-of-3">
          <label class="c-label">
            Логин
            <input class="c-input" type="text" name="login" value="<?php echo $edited_user ? $edited_user['login'] : '' ?>" required>
          </label>
          <label class="c-label">
            Имя
            <input class="c-input" type="text" name="firstname" value="<?php echo $edited_user ? $edited_user['firstname'] : '' ?>" required>
          </label>
          <label class="c-label">
            Фамилия
            <input class="c-input" type="text" name="lastname" value="<?php echo $edited_user ? $edited_user['lastname'] : '' ?>" required>
          </label>
          <label class="c-label">
            E-mail
            <input class="c-input" type="email" name="email" value="<?php echo $edited_user ? $edited_user['email'] : '' ?>" required>
          </label>
          <div class="o-wrap">
            <div class="o-grid">
              <div class="o-grid__col o-grid__col--1-of-2">
                <label class="c-label">
                  Дата рождения
                  <input class="c-input" type="date" name="birthday" value="<?php echo $edited_user ? $edited_user['birthday'] : '' ?>" required>
                </label>
              </div>
              <div class="o-grid__col o-grid__col--1-of-2">
                <label class="c-label">
                  Телефон
                  <input class="c-input" type="phone" name="phonenumber" value="<?php echo $edited_user ? $edited_user['phonenumber'] : '' ?>" required>
                </label>
              </div>
            </div>
          </div>
          <div class="o-wrap">
            <div class="o-grid">
              <div class="o-grid__col o-grid__col--1-of-2">
                <label class="c-label">
                  Тип
                  <select name="type" class="c-input">
                    <?php foreach ($user_types as $type): ?>
                      <option value="<?php echo $type ?>" <?php echo $edited_user && $edited_user['type'] == $type ? "selected" : "" ?> >
                        <?php echo $type ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </label>
              </div>
              <div class="o-grid__col o-grid__col--1-of-2">
                <label class="c-label">
                  Статус
                  <select name="status" class="c-input">
                    <?php foreach ($user_statuses as $status): ?>
                      <option value="<?php echo $status ?>" <?php echo $edited_user && $edited_user['status'] == $status ? "selected" : "" ?> >
                        <?php echo $status ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </label>
              </div>
            </div>
          </div>
          <label class="c-label">
            Пароль
            <input class="c-input" type="password" name="password">
          </label>
          <label class="c-label">
            Подтверждение пароля
            <input class="c-input" type="password" name="password-confirm">
          </label>
          <div class="u-font-right">
            <button class="c-btn c-btn--primary">Сохранить</button>
          </div>
        </div>
      </div>
    </form>

  <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>