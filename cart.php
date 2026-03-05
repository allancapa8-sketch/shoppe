<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart - Shopie</title>
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
      <h3 class="heading">Shopping Cart</h3>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
         <div class="col" data-aos="fade-up">
            <form action="" method="post">
               <div class="card cart-item-card shadow-sm border-0 h-100">
                  <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                  <div class="card-body">
                     <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="<?= $fetch_cart['name']; ?>" class="rounded">
                        <div>
                           <h6 class="fw-bold mb-1"><?= $fetch_cart['name']; ?></h6>
                           <span class="price-text">$<?= $fetch_cart['price']; ?>/-</span>
                        </div>
                        <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="ms-auto text-secondary"><i class="fas fa-eye"></i></a>
                     </div>
                     <div class="d-flex align-items-center gap-2 mb-2">
                        <label class="form-label mb-0 small">Qty:</label>
                        <input type="number" name="qty" class="form-control form-control-sm qty-input" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
                        <button type="submit" class="btn btn-warning btn-sm" name="update_qty"><i class="fas fa-edit"></i></button>
                     </div>
                     <p class="sub-total-text mb-0">Sub total: <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span></p>
                  </div>
                  <div class="card-footer bg-transparent border-0 pt-0">
                     <input type="submit" value="Delete Item" class="btn btn-danger btn-sm w-100 swal-confirm-submit" data-swal-msg="Delete this item from cart?" name="delete">
                  </div>
               </div>
            </form>
         </div>
      <?php
         $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">Your cart is empty</p>';
         }
      ?>
      </div>

      <div class="card cart-total-card shadow-sm border-0 mx-auto mt-4 p-4 text-center" data-aos="fade-up">
         <p class="total-price mb-3">Grand Total: <span>$<?= $grand_total; ?>/-</span></p>
         <a href="shop.php" class="btn btn-warning w-100 mb-2">Continue Shopping</a>
         <a href="cart.php?delete_all" class="btn btn-danger w-100 mb-2 swal-confirm-delete <?= ($grand_total > 1)?'':'disabled'; ?>" data-swal-msg="Delete all items from cart?">Delete All Items</a>
         <a href="checkout.php" class="btn btn-primary w-100 <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout</a>
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
