<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

include 'components/wishlist_cart.php';

if(isset($_POST['delete'])){
   $wishlist_id = $_POST['wishlist_id'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$wishlist_id]);
}

if(isset($_GET['delete_all'])){
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Wishlist - Shopie</title>
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
      <h3 class="heading">Your Wishlist</h3>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         $grand_total = 0;
         $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $select_wishlist->execute([$user_id]);
         if($select_wishlist->rowCount() > 0){
            while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
               $grand_total += $fetch_wishlist['price'];
      ?>
         <div class="col" data-aos="fade-up">
            <form action="" method="post">
               <div class="card product-card shadow-sm border-0 h-100 position-relative">
                  <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
                  <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_wishlist['name']; ?>">
                  <input type="hidden" name="price" value="<?= $fetch_wishlist['price']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_wishlist['image']; ?>">
                  <a href="quick_view.php?pid=<?= $fetch_wishlist['pid']; ?>" class="icon-quickview fas fa-eye"></a>
                  <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" class="card-img-top" alt="<?= $fetch_wishlist['name']; ?>">
                  <div class="card-body">
                     <h6 class="card-title fw-bold"><?= $fetch_wishlist['name']; ?></h6>
                     <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="price-text">$<?= $fetch_wishlist['price']; ?>/-</span>
                        <input type="number" name="qty" class="form-control form-control-sm qty-input" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                  </div>
                  <div class="card-footer bg-transparent border-0 pt-0 d-flex flex-column gap-2">
                     <input type="submit" value="Add to Cart" class="btn btn-primary w-100" name="add_to_cart">
                     <input type="submit" value="Delete Item" class="btn btn-danger btn-sm w-100 swal-confirm-submit" data-swal-msg="Delete this from wishlist?" name="delete">
                  </div>
               </div>
            </form>
         </div>
      <?php
            }
         }else{
            echo '<p class="empty">Your wishlist is empty</p>';
         }
      ?>
      </div>

      <div class="card wishlist-total-card shadow-sm border-0 mx-auto mt-4 p-4 text-center" data-aos="fade-up">
         <p class="total-price mb-3">Grand Total: <span>$<?= $grand_total; ?>/-</span></p>
         <a href="shop.php" class="btn btn-warning w-100 mb-2">Continue Shopping</a>
         <a href="wishlist.php?delete_all" class="btn btn-danger w-100 swal-confirm-delete <?= ($grand_total > 1)?'':'disabled'; ?>" data-swal-msg="Delete all items from wishlist?">Delete All Items</a>
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
