<?php
   if(isset($message)){
      foreach($message as $msg){
         $icon = 'info';
         $lower = strtolower($msg);
         if(strpos($lower,'success') !== false || strpos($lower,'added') !== false || strpos($lower,'updated') !== false || strpos($lower,'registered') !== false || strpos($lower,'submitted') !== false || strpos($lower,'deleted') !== false){
            $icon = 'success';
         } elseif(strpos($lower,'already') !== false || strpos($lower,'incorrect') !== false || strpos($lower,'not') !== false || strpos($lower,'empty') !== false || strpos($lower,'error') !== false || strpos($lower,'match') !== false || strpos($lower,'wrong') !== false || strpos($lower,'pending') !== false || strpos($lower,'rejected') !== false || strpos($lower,'disabled') !== false){
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
      <a class="navbar-brand" href="../seller/dashboard.php">Seller<span>Panel</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sellerNavbar" aria-controls="sellerNavbar" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="sellerNavbar">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="../seller/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../seller/products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../seller/orders.php">Orders</a></li>
         </ul>

         <div class="d-flex align-items-center">
            <div class="dropdown">
               <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="sellerDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user me-1"></i>
               </button>
               <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="sellerDropdown">
                  <?php
                     $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
                     $select_seller->execute([$seller_id]);
                     $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);
                  ?>
                  <p class="text-center fw-bold mb-1"><?= $fetch_seller['name']; ?></p>
                  <p class="text-center text-muted small mb-2"><?= $fetch_seller['email']; ?></p>
                  <span class="badge bg-success d-block mb-3">Approved Seller</span>
                  <a href="../components/seller_logout.php" class="btn btn-danger btn-sm w-100 swal-logout">Logout</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</nav>
