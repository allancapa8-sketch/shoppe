<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

      if($insert_products){
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'new product added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products - Admin Panel</title>
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

<!-- Add Product Form -->
<section class="section-padding">
   <div class="container">
      <h1 class="heading">Add Product</h1>
      <div class="card add-product-form shadow-sm border-0 mx-auto p-4" data-aos="fade-up">
         <form action="" method="post" enctype="multipart/form-data">
            <div class="row g-3">
               <div class="col-md-6">
                  <label class="form-label text-muted">Product Name (required)</label>
                  <input type="text" class="form-control" required maxlength="100" placeholder="Enter product name" name="name">
               </div>
               <div class="col-md-6">
                  <label class="form-label text-muted">Product Price (required)</label>
                  <input type="number" min="0" class="form-control" required max="9999999999" placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
               </div>
               <div class="col-md-4">
                  <label class="form-label text-muted">Image 01 (required)</label>
                  <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
               </div>
               <div class="col-md-4">
                  <label class="form-label text-muted">Image 02 (required)</label>
                  <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
               </div>
               <div class="col-md-4">
                  <label class="form-label text-muted">Image 03 (required)</label>
                  <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
               </div>
               <div class="col-12">
                  <label class="form-label text-muted">Product Details (required)</label>
                  <textarea name="details" placeholder="Enter product details" class="form-control" required maxlength="500" rows="3" style="resize:none;"></textarea>
               </div>
               <div class="col-12">
                  <input type="submit" value="Add Product" class="btn btn-primary w-100" name="add_product">
               </div>
            </div>
         </form>
      </div>
   </div>
</section>

<!-- Products List -->
<section class="section-padding" style="padding-top:0;">
   <div class="container">
      <h1 class="heading">Products Added</h1>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
      ?>
         <div class="col" data-aos="fade-up">
            <div class="card admin-product-card shadow-sm border-0 h-100">
               <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" class="card-img-top" alt="<?= $fetch_products['name']; ?>">
               <div class="card-body">
                  <h6 class="fw-bold"><?= $fetch_products['name']; ?></h6>
                  <p class="text-primary fw-bold mb-1">$<?= $fetch_products['price']; ?>/-</p>
                  <p class="text-muted small" style="line-height:1.8;"><?= $fetch_products['details']; ?></p>
               </div>
               <div class="card-footer bg-transparent border-0 d-flex gap-2 pt-0">
                  <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="btn btn-warning btn-sm flex-fill">Update</a>
                  <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="btn btn-danger btn-sm flex-fill swal-confirm-delete" data-swal-msg="Delete this product?">Delete</a>
               </div>
            </div>
         </div>
      <?php
            }
         }else{
            echo '<p class="empty">No products added yet!</p>';
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
