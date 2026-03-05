<?php
   if(isset($message)){
      foreach($message as $msg){
         $icon = 'info';
         $lower = strtolower($msg);
         if(strpos($lower,'success') !== false || strpos($lower,'added') !== false || strpos($lower,'updated') !== false || strpos($lower,'placed') !== false || strpos($lower,'registered') !== false || strpos($lower,'sent') !== false || strpos($lower,'logged') !== false || strpos($lower,'deleted') !== false){
            $icon = 'success';
         } elseif(strpos($lower,'already') !== false || strpos($lower,'incorrect') !== false || strpos($lower,'not') !== false || strpos($lower,'empty') !== false || strpos($lower,'error') !== false || strpos($lower,'match') !== false || strpos($lower,'wrong') !== false){
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
                  timer: 3000,
                  timerProgressBar: true
               });
            });
         </script>";
      }
   }
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
   <div class="container">
      <a class="navbar-brand" href="../admin/dashboard.php">Admin<span>Panel</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="adminNavbar">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/placed_orders.php">Orders</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/admin_accounts.php">Admins</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/users_accounts.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/manage_sellers.php">Sellers</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/messages.php">Messages</a></li>
            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Reports</a>
               <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="../admin/transactions.php"><i class="fas fa-exchange-alt me-2"></i>Transactions</a></li>
                  <li><a class="dropdown-item" href="../admin/sales_reports.php"><i class="fas fa-chart-bar me-2"></i>Sales Reports</a></li>
               </ul>
            </li>
         </ul>

         <div class="d-flex align-items-center">
            <!-- Admin Dropdown -->
            <div class="dropdown">
               <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user me-1"></i>
               </button>
               <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="adminDropdown">
                  <?php
                     $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                     $select_profile->execute([$admin_id]);
                     $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                  ?>
                  <p class="text-center fw-bold mb-2"><?= $fetch_profile['name']; ?></p>
                  <a href="../admin/update_profile.php" class="btn btn-primary btn-sm w-100 mb-2">Update Profile</a>
                  <div class="d-flex gap-2 mb-2">
                     <a href="../admin/register_admin.php" class="btn btn-warning btn-sm w-100">Register</a>
                     <a href="../admin/admin_login.php" class="btn btn-warning btn-sm w-100">Login</a>
                  </div>
                  <a href="../components/admin_logout.php" class="btn btn-danger btn-sm w-100 swal-logout">Logout</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</nav>
