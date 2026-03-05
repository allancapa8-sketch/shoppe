<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email,]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
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
   <title>Register - Shopie</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="auth-section">
   <div class="card auth-card shadow-sm border-0" data-aos="fade-up">
      <div class="card-body p-4 text-center">
         <h3 class="fw-bold text-uppercase mb-4">Register Now</h3>
         <form action="" method="post">
            <div class="mb-3">
               <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="form-control">
            </div>
            <div class="mb-3">
               <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="mb-3">
               <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="mb-3">
               <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <input type="submit" value="Register Now" class="btn btn-primary w-100 mb-3" name="submit">
            <p class="text-muted">Already have an account?</p>
            <a href="user_login.php" class="btn btn-warning w-100">Login Now</a>
         </form>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/script.js"></script>

</body>
</html>
