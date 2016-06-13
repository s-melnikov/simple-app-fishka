<?php

if (!$permissions['ideas:show']) {
  include 'views/error403.php';
  exit;
}

$ideas = null;

$sql = "SELECT
  ideas.id,
  ideas.title,
  ideas.content,
  ideas.creator,
  ideas.lasteditor,
  ideas.status,
  ideas.created,
  ideas.updated,
  users.firstname,
  users.lastname
  FROM ideas
  INNER JOIN users
  ON ideas.creator = users.id
  WHERE ideas.status != 'deleted' AND ideas.status != 'archive'
  ORDER BY ideas.status, ideas.updated DESC";

$result = $connect->query($sql);

$ideas = $result->fetch_all(MYSQLI_ASSOC);

include 'views/header.php'
?>

<header>
  <div class="o-wrap">
    <div class="o-grid">
      <div class="o-grid__col o-grid__col-1of-2">
        <a href="./" class="logo">ExampleApp</a>
        <nav>
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

  <h1>Идеи</h1>

  <?php if ($message): ?>
    <div class="c-banner u-l-p"><?php echo $message ?></div>
  <?php endif; ?>

  <?php if ($error_message): ?>
    <div class="c-banner c-banner--error u-l-p"><?php echo $error_message ?></div>
  <?php endif; ?>

  <div class="u-l-p">
    <a href="?page=idea-edit">+ Добавить идею</a>
  </div>

  <div class="o-grid">
    <div class="o-grid__col o-grid__col--10-of-12">
      <div class="ideas">
        <h3>Новые</h3>
        <?php if ($ideas): ?>
          <?php foreach ($ideas as $idea): ?>
            <div class="idea idea-<?php echo $idea['status'] ?>">
              <h4><?php echo $idea['title'] ?></h4>
              <p><?php echo markdown($idea['content']) ?></p>
              <div class="u-font-small u-font-meta">
                <span>создано: <?php echo $idea['created'] ?></span> |
                <span>изменено: <?php echo $idea['updated'] ?></span> |
                <span>статус: <?php echo $idea['status'] ?></span> |
                <span>создатель: <?php echo $idea['firstname'] ?> <?php echo $idea['lastname'] ?></span> |
                <a href="?page=idea-show&id=<?php echo $idea['id'] ?>">подробнее</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <h4>Нет записей :(</h4>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include 'views/footer.php'; ?>