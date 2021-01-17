<?php
  require '../config/config.php';
  $sql = "DELETE FROM users WHERE id = ". $_GET['id'];
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  header('Location: user_list.php');
?>