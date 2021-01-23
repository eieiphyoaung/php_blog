<?php
  session_start();

  require 'config/config.php';
  require 'config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
  $sql = "SELECT * FROM posts WHERE id = ".$_GET['id'];
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();

  if($_POST){
    if(empty($_POST['comment'])){
        $commentError = 'Comment cannot be null';
    }else{
      $comment = $_POST['comment'];
      $author_id = $_SESSION['user_id'];
      $blog_id = $_GET['id'];
  
      $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
      $result = $stmt->execute(
          array(':content' => $comment, ':author_id' => $author_id,':post_id'=> $blog_id)
      );
      if($result){
        header("Location: blog_details.php?id=".$blog_id);
      }  
    }
  }

  $cmtStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ".$_GET['id']);
  $cmtStmt->execute();
  $cmtResult = $cmtStmt->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog Site | Blog Details</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Blog Details</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                  <h4 class="text-center"><?php echo escape($result[0]['title']);?></h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image'];?>" alt="Photo">

                <p><?php echo escape($result[0]['content']);?></p>
                <h4>Comments</h4> <hr>
                <a href="/php-blog" type="button" class="btn btn-default">Go Back</a>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
                  <?php
                    if($cmtResult){
                      foreach($cmtResult as $cmRes){
                        $authorId = $cmRes['author_id'];

                        $autStmt = $pdo->prepare("SELECT * FROM users WHERE id = ".$authorId);
                        $autStmt->execute();
                        $auResult = $autStmt->fetchAll();                      
                  ?>
                <div class="card-comment">
                  <div class="comment-text" style="margin-left:0px !important;">
                    <span class="username">
                      <?php echo escape($auResult[0]['name']);?>
                      <span class="text-muted float-right"><?php echo escape($cmRes['created_at']);?></span>
                    </span><!-- /.username -->
                    <?php echo $cmRes['content'];?>
                  </div>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->
                <?php 
                      }
                    }
                ?>
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="img-push"><p style="color:red;"><?php echo empty($commentError) ? '' : '* '.$commentError; ?> </p>
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left:0 !important;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-default">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="#">A Programmer</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
