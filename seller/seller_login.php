<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ? AND password = ?");
   $select_seller->execute([$email, $pass]);
   $row = $select_seller->fetch(PDO::FETCH_ASSOC);

   if($select_seller->rowCount() > 0){
      $status = $row['status'];
      if($status == 'pending'){
         $message[] = 'your account is still pending approval!';
      }elseif($status == 'rejected'){
         $message[] = 'your application was rejected by admin!';
      }else{
         $_SESSION['seller_id'] = $row['id'];
         header('location:dashboard.php');
      }
   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seller Login</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <link rel="stylesheet" href="../css/seller_style.css">
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
               <h3 class="card-title text-center mb-3"><i class="bi bi-shop me-2"></i>Seller Login</h3>
               <p class="text-center text-muted small mb-4">Login to manage your products and orders.</p>
               <form action="" method="post">
                  <div class="mb-3">
                     <label class="form-label">Email</label>
                     <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="form-control">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">Password</label>
                     <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary w-100 mb-3"><i class="fas fa-sign-in-alt me-1"></i> Login Now</button>
                  <p class="text-center text-muted small">Don't have an account? <a href="seller_register.php" class="text-decoration-none">Register here</a></p>
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
