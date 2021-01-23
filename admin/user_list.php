<?php
  session_start();
  require '../config/config.php';
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  if($_SESSION['role'] != 1){
      header('Location: login.php');
  }

  if (!empty($_POST['search'])) {
    setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
  }else{
    if (empty($_GET['pageno'])) {
      unset($_COOKIE['search']); 
      setcookie('search', null, -1, '/'); 
    }
  }

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
              <div class="card-header">
                <h3 class="card-title">User Listings</h3>
              </div>
              <?php
                if(!empty($_GET['pageno'])){
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
                $numOfrecs  = 2;
                $offset = ($pageno - 1) * $numOfrecs;

                if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                  $stmt->execute();
                  $rawResults = $stmt->fetchAll();
    
                  $total_pages = ceil(count($rawResults) / $numOfrecs);
    
                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $stmt->execute();
                  $results = $stmt->fetchAll();
                }else{
                  $search_key = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];

                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search_key%' ORDER BY id DESC");
                  $stmt->execute();
                  $rawResults = $stmt->fetchAll();
    
                  $total_pages = ceil(count($rawResults) / $numOfrecs);
    
                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search_key%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $stmt->execute();
                  $results = $stmt->fetchAll();
                }

                
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="user_add.php" type="button" class="btn btn-success">Create New User</a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $count = 1;
                      if($results){
                        foreach($results as $result){
                    ?>
                    <tr>
                      <td><?php echo $count++; ?></td>
                      <td><?php echo escape($result['name']); ?></td>
                      <td><?php echo escape($result['email']); ?></td>
                      <td><?php if($result['role'] ==1) {echo 'admin';} else { echo 'user';}?></td>
                      <td>
                          <div class="btn-group">
                              <div class="container">
                                  <a href="user_edit.php?id=<?php echo $result['id']; ?>" type="button" class="btn btn-warning">Edit</a>
                              </div>
                              <div class="container">
                                  <a href="user_delete.php?id=<?php echo $result['id']; ?>"
                                  onclick="return confirm('Are you sure to delete?');" type="button" class="btn btn-danger">
                                  Delete
                                </a>
                              </div>
                          </div>
                      </td>
                    </tr>
                    <?php 
                          }
                        }
                    ?>
                  </tbody>
                </table> <br>

                <nav aria-label="Page navigation example" style="float:right!important;">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">Previous</a></li>
                    <li class="page-item <?php if($pageno <=1) echo 'disabled';?>">
                      <a class="page-link" href="<?php if($pageno <=1) {echo '#';} else { echo '?pageno='.($pageno-1);}?>">Previous</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="?pageno=<?php echo $pageno; ?>"><?php echo $pageno;?></a>
                    </li>
                    <li class="page-item <?php if($pageno >= $total_pages) echo 'disabled';?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages) {echo '#';} else { echo '?pageno='.($pageno+1);}?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Next</a></li>
                  </ul>
                </nav>
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
