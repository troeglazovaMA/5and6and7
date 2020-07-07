<!DOCTYPE html>
<html lang="ru">
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
    <style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
      .error {
      border: 2px solid red;
      }
      .mycolor{
	background-color: rgba(255,255,255,0.5);
	
}
      body{
background:url(Ah1ZVHO6690.jpg) no-repeat center center fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
background-attachment: fixed;

}
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 </head>  
<body><?php



// ���������� �������� ���������� ���������,
// ���� login.php ������ ���� � ��������� UTF-8 ��� BOM.
header('Content-Type: text/html; charset=UTF-8');


session_start();

// � ��������������� ������� $_SESSION �������� ���������� ������.
// ����� ��������� ���� ����� ����� �������� �����������.
if (!empty($_SESSION['login'])) {
    // ���� ���� ����� � ������, �� ������������ ��� �����������.
    // TODO: ������� ����� (��������� ������ ������� session_destroy()
    //��� ������� �� ������ �����).
    // ������ ��������������� �� �����.
    header('Location: ./');
}

// � ��������������� ������� $_SERVER PHP ��������� �������� ��������� ������� HTTP
// � ������ �������� � �������� � �������, �������� ����� �������� ������� $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    if(isset($_COOKIE['autor_error'])){
        print('error. try again later');
        $login_value= isset($_COOKIE['login_value']) ? strip_tags($_COOKIE['login_value']) : '';
    }
    setcookie('autor_error', '', 100000);
    setcookie('login_value', '', 100000);
    ?>
    <div class='container-fluid '>
    <div class='row text-center'>
    <div class='col-10 py-5 mx-auto mycolor'>
<form class='mx-auto mycolor' action="" method="post">Логин:<br>
  <input class ='mt-2' name="login" value="<?php !empty($login_value) ? print $login_value : '' ?>" />
  <br>Pass:<br> <input name="pass" /><br>
  <input class="my-3" type="submit" value="Войти" />
</form>
</div>
</div>
</div>
<?php
}
// �����, ���� ������ ��� ������� POST, �.�. ����� ������� ����������� � ������� ������ � ������.
else {
    
    $user = 'u20397';
    $pass = '5245721';
    $db = new PDO('mysql:host=localhost;dbname=u20397', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
 
  try {
    $stmt = $db->prepare("SELECT id FROM utable WHERE login = ? AND password = md5(?)");
    $stmt->execute(array($_POST['login'], $_POST['pass']));
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  } 
 
  $user_id = $stmt->fetchAll();
  if (!empty($user_id[0]['id'])) {
    setcookie('autor_error', '', 100000);
    setcookie('login_value', '', 100000);
    // ���� ��� ��, �� ���������� ������������.
    $_SESSION['login'] = $_POST['login'];
    // ���������� ID ������������.
    $_SESSION['uid'] = $user_id[0]['id'];
    // ������ ���������������.
    
    $token=substr(md5(uniqid()),0,8).$_SERVER['REMOTE_ADDR'].$_SESSION['login'];
    $_SESSION['token'] = $token;
    header('Location: ./');
  }
  else {
    setcookie('autor_error', '1', time() + 24 * 60 * 60);
    setcookie('login_value',$_POST['login'], time() + 24 * 60 * 60);
    // ������ ���������������.
    header('Location: ./login.php');
  }
  
} ?>
</body>
</html>