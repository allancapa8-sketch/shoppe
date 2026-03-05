<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}

if(isset($_GET['disable'])){
   $disable_id = $_GET['disable'];
   $disable_user = $conn->prepare("UPDATE `users` SET status = 'disabled' WHERE id = ?");
   $disable_user->execute([$disable_id]);
   $message[] = 'user account disabled successfully!';
}

if(isset($_GET['enable'])){
   $enable_id = $_GET['enable'];
   $enable_user = $conn->prepare("UPDATE `users` SET status = 'active' WHERE id = ?");
   $enable_user->execute([$enable_id]);
   $message[] = 'user account enabled successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts - Admin Panel</title>
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
      <h1 class="heading">User Accounts</h1>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         $select_accounts = $conn->prepare("SELECT * FROM `users`");
         $select_accounts->execute();
         if($select_accounts->rowCount() > 0){
            while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){
               $user_status = isset($fetch_accounts['status']) ? $fetch_accounts['status'] : 'active';
               $status_class = ($user_status == 'active') ? 'success' : 'danger';
      ?>
         <div class="col" data-aos="fade-up">
            <div class="card admin-account-card shadow-sm border-0 text-center h-100">
               <div class="card-body">
                  <div class="mb-2">
                     <span class="badge bg-<?= $status_class; ?>"><?= ucfirst($user_status); ?></span>
                  </div>
                  <p>User ID: <span><?= $fetch_accounts['id']; ?></span></p>
                  <p>Username: <span><?= $fetch_accounts['name']; ?></span></p>
                  <p>Email: <span><?= $fetch_accounts['email']; ?></span></p>
               </div>
               <div class="card-footer bg-transparent border-0 pt-0">
                  <div class="d-flex gap-2">
                     <?php if($user_status == 'active'): ?>
                        <a href="users_accounts.php?disable=<?= $fetch_accounts['id']; ?>" class="btn btn-warning btn-sm flex-fill" title="Disable this account"><i class="fas fa-ban me-1"></i> Disable</a>
                     <?php else: ?>
                        <a href="users_accounts.php?enable=<?= $fetch_accounts['id']; ?>" class="btn btn-success btn-sm flex-fill" title="Enable this account"><i class="fas fa-check me-1"></i> Enable</a>
                     <?php endif; ?>
                     <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="btn btn-danger btn-sm flex-fill swal-confirm-delete" data-swal-msg="Delete this account? All related information will also be deleted!"><i class="fas fa-trash me-1"></i> Delete</a>
                  </div>
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
