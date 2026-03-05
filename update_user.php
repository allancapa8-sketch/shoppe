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

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass == $empty_pass){
      $message[] = 'please enter old password!';
   }elseif($old_pass != $prev_pass){
      $message[] = 'old password not matched!';
   }elseif($new_pass != $cpass){
      $message[] = 'confirm password not matched!';
   }else{
      if($new_pass != $empty_pass){
         $update_admin_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$cpass, $user_id]);
         $message[] = 'password updated successfully!';
      }else{
         $message[] = 'please enter a new password!';
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
   <title>Update Profile - Shopie</title>
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
         <h3 class="fw-bold text-uppercase mb-4">Update Profile</h3>
         <form action="" method="post">
            <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
            <div class="mb-3">
               <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="form-control" value="<?= $fetch_profile["name"]; ?>">
            </div>
            <div class="mb-3">
               <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">
            </div>
            <div class="mb-3">
               <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="mb-3">
               <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="mb-3">
               <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <input type="submit" value="Update Now" class="btn btn-primary w-100" name="submit">
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
