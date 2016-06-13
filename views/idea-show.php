<?php

if (!$permissions['ideas:show']) {
  include 'views/error403.php';
  exit;
}

$idea = null;
$creator = null;
$lasteditor = null;
$comments = null;
$idea_id = isset($_GET['id']) ? (int) $_GET['id'] : null;

$sql = "SELECT * FROM ideas WHERE ideas.id = {$idea_id}";

$result = $connect->query($sql);

$result_array = $result->fetch_all(MYSQLI_ASSOC);
$idea = $result_array[0];

if ($idea) {
  $sql = "SELECT firstname, lastname FROM users WHERE id = {$idea['creator']}";
  $result = $connect->query($sql);
  $result_array = $result->fetch_all(MYSQLI_ASSOC);
  $creator = $result_array[0];

  $sql = "SELECT firstname, lastname FROM users WHERE id = {$idea['lasteditor']}";
  $result = $connect->query($sql);
  $result_array = $result->fetch_all(MYSQLI_ASSOC);
  $lasteditor = $result_array[0];

  if (isset($_POST['comment-text']) && trim($_POST['comment-text'])) {
    $comment_text = $_POST['comment-text'];
    $comment_text = $connect->real_escape_string($comment_text);
    $sql = "INSERT INTO comments (idea_id, creator, content, created) VALUES
      ({$idea['id']}, {$user['id']}, '{$comment_text}', NOW())";
    $result = $connect->query($sql);
    if ($connect->error) debug($connect->error);
  }

  $sql = "SELECT
    comments.content,
    comments.created,
    users.firstname,
    users.lastname
  FROM comments
  INNER JOIN users
  ON comments.creator = users.id
  WHERE comments.idea_id = {$idea['id']}";
  $result = $connect->query($sql);
  $comments = $result->fetch_all(MYSQLI_ASSOC);
}

include 'views/header.php';
?>

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

  <?php if (!$idea): ?>

    <p>Новость с id: "<?php echo $_GET['id'] ?>" не найдена!</p>

  <?php else: ?>

    <h1><?php echo $idea['title'] ?></h1>

    <div class="o-grid u-l-p">
      <div class="o-grid__col o-grid__col--3-of-4">
        <div class="idea-content">
          <?php echo markdown($idea['content']) ?>
        </div>
      </div>
      <div class="o-grid__col o-grid__col--1-of-4">
        <div class="u-font-small idea-meta">
          <?php if ($permissions['ideas:edit']): ?>
            <div class="u-l-p">
              <a href="?page=idea-edit&id=<?php echo $idea['id'] ?>">Редактировать</a>
            </div>
          <?php endif?>
          <div class="u-l-p">
            создатель: <br>
            <?php echo $creator['firstname'] ?> <?php echo $creator['lastname'] ?>
          </div>
          <div class="u-l-p">
            создано: <br>
            <?php echo $idea['created'] ?>
          </div>
          <div class="u-l-p">
            статус: <br>
            <?php echo $idea['status'] ?>
          </div>
          <div class="u-l-p">
            изменено: <br>
            <?php echo $idea['updated'] ?> <br>
            кем: <br>
            <?php echo $lasteditor['firstname'] ?> <?php echo $lasteditor['lastname'] ?>
          </div>
        </div>
      </div>
    </div>

    <?php if ($comments): ?>
      <div class="o-grid">
        <div class="o-grid__col o-grid__col--2-of-3 comments">
          <h4 class="u-l-p">Коментарии</h5>
          <?php foreach ($comments as $comment): ?>
            <div class="u-l-p comment">
              <?php echo markdown($comment['content']) ?>
              <div class="u-font-meta u-font-small">
                <?php echo $comment['firstname'] ?> <?php echo $comment['lastname'] ?> |
                <?php echo $comment['created'] ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="o-grid">
      <div class="o-grid__col o-grid__col--1-of-3">
        <h4 class="u-l-p">Добавить коментарий</h5>
        <form method="post">
          <textarea class="c-input u-l-p" name="comment-text" rows="3" class=""></textarea>
          <button class="c-btn c-btn--primary">Добавить</button>
        </form>
      </div>
    </div>

  <?php endif; ?>

</div>

<?php include 'views/footer.php'; ?>

