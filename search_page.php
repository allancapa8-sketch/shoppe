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
   <title>Search - Shopie</title>
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

<section class="section-padding search-form">
   <div class="container">
      <form action="" method="post" class="mx-auto" style="max-width:600px;">
         <div class="input-group">
            <input type="text" name="search_box" placeholder="Search here..." maxlength="100" class="form-control" required>
            <button type="submit" class="btn btn-primary" name="search_btn"><i class="fas fa-search"></i></button>
         </div>
      </form>
   </div>
</section>

<section class="section-padding" style="padding-top:0; min-height:60vh;">
   <div class="container">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         $search_box = $_POST['search_box'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'"); 
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
         <div class="col" data-aos="fade-up">
            <form action="" method="post">
               <div class="card product-card shadow-sm border-0 h-100 position-relative">
                  <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                  <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                  <button class="icon-wishlist fas fa-heart" type="submit" name="add_to_wishlist"></button>
                  <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="icon-quickview fas fa-eye"></a>
                  <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" class="card-img-top" alt="<?= $fetch_product['name']; ?>">
                  <div class="card-body">
                     <h6 class="card-title fw-bold"><?= $fetch_product['name']; ?></h6>
                     <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="price-text">$<?= $fetch_product['price']; ?>/-</span>
                        <input type="number" name="qty" class="form-control form-control-sm qty-input" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                  </div>
                  <div class="card-footer bg-transparent border-0 pt-0">
                     <input type="submit" value="Add to Cart" class="btn btn-primary w-100" name="add_to_cart">
                  </div>
               </div>
            </form>
         </div>
      <?php
               }
            }else{
               echo '<p class="empty">No products found!</p>';
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
