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
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ?");
   $select_seller->execute([$email]);

   if($select_seller->rowCount() > 0){
      $message[] = 'email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_seller = $conn->prepare("INSERT INTO `sellers`(name, email, password, status) VALUES(?,?,?,?)");
         $insert_seller->execute([$name, $email, $cpass, 'pending']);
         $message[] = 'registration submitted successfully! Please wait for admin approval.';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seller Registration</title>
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
         $icon = 'info';
         $lower = strtolower($msg);
         if(strpos($lower,'success') !== false || strpos($lower,'submitted') !== false){
            $icon = 'success';
         } elseif(strpos($lower,'already') !== false || strpos($lower,'not') !== false || strpos($lower,'match') !== false){
            $icon = 'error';
         }
         echo "<script>
            document.addEventListener('DOMContentLoaded', function(){
               Swal.fire({
                  toast: true,
                  position: 'top-end',
                  icon: '".$icon."',
                  title: '".addslashes($msg)."',
                  showConfirmButton: false,
                  timer: 4000,
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
               <h3 class="card-title text-center mb-3"><i class="bi bi-shop me-2"></i>Seller Registration</h3>
               <p class="text-center text-muted small mb-4">Register to start selling your products.</p>
               <form action="" method="post">
                  <div class="mb-3">
                     <label class="form-label">Full Name</label>
                     <input type="text" name="name" required placeholder="Enter your name" maxlength="20" class="form-control">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">Email</label>
                     <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="form-control">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">Password</label>
                     <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">Confirm Password</label>
                     <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary w-100 mb-3"><i class="fas fa-user-plus me-1"></i> Register Now</button>
                  <p class="text-center text-muted small">Already have an account? <a href="seller_login.php" class="text-decoration-none">Login here</a></p>
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
