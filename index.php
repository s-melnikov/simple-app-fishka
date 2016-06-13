<?php
session_start();

require 'sys/configs.php';
require 'sys/functions.php';
require 'sys/parsedown.php';
require 'sys/parsedown-extra.php';

$error_message = null;
$user = null;
$permissions = null;

$current_page = isset($_GET['page']) ? $_GET['page'] : 'ideas-list';

if ($current_page == 'logout') {
  $_SESSION['my_app_authenticated_user'] = null;
}

if (!$_SESSION['my_app_authenticated_user']) {
  if ($current_page !== 'login') {
    header('Location: ?page=login');
  }
} else {

  # создаем подключение БД
  $connect = new mysqli($configs['db.host'], $configs['db.user'], $configs['db.pass'], $configs['db.name']);

  # если есть текст ошибки в connect_error, вызываем свою ошибку
  if ($connect->connect_error) {
    $error_message = 'Ошибка подключения к базе данных: ' . $connect->connect_error;
    include 'views/error.php';
    exit;
  }

  $user_id = $_SESSION['my_app_authenticated_user'];
  $result = $connect->query("SELECT * FROM users WHERE id = {$user_id}");

  $result_array = $result->fetch_all(MYSQLI_ASSOC);
  $user = $result_array[0];

  if (!$user) {
    $error_message = 'Не найден пользователь с id: '.$user_id;
    include 'views/error.php';
    exit;
  }

  if ($user['status'] === 'inactive') {
    include 'views/error403.php';
    exit;
  }

  $sql = "SELECT p_name, p_type FROM permissions WHERE usertype = '{$user['type']}'";
  $result = $connect->query($sql);

  $result_array = $result->fetch_all(MYSQLI_ASSOC);
  $permissions = array();

  foreach ($result_array as $permission) {
    $permissions[$permission['p_name']] = !!$permission['p_type'];
  }
}

if (file_exists('views/' . $current_page . '.php')) {
  include 'views/' . $current_page . '.php';
} else {
  include 'views/error404.php';
}
/*

on('GET', '/users', function() {

  if (session('user')['type'] < 2) {
    error(403);
  }

  $crud = new CRUD('users');
  $users = $crud->select();

  render('users', array(
    'users' => $users,
    'user_types' => array(
      1 => 'manager',
      2 => 'oficer',
      3 => 'super user'
    )
  ));
});

on('GET', '/users/edit/', function() {
  if (session('user')['type'] < 3) {
    error(403);
  }
  render('users.edit', array(
    'user_types' => array(
      1 => 'manager',
      2 => 'oficer',
      3 => 'super user'
    )
  ));
});

on('POST', '/users/edit/', function() {
  if (session('user')['type'] < 3) {
    error(403);
  }

  $error = null;

  $user = array(
    'login' => params('login'),
    'firstname' => params('firstname'),
    'lastname' => params('lastname'),
    'type' => params('type')
  );

  if (!params('password')) {
    $error = 'Поле "пароль" не заполнено!';
  } elseif (params('password') !== params('password-confirm')) {
    $error = 'Пароли не совпадают!';
  } else {
    $user['hash'] = hash('sha256', params('password'));
    $crud = new CRUD('users');
    $id = $crud->insert($user);
    if ($id) {
      flash('message', 'Пользователь "'.params('firstname').' '.params('lastname').'" успешно создан!');
      redirect(config('url') . '/users/edit/' . $id);
    } else {
      $error = 'Ошибка! Не удалось создать нового пользователя.';
    }
  }

  render('users.edit', array(
    'error' => $error,
    'user' => $user,
    'user_types' => array(
      1 => 'manager',
      2 => 'oficer',
      3 => 'super user'
    )
  ));
});

on('GET', '/users/edit/:id', function($id) {

  if (session('user')['type'] < 2) {
    error(403);
  }

  $error = null;
  $user = null;

  $crud = new CRUD('users');
  $result = $crud->select("id = {$id}");

  if (!$result[0]) {
    flash('error', 'Ошибка! Пользователь с id: "'.$id.'" не найден!');
    redirect(config('url') . '/users');
  }

  $user = $result[0];

  render('users.edit', array(
    'error' => $error,
    'user' => $user,
    'user_types' => array(
      1 => 'manager',
      2 => 'oficer',
      3 => 'super user'
    )
  ));
});

on('POST', '/users/edit/:id', function($id) {

  if (session('user')['type'] < 3) {
    error(403);
  }

  $error = null;
  $message = null;

  $user = array(
    'login' => params('login'),
    'firstname' => params('firstname'),
    'lastname' => params('lastname'),
    'type' => params('type')
  );

  if (params('password')) {
    if (params('password') !== params('password-confirm')) {
      $error = 'Ошибка! Пароли не совпадают.';
    } else {
      $user['hash'] = hash('sha256', params('password'));
    }
  }
  if (!$error) {
    $crud = new CRUD('users');
    $result = $crud->update($user, "id = {$id}", 1);
    if ($result) {
      $message = 'Изиенения успешно сохранены.';
    }
  }

  render('users.edit', array(
    'error' => $error,
    'user' => $user,
    'message' => $message,
    'user_types' => array(
      1 => 'manager',
      2 => 'oficer',
      3 => 'super user'
    )
  ));
});

on('GET', '/users/delete/:id', function($id) {
  if (session('user')['type'] < 3) {
    error(403);
  }

  $crud = new CRUD('users');
  $result = $crud->delete("id = {$id}");

  if ($result) {
    flash('message', 'Пользователь успешно удален.');
  } else {
    flash('error', 'Не удалось удалить пользователя.');
  }

  redirect(config('url') . '/users');
});

dispatch();
*/

?>