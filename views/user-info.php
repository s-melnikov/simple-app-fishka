<?php

if (!$permissions['users:show']) {
  include 'views/error403.php';
  exit;
}

$user_info = null;

$user_id = isset($_GET['id']) ? $_GET['id'] : null;
$user_id = (int) $user_id;

$sql = "SELECT * FROM users WHERE id = {$user_id}";
$result = $connect->query($sql);

$result_array = $result->fetch_all(MYSQLI_ASSOC);
$user_info = $result_array[0];

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

  <h1>Личные данные</h1>

  <?php if (!$user_info): ?>
    <div class="c-banner c-banner--error u-l-p">
      Пользоваетль с id: "<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>" не найден!
    </div>
  <?php else: ?>
    <h2><?php echo $user_info['firstname']?> <?php echo $user_info['lastname']?> (<?php echo $user_info['login']?>)</h2>

    <div class="o-grid">
      <div class="o-grid__col o-grid__col--1-of-3">

        <h4>E-mail</h4>
        <p><?php echo $user_info['email'] ?></p>

        <h4>Дата рождения</h4>
        <p><?php echo $user_info['birthday'] ?></p>

        <h4>Телефон</h4>
        <p><?php echo $user_info['phonenumber'] ?></p>

        <?php if ($permissions['users:edit']): ?>
          <p><a href="?page=user-edit&id=<?php echo $user_info['id'] ?>">Редактировать</a></p>
        <?php endif; ?>

      </div>
      <div class="o-grid__col o-grid__col--1-of-3">

        <h4>Тип</h4>
        <p><?php echo $user_info['type'] ?></p>

        <h4>Статус</h4>
        <p><?php echo $user_info['status'] ?></p>

      </div>
    </div>
  <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>