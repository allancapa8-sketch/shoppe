<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders - Admin Panel</title>
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
      <h1 class="heading">Placed Orders</h1>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
         <div class="col" data-aos="fade-up">
            <div class="card admin-order-card shadow-sm border-0 h-100">
               <div class="card-body">
                  <p>Placed on: <span><?= $fetch_orders['placed_on']; ?></span></p>
                  <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
                  <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
                  <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
                  <p>Total Products: <span><?= $fetch_orders['total_products']; ?></span></p>
                  <p>Total Price: <span>$<?= $fetch_orders['total_price']; ?>/-</span></p>
                  <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                  <form action="" method="post" class="mt-2">
                     <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                     <select name="payment_status" class="form-select form-select-sm mb-2">
                        <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                        <option value="pending">pending</option>
                        <option value="completed">completed</option>
                     </select>
                     <div class="d-flex gap-2">
                        <input type="submit" value="Update" class="btn btn-warning btn-sm flex-fill" name="update_payment">
                        <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="btn btn-danger btn-sm flex-fill swal-confirm-delete" data-swal-msg="Delete this order?">Delete</a>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      <?php
            }
         }else{
            echo '<p class="empty">No orders placed yet!</p>';
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
