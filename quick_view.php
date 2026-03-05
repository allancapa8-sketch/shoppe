<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick View - Shopie</title>
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

<section class="quick-view section-padding">
   <div class="container">
      <h1 class="heading">Quick View</h1>

      <?php
         $pid = $_GET['pid'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
         $select_products->execute([$pid]);
         if($select_products->rowCount() > 0){
            while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post">
         <div class="card shadow-sm border-0" data-aos="fade-up">
            <div class="card-body">
               <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
               <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
               <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
               <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
               <div class="row g-4">
                  <div class="col-md-6">
                     <div class="main-image text-center mb-3">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="<?= $fetch_product['name']; ?>" class="img-fluid" style="max-height:300px; object-fit:contain;">
                     </div>
                     <div class="sub-image d-flex gap-2 justify-content-center">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
                     </div>
                  </div>
                  <div class="col-md-6 d-flex flex-column justify-content-center">
                     <h4 class="fw-bold mb-3"><?= $fetch_product['name']; ?></h4>
                     <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="price-text fs-5">$<?= $fetch_product['price']; ?>/-</span>
                        <input type="number" name="qty" class="form-control qty-input" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <p class="text-muted mb-3" style="line-height:1.8;"><?= $fetch_product['details']; ?></p>
                     <div class="d-flex gap-2">
                        <input type="submit" value="Add to Cart" class="btn btn-primary flex-fill" name="add_to_cart">
                        <input type="submit" value="Add to Wishlist" class="btn btn-warning flex-fill" name="add_to_wishlist">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>
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
