<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  if($_POST){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    if($_FILES['image']['name'] != null){
        $file = 'images/'.($_FILES['image']['name']);
        $fileType = pathinfo($file,PATHINFO_EXTENSION);
        if($fileType != 'png' && $fileType != 'jpg' && $fileType != 'jpeg'){
            echo "<script>alert('Image must be PNG,JPG or JPEG');</script>";
        }else{
            $image = $_FILES['image']['name'];
            $author_id = $_SESSION['user_id'];
            move_uploaded_file($_FILES['image']['tmp_name'],$file);
            $sql = "UPDATE posts SET title = '$title', content = '$content', image = '$image' WHERE id = '$id'";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            if($result){
                echo "<script>alert('Successfully updated');window.location.href='index.php';</script>";
            }
        }
    }else{
        $sql = "UPDATE posts SET title = '$title', content = '$content' WHERE id = '$id'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if($result){
            echo "<script>alert('Successfully updated');window.location.href='index.php';</script>";
        }
    }
  }


  $sql = "SELECT * FROM posts WHERE id = ".$_GET['id'];
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();

?>

  <?php
    include('header.html');
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $result[0]['id'];?>">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title'];?>"required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" class="form-control" cols="30" rows="10"><?php echo $result[0]['content'];?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label> <br>
                            <img src="images/<?php echo $result[0]['image'];?>" alt="Blog Image" width="100" height="100"> <br> <br>
                            <input type="file" class="form-control" name="image" value="">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="SUBMIT">
                            <a href="index.php" class="btn btn-warning">Back</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <?php
    include('footer.html');
  ?>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
