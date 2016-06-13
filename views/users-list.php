<?php

if (!$permissions['users:show']) {
  include 'views/error403.php';
  exit;
}

$users = null;

$sql = "SELECT * FROM users ORDER BY type";
$result = $connect->query($sql);

$users = $result->fetch_all(MYSQLI_ASSOC);

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
  <h1>Список пользователей</h1>

  <?php if ($message): ?>
    <div class="c-banner u-l-p"><?php echo $message ?></div>
  <?php endif; ?>

  <?php if ($error_message): ?>
    <div class="c-banner c-banner--error u-l-p"><?php echo $error_message ?></div>
  <?php endif; ?>

  <a href="?page=user-edit">+ Добавить пользователя</a>

  <div class="o-grid">
    <div class="o-grid__col o-grid__col--10-of-12">
      <?php if ($users): ?>
        <table class="users-table">
          <tr>
            <th>Логин</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Тип</th>
            <th>Статус</th>
            <?php if ($permissions['users:edit']): ?>
              <th>Действие</th>
            <?php endif; ?>
            <th></th>
          </tr>
          <?php foreach ($users as $user): ?>
            <tr class="<?php echo $user['status'] ?>">
              <td><?php echo $user['login'] ?></td>
              <td><?php echo $user['firstname'] ?></td>
              <td><?php echo $user['lastname'] ?></td>
              <td><?php echo $user['type'] ?></td>
              <td><?php echo $user['status'] ?></td>
              <?php if ($permissions['users:edit']): ?>
                <td>
                  <a href="?page=user-edit&id=<?php echo $user['id'] ?>">редактировать</a>
                </td>
              <?php endif; ?>
              <td>
                <a href="?page=user-info&id=<?php echo $user['id'] ?>">подробнее</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'views/footer.php'; ?>