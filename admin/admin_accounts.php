<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts - Admin Panel</title>
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
      <h1 class="heading">Admin Accounts</h1>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

         <div class="col" data-aos="fade-up">
            <div class="card admin-account-card shadow-sm border-0 text-center h-100">
               <div class="card-body">
                  <p class="text-muted mb-2">Add New Admin</p>
                  <a href="register_admin.php" class="btn btn-warning btn-sm">Register Admin</a>
               </div>
            </div>
         </div>

         <?php
            $select_accounts = $conn->prepare("SELECT * FROM `admins`");
            $select_accounts->execute();
            if($select_accounts->rowCount() > 0){
               while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
         ?>
         <div class="col" data-aos="fade-up">
            <div class="card admin-account-card shadow-sm border-0 text-center h-100">
               <div class="card-body">
                  <p>Admin ID: <span><?= $fetch_accounts['id']; ?></span></p>
                  <p>Admin Name: <span><?= $fetch_accounts['name']; ?></span></p>
               </div>
               <div class="card-footer bg-transparent border-0 d-flex gap-2 justify-content-center pt-0">
                  <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="btn btn-danger btn-sm swal-confirm-delete" data-swal-msg="Delete this admin account?">Delete</a>
                  <?php
                     if($fetch_accounts['id'] == $admin_id){
                        echo '<a href="update_profile.php" class="btn btn-warning btn-sm">Update</a>';
                     }
                  ?>
               </div>
            </div>
         </div>
         <?php
               }
            }else{
               echo '<p class="empty">No accounts available!</p>';
            }
         ?>
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
