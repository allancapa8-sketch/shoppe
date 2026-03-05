<?php

include '../components/connect.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:seller_login.php');
}

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   // Verify product belongs to seller
   $check_owner = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
   $check_owner->execute([$pid, $seller_id]);
   if($check_owner->rowCount() == 0){
      $message[] = 'you are not authorized to update this product!';
   }else{

      $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ? WHERE id = ? AND seller_id = ?");
      $update_product->execute([$name, $price, $details, $pid, $seller_id]);

      $message[] = 'product updated successfully!';

      $old_image_01 = $_POST['old_image_01'];
      $image_01 = $_FILES['image_01']['name'];
      $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
      $image_size_01 = $_FILES['image_01']['size'];
      $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
      $image_folder_01 = '../uploaded_img/'.$image_01;

      if(!empty($image_01)){
         if($image_size_01 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            $update_image_01 = $conn->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
            $update_image_01->execute([$image_01, $pid]);
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            @unlink('../uploaded_img/'.$old_image_01);
            $message[] = 'image 01 updated successfully!';
         }
      }

      $old_image_02 = $_POST['old_image_02'];
      $image_02 = $_FILES['image_02']['name'];
      $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
      $image_size_02 = $_FILES['image_02']['size'];
      $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
      $image_folder_02 = '../uploaded_img/'.$image_02;

      if(!empty($image_02)){
         if($image_size_02 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            $update_image_02 = $conn->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
            $update_image_02->execute([$image_02, $pid]);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            @unlink('../uploaded_img/'.$old_image_02);
            $message[] = 'image 02 updated successfully!';
         }
      }

      $old_image_03 = $_POST['old_image_03'];
      $image_03 = $_FILES['image_03']['name'];
      $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
      $image_size_03 = $_FILES['image_03']['size'];
      $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
      $image_folder_03 = '../uploaded_img/'.$image_03;

      if(!empty($image_03)){
         if($image_size_03 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            $update_image_03 = $conn->prepare("UPDATE `products` SET image_03 = ? WHERE id = ?");
            $update_image_03->execute([$image_03, $pid]);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            @unlink('../uploaded_img/'.$old_image_03);
            $message[] = 'image 03 updated successfully!';
         }
      }

   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product - Seller Panel</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
   <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
   <link rel="stylesheet" href="../css/seller_style.css">
</head>
<body>

<?php include '../components/seller_header.php'; ?>

<section class="section-padding">
   <div class="container">
      <h1 class="heading">Update Product</h1>

      <?php
         $update_id = $_GET['update'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
         $select_products->execute([$update_id, $seller_id]);
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="row justify-content-center" data-aos="fade-up">
         <div class="col-lg-8">
            <form action="" method="post" enctype="multipart/form-data" class="card shadow-sm border-0 p-4">
               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
               <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
               <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
               <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">

               <!-- Current Images -->
               <div class="text-center mb-4">
                  <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="Product Image" class="img-fluid rounded mb-3" style="max-height:250px;">
                  <div class="d-flex justify-content-center gap-2">
                     <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="" class="rounded" style="width:80px; height:80px; object-fit:cover;">
                     <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="" class="rounded" style="width:80px; height:80px; object-fit:cover;">
                     <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="" class="rounded" style="width:80px; height:80px; object-fit:cover;">
                  </div>
               </div>

               <div class="mb-3">
                  <label class="form-label">Product Name</label>
                  <input type="text" name="name" required class="form-control" maxlength="100" placeholder="Enter product name" value="<?= $fetch_products['name']; ?>">
               </div>
               <div class="mb-3">
                  <label class="form-label">Product Price ($)</label>
                  <input type="number" name="price" required class="form-control" min="0" max="9999999999" placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>">
               </div>
               <div class="mb-3">
                  <label class="form-label">Product Details</label>
                  <textarea name="details" class="form-control" required cols="30" rows="6"><?= $fetch_products['details']; ?></textarea>
               </div>
               <div class="mb-3">
                  <label class="form-label">Update Image 01</label>
                  <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control">
               </div>
               <div class="mb-3">
                  <label class="form-label">Update Image 02</label>
                  <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control">
               </div>
               <div class="mb-3">
                  <label class="form-label">Update Image 03</label>
                  <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control">
               </div>
               <div class="d-flex gap-2">
                  <button type="submit" name="update" class="btn btn-primary flex-fill"><i class="fas fa-sync-alt me-1"></i> Update</button>
                  <a href="products.php" class="btn btn-outline-secondary flex-fill"><i class="fas fa-arrow-left me-1"></i> Go Back</a>
               </div>
            </form>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<div class="alert alert-warning text-center">No product found or you are not authorized!</div>';
         }
      ?>
   </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/seller_script.js"></script>

</body>
</html>
