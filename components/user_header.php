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
      <a class="navbar-brand" href="home.php">Shopie<span>.</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavbar">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
            <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
            <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
         </ul>

         <div class="d-flex align-items-center gap-3">
            <?php
               $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
               $count_wishlist_items->execute([$user_id]);
               $total_wishlist_counts = $count_wishlist_items->rowCount();

               $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $count_cart_items->execute([$user_id]);
               $total_cart_counts = $count_cart_items->rowCount();
            ?>
            <a href="search_page.php" class="icon-link" title="Search"><i class="fas fa-search"></i></a>
            <a href="wishlist.php" class="icon-link" title="Wishlist">
               <i class="fas fa-heart"></i>
               <span class="badge rounded-pill bg-primary"><?= $total_wishlist_counts; ?></span>
            </a>
            <a href="cart.php" class="icon-link" title="Cart">
               <i class="fas fa-shopping-cart"></i>
               <span class="badge rounded-pill bg-primary"><?= $total_cart_counts; ?></span>
            </a>

            <!-- User Dropdown -->
            <div class="dropdown">
               <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user me-1"></i>
               </button>
               <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="userDropdown">
                  <?php          
                     $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                     $select_profile->execute([$user_id]);
                     if($select_profile->rowCount() > 0){
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                  ?>
                  <p class="text-center fw-bold mb-2"><?= $fetch_profile["name"]; ?></p>
                  <a href="update_user.php" class="btn btn-primary btn-sm w-100 mb-2">Update Profile</a>
                  <div class="d-flex gap-2 mb-2">
                     <a href="user_register.php" class="btn btn-warning btn-sm w-100">Register</a>
                     <a href="user_login.php" class="btn btn-warning btn-sm w-100">Login</a>
                  </div>
                  <a href="components/user_logout.php" class="btn btn-danger btn-sm w-100 swal-logout">Logout</a>
                  <?php } else { ?>
                  <p class="text-center mb-2">Please login or register first!</p>
                  <div class="d-flex gap-2">
                     <a href="user_register.php" class="btn btn-warning btn-sm w-100">Register</a>
                     <a href="user_login.php" class="btn btn-warning btn-sm w-100">Login</a>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</nav>
