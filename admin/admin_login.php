<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body class="bg-light">

<?php
   if(isset($message)){
      foreach($message as $msg){
         $icon = 'error';
         $lower = strtolower($msg);
         if(strpos($lower,'success') !== false){
            $icon = 'success';
         }
         echo "<script>
            document.addEventListener('DOMContentLoaded', function(){
               Swal.fire({
                  toast: true,
                  position: 'top-end',
                  icon: '".$icon."',
                  title: '".addslashes($msg)."',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true
               });
            });
         </script>";
      }
   }
?>

<section class="d-flex align-items-center justify-content-center min-vh-100">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0 p-4 animate__animated animate__fadeInUp">
               <h3 class="card-title text-center mb-3">Admin Login</h3>
               <p class="text-center text-muted small mb-4">Default username = <span class="fw-bold text-primary">admin</span> & password = <span class="fw-bold text-primary">111</span></p>
               <form action="" method="post">
                  <div class="mb-3">
                     <label class="form-label">Username</label>
                     <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">Password</label>
                     <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-1"></i> Login Now</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
</body>
</html>
