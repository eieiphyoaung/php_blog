<?php
  require '../config/config.php';
  $sql = "DELETE FROM posts WHERE id = ". $_GET['id'];
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  header('Location: index.php');
?>