<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
   $update_profile_name->execute([$name, $admin_id]);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass == $empty_pass){
      $message[] = 'please enter old password!';
   }elseif($old_pass != $prev_pass){
      $message[] = 'old password not matched!';
   }elseif($new_pass != $confirm_pass){
      $message[] = 'confirm password not matched!';
   }else{
      if($new_pass != $empty_pass){
         $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$confirm_pass, $admin_id]);
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
   <title>Update Profile - Admin Panel</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="section-padding">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-6 col-lg-5" data-aos="fade-up">
            <div class="card shadow-sm border-0 p-4">
               <h3 class="card-title text-center mb-4">Update Profile</h3>
               <form action="" method="post">
                  <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
                  <div class="mb-3">
                     <label class="form-label">Username</label>
                     <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="Enter your username" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">Old Password</label>
                     <input type="password" name="old_pass" placeholder="Enter old password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">New Password</label>
                     <input type="password" name="new_pass" placeholder="Enter new password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="mb-3">
                     <label class="form-label">Confirm New Password</label>
                     <input type="password" name="confirm_pass" placeholder="Confirm new password" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary w-100"><i class="fas fa-user-edit me-1"></i> Update Now</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/admin_script.js"></script>
   
</body>
</html>
