<?php

if (!$permissions['ideas:edit']) {
  include 'views/error403.php';
  exit;
}

$idea = null;

$idea_id = isset($_GET['id']) ? $_GET['id'] : null;
$idea_id = (int) $idea_id;

if ($idea_id) {
  $sql = "SELECT * FROM ideas WHERE id = {$idea_id}";
  $result = $connect->query($sql);

  $result_array = $result->fetch_all(MYSQLI_ASSOC);
  $idea = $result_array[0];
}

if (isset($_POST['title'])) {

  $idea['title'] = trim($_POST['title']);
  $idea['content'] = trim($_POST['content']);

  if ($idea_id) {
    $sql = "UPDATE ideas SET
      title = '{$idea['title']}',
      content = '{$idea['content']}',
      lasteditor = {$user['id']},
      updated = NOW()
      WHERE id = {$idea_id}";
    $result = $connect->query($sql);
    if ($result !== false) {
      $message = 'Запись успешно обновлена!';
    }
  } else {
    $sql = "INSERT INTO ideas
      (title, content, creator, lasteditor, status, created, updated) VALUES
      ('{$idea['title']}', '{$idea['content']}', {$user['id']}, {$user['id']}, 'new', NOW(), NOW())";
    $result = $connect->query($sql);
    if ($result !== false) {
      $insert_id = $connect->insert_id;
      $message = 'Запись успешно создана!';
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
      Новая идея успешно добавлена <br>
      <a href="?page=idea-edit&id=<?php echo $insert_id ?>">прейти редактированию</a> |
      <a href="?page=idea-edit">создать новую запись</a> |
      <a href="?page=idea-list">перейти к списку</a>
    </div>
  <?php else: ?>

    <?php if ($edited_user_id): ?>
      <h1>Редактирование</h1>
    <?php else: ?>
      <h1>Создание</h1>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="c-banner u-l-p"><?php echo $message ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
      <div class="c-banner c-banner--error u-l-p"><?php echo $error_message ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="o-grid">
        <div class="o-grid__col o-grid__col--5-of-6">
          <label class="c-label">
            Заголовок
            <input class="c-input" type="text" name="title" value="<?php echo $idea ? $idea['title'] : '' ?>" required>
          </label>
          <label class="c-label">
            Содержание
            <textarea class="c-input" name="content" rows="16" required><?php echo $idea ? $idea['content'] : '' ?></textarea>
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