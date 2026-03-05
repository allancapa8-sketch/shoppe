<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders - Shopie</title>
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

<section class="section-padding">
   <div class="container">
      <h1 class="heading">Placed Orders</h1>

      <div class="row row-cols-1 row-cols-md-2 g-4">
      <?php
         if($user_id == ''){
            echo '<p class="empty">Please login to see your orders</p>';
         }else{
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
            $select_orders->execute([$user_id]);
            if($select_orders->rowCount() > 0){
               while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
         <div class="col" data-aos="fade-up">
            <div class="card order-card shadow-sm border-0 h-100">
               <div class="card-body">
                  <p>Placed on: <span><?= $fetch_orders['placed_on']; ?></span></p>
                  <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
                  <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                  <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
                  <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
                  <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                  <p>Your Orders: <span><?= $fetch_orders['total_products']; ?></span></p>
                  <p>Total Price: <span>$<?= $fetch_orders['total_price']; ?>/-</span></p>
                  <p>Payment Status: <span class="badge <?php echo ($fetch_orders['payment_status'] == 'pending') ? 'bg-danger' : 'bg-success'; ?>"><?= $fetch_orders['payment_status']; ?></span></p>
               </div>
            </div>
         </div>
      <?php
               }
            }else{
               echo '<p class="empty">No orders placed yet!</p>';
            }
         }
      ?>
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
