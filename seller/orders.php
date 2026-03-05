<?php

include '../components/connect.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:seller_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Orders - Seller Panel</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
   <link rel="stylesheet" href="../css/seller_style.css">
</head>
<body>

<?php include '../components/seller_header.php'; ?>

<section class="section-padding">
   <div class="container">
      <h1 class="heading">Orders Containing My Products</h1>

      <?php
         // Get seller's product names
         $seller_products = $conn->prepare("SELECT name FROM `products` WHERE seller_id = ?");
         $seller_products->execute([$seller_id]);
         $product_names = [];
         while($sp = $seller_products->fetch(PDO::FETCH_ASSOC)){
            $product_names[] = $sp['name'];
         }
      ?>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         $found_orders = false;
         if(!empty($product_names)){
            $all_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY placed_on DESC");
            $all_orders->execute();
            while($fetch_orders = $all_orders->fetch(PDO::FETCH_ASSOC)){
               $contains_product = false;
               foreach($product_names as $pname){
                  if(stripos($fetch_orders['total_products'], $pname) !== false){
                     $contains_product = true;
                     break;
                  }
               }
               if($contains_product){
                  $found_orders = true;
                  $status_class = 'warning';
                  if($fetch_orders['payment_status'] == 'completed') $status_class = 'success';
      ?>
         <div class="col" data-aos="fade-up">
            <div class="card seller-order-card shadow-sm border-0 h-100">
               <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                     <small class="text-muted"><?= $fetch_orders['placed_on']; ?></small>
                     <span class="badge bg-<?= $status_class; ?>"><?= $fetch_orders['payment_status']; ?></span>
                  </div>
                  <p>Customer: <span><?= $fetch_orders['name']; ?></span></p>
                  <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                  <p>Products: <span><?= $fetch_orders['total_products']; ?></span></p>
                  <p>Total Price: <span>$<?= $fetch_orders['total_price']; ?>/-</span></p>
                  <p>Payment: <span><?= $fetch_orders['method']; ?></span></p>
                  <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
               </div>
            </div>
         </div>
      <?php
               }
            }
         }
         if(!$found_orders){
            echo '<p class="empty">No orders found for your products yet!</p>';
         }
      ?>
      </div>
   </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/seller_script.js"></script>

</body>
</html>
