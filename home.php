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
   <title>Home - Shopie</title>

   <!-- Google Fonts -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <!-- Bootstrap 5 CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <!-- Bootstrap Icons -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Animate.css -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <!-- AOS -->
   <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
   <!-- Swiper -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- Hero Slider -->
<div class="home-bg">
   <div class="container py-5">
      <div class="swiper home-slider">
         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <div class="row align-items-center">
                  <div class="col-md-6 text-center mb-3 mb-md-0">
                     <img src="images/home-img-1.png" alt="Smartphones" class="img-fluid animate__animated animate__fadeInLeft">
                  </div>
                  <div class="col-md-6 text-center text-md-start">
                     <div class="content animate__animated animate__fadeInRight">
                        <span>Upto 50% Off</span>
                        <h3>Latest Smartphones</h3>
                        <a href="shop.php" class="btn btn-primary btn-lg mt-2">Shop Now</a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="row align-items-center">
                  <div class="col-md-6 text-center mb-3 mb-md-0">
                     <img src="images/home-img-2.png" alt="Watches" class="img-fluid animate__animated animate__fadeInLeft">
                  </div>
                  <div class="col-md-6 text-center text-md-start">
                     <div class="content animate__animated animate__fadeInRight">
                        <span>Upto 50% Off</span>
                        <h3>Latest Watches</h3>
                        <a href="shop.php" class="btn btn-primary btn-lg mt-2">Shop Now</a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="row align-items-center">
                  <div class="col-md-6 text-center mb-3 mb-md-0">
                     <img src="images/home-img-3.png" alt="Headsets" class="img-fluid animate__animated animate__fadeInLeft">
                  </div>
                  <div class="col-md-6 text-center text-md-start">
                     <div class="content animate__animated animate__fadeInRight">
                        <span>Upto 50% Off</span>
                        <h3>Latest Headsets</h3>
                        <a href="shop.php" class="btn btn-primary btn-lg mt-2">Shop Now</a>
                     </div>
                  </div>
               </div>
            </div>

         </div>
         <div class="swiper-pagination"></div>
      </div>
   </div>
</div>

<!-- Category Slider -->
<section class="section-padding">
   <div class="container">
      <h1 class="heading">Shop by Category</h1>
      <div class="swiper category-slider">
         <div class="swiper-wrapper">
            <a href="category.php?category=laptop" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in">
                  <img src="images/icon-1.png" alt="Laptop" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">Laptop</h6>
               </div>
            </a>
            <a href="category.php?category=tv" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in" data-aos-delay="50">
                  <img src="images/icon-2.png" alt="TV" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">TV</h6>
               </div>
            </a>
            <a href="category.php?category=camera" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in" data-aos-delay="100">
                  <img src="images/icon-3.png" alt="Camera" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">Camera</h6>
               </div>
            </a>
            <a href="category.php?category=mouse" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in" data-aos-delay="150">
                  <img src="images/icon-4.png" alt="Mouse" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">Mouse</h6>
               </div>
            </a>
            <a href="category.php?category=fridge" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in" data-aos-delay="200">
                  <img src="images/icon-5.png" alt="Fridge" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">Fridge</h6>
               </div>
            </a>
            <a href="category.php?category=washing" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in" data-aos-delay="250">
                  <img src="images/icon-6.png" alt="Washing Machine" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">Washing Machine</h6>
               </div>
            </a>
            <a href="category.php?category=smartphone" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in" data-aos-delay="300">
                  <img src="images/icon-7.png" alt="Smartphone" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">Smartphone</h6>
               </div>
            </a>
            <a href="category.php?category=watch" class="swiper-slide">
               <div class="card category-card text-center border-0 shadow-sm p-3" data-aos="zoom-in" data-aos-delay="350">
                  <img src="images/icon-8.png" alt="Watch" class="mx-auto mb-2">
                  <h6 class="mb-0 fw-bold">Watch</h6>
               </div>
            </a>
         </div>
         <div class="swiper-pagination"></div>
      </div>
   </div>
</section>

<!-- Latest Products -->
<section class="section-padding">
   <div class="container">
      <h1 class="heading">Latest Products</h1>
      <div class="swiper products-slider">
         <div class="swiper-wrapper">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
            $select_products->execute();
            if($select_products->rowCount() > 0){
               while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
            <form action="" method="post" class="swiper-slide">
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
         <?php
               }
            }else{
               echo '<p class="empty">No products added yet!</p>';
            }
         ?>
         </div>
         <div class="swiper-pagination"></div>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom JS -->
<script src="js/script.js"></script>

<script>
var swiper = new Swiper(".home-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: { el: ".swiper-pagination", clickable: true },
});

var swiper = new Swiper(".category-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: { el: ".swiper-pagination", clickable: true },
   breakpoints: {
      0: { slidesPerView: 2 },
      650: { slidesPerView: 3 },
      768: { slidesPerView: 4 },
      1024: { slidesPerView: 5 },
   },
});

var swiper = new Swiper(".products-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: { el: ".swiper-pagination", clickable: true },
   breakpoints: {
      550: { slidesPerView: 2 },
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
   },
});
</script>

</body>
</html>
