<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!';
   }else{
      $message[] = 'your cart is empty';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout - Shopie</title>
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
      <form action="" method="POST">

         <!-- Order Summary -->
         <div class="card checkout-card shadow-sm border-0 mb-4" data-aos="fade-up">
            <div class="card-header bg-dark text-white text-center">
               <h5 class="mb-0 text-uppercase fw-bold">Your Orders</h5>
            </div>
            <div class="card-body display-orders text-center">
               <?php
                  $grand_total = 0;
                  $cart_items[] = '';
                  $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                  $select_cart->execute([$user_id]);
                  if($select_cart->rowCount() > 0){
                     while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                        $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                        $total_products = implode($cart_items);
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
               ?>
                  <span class="order-info-badge"><?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span></span>
               <?php
                     }
                  }else{
                     echo '<p class="empty">Your cart is empty!</p>';
                  }
               ?>
               <input type="hidden" name="total_products" value="<?= $total_products; ?>">
               <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
               <div class="grand-total mt-3">Grand Total: <span>$<?= $grand_total; ?>/-</span></div>
            </div>
         </div>

         <!-- Checkout Form -->
         <div class="card checkout-card shadow-sm border-0" data-aos="fade-up">
            <div class="card-header bg-dark text-white text-center">
               <h5 class="mb-0 text-uppercase fw-bold">Place Your Orders</h5>
            </div>
            <div class="card-body">
               <div class="row g-3">
                  <div class="col-md-6">
                     <label class="form-label">Your Name:</label>
                     <input type="text" name="name" placeholder="Enter your name" class="form-control" maxlength="20" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Your Number:</label>
                     <input type="number" name="number" placeholder="Enter your number" class="form-control" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Your Email:</label>
                     <input type="email" name="email" placeholder="Enter your email" class="form-control" maxlength="50" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Payment Method:</label>
                     <select name="method" class="form-select" required>
                        <option value="cash on delivery">Cash on Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="paytm">Paytm</option>
                        <option value="paypal">PayPal</option>
                     </select>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Address Line 01:</label>
                     <input type="text" name="flat" placeholder="e.g. Flat number" class="form-control" maxlength="50" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Address Line 02:</label>
                     <input type="text" name="street" placeholder="e.g. Street name" class="form-control" maxlength="50" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">City:</label>
                     <input type="text" name="city" placeholder="e.g. Mumbai" class="form-control" maxlength="50" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">State:</label>
                     <input type="text" name="state" placeholder="e.g. Maharashtra" class="form-control" maxlength="50" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Country:</label>
                     <input type="text" name="country" placeholder="e.g. India" class="form-control" maxlength="50" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Pin Code:</label>
                     <input type="number" name="pin_code" placeholder="e.g. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="form-control" required>
                  </div>
                  <div class="col-12">
                     <input type="submit" name="order" class="btn btn-primary btn-lg w-100 <?= ($grand_total > 1)?'':'disabled'; ?>" value="Place Order">
                  </div>
               </div>
            </div>
         </div>

      </form>
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
