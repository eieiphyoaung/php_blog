<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  if(!empty($_POST)){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (empty($_POST['role'])) {
        $role = 0;
      }else{
        $role = 1;
      }

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
      $stmt->execute(array(':email'=>$email,':id'=>$id));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        echo "<script>alert('Email duplicated')</script>";
    }else{
        $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
        $result = $stmt->execute();

          if($result){
            echo "<script>alert('Successfully Updated');window.location.href='user_list.php';</script>";
          }
    }
      
  }

  $sql = "SELECT * FROM users WHERE id = ".$_GET['id'];
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();

?>

  <?php
    include('header.php');
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
                        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $result[0]['email']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="admin">Role</label> <br>
                            <input type="checkbox" name="role" value="1" <?php if($result[0]['role'] == 1) { echo 'checked';} else { echo ''; }?>>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="SUBMIT">
                            <a href="user_list.php" class="btn btn-warning">Back</a>
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
