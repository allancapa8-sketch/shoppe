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
   <title>About - Shopie</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about-section section-padding">
   <div class="container">
      <div class="row align-items-center g-4">
         <div class="col-lg-6" data-aos="fade-right">
            <img src="images/about-img.svg" alt="About Us" class="img-fluid">
         </div>
         <div class="col-lg-6" data-aos="fade-left">
            <h3 class="fw-bold fs-2 mb-3">Why Choose Us?</h3>
            <p class="text-muted" style="line-height:1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam veritatis minus et similique doloribus? Harum molestias tenetur eaque illum quas? Obcaecati nulla in itaque modi magnam ipsa molestiae ullam consequuntur.</p>
            <a href="contact.php" class="btn btn-primary mt-2">Contact Us</a>
         </div>
      </div>
   </div>
</section>

<section class="section-padding">
   <div class="container">
      <h1 class="heading">Client's Reviews</h1>

      <div class="swiper reviews-slider">
         <div class="swiper-wrapper">
            <div class="swiper-slide">
               <div class="card review-card shadow-sm border-0 text-center p-4 h-100">
                  <img src="images/pic-1.png" alt="John Deo" class="mx-auto mb-2">
                  <p class="text-muted" style="line-height:1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
                  <div class="stars mb-2 bg-light d-inline-block mx-auto px-3 py-1 rounded">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold">John Deo</h6>
               </div>
            </div>
            <div class="swiper-slide">
               <div class="card review-card shadow-sm border-0 text-center p-4 h-100">
                  <img src="images/pic-2.png" alt="John Deo" class="mx-auto mb-2">
                  <p class="text-muted" style="line-height:1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
                  <div class="stars mb-2 bg-light d-inline-block mx-auto px-3 py-1 rounded">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold">John Deo</h6>
               </div>
            </div>
            <div class="swiper-slide">
               <div class="card review-card shadow-sm border-0 text-center p-4 h-100">
                  <img src="images/pic-3.png" alt="John Deo" class="mx-auto mb-2">
                  <p class="text-muted" style="line-height:1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
                  <div class="stars mb-2 bg-light d-inline-block mx-auto px-3 py-1 rounded">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold">John Deo</h6>
               </div>
            </div>
            <div class="swiper-slide">
               <div class="card review-card shadow-sm border-0 text-center p-4 h-100">
                  <img src="images/pic-4.png" alt="John Deo" class="mx-auto mb-2">
                  <p class="text-muted" style="line-height:1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
                  <div class="stars mb-2 bg-light d-inline-block mx-auto px-3 py-1 rounded">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold">John Deo</h6>
               </div>
            </div>
            <div class="swiper-slide">
               <div class="card review-card shadow-sm border-0 text-center p-4 h-100">
                  <img src="images/pic-5.png" alt="John Deo" class="mx-auto mb-2">
                  <p class="text-muted" style="line-height:1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
                  <div class="stars mb-2 bg-light d-inline-block mx-auto px-3 py-1 rounded">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold">John Deo</h6>
               </div>
            </div>
            <div class="swiper-slide">
               <div class="card review-card shadow-sm border-0 text-center p-4 h-100">
                  <img src="images/pic-6.png" alt="John Deo" class="mx-auto mb-2">
                  <p class="text-muted" style="line-height:1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
                  <div class="stars mb-2 bg-light d-inline-block mx-auto px-3 py-1 rounded">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold">John Deo</h6>
               </div>
            </div>
         </div>
         <div class="swiper-pagination"></div>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/script.js"></script>

<script>
var swiper = new Swiper(".reviews-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: { el: ".swiper-pagination", clickable: true },
   breakpoints: {
      0: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
      991: { slidesPerView: 3 },
   },
});
</script>

</body>
</html>
