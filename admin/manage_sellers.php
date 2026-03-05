<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['approve'])){
   $seller_id = $_GET['approve'];
   $approve = $conn->prepare("UPDATE `sellers` SET status = 'approved' WHERE id = ?");
   $approve->execute([$seller_id]);
   $message[] = 'seller approved successfully!';
}

if(isset($_GET['reject'])){
   $seller_id = $_GET['reject'];
   $reject = $conn->prepare("UPDATE `sellers` SET status = 'rejected' WHERE id = ?");
   $reject->execute([$seller_id]);
   $message[] = 'seller rejected successfully!';
}

if(isset($_GET['delete'])){
   $seller_id = $_GET['delete'];
   // Delete seller's products too
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE seller_id = ?");
   $delete_products->execute([$seller_id]);
   $delete_seller = $conn->prepare("DELETE FROM `sellers` WHERE id = ?");
   $delete_seller->execute([$seller_id]);
   header('location:manage_sellers.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Sellers - Admin Panel</title>
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
      <h1 class="heading">Manage Sellers</h1>

      <!-- Summary Cards -->
      <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
         <div class="col" data-aos="fade-up">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $total_sellers = $conn->prepare("SELECT * FROM `sellers`");
                  $total_sellers->execute();
               ?>
               <h3><?= $total_sellers->rowCount(); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Total Sellers</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="50">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $pending_sellers = $conn->prepare("SELECT * FROM `sellers` WHERE status = 'pending'");
                  $pending_sellers->execute();
               ?>
               <h3 class="text-warning"><?= $pending_sellers->rowCount(); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Pending Approval</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="100">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $approved_sellers = $conn->prepare("SELECT * FROM `sellers` WHERE status = 'approved'");
                  $approved_sellers->execute();
               ?>
               <h3 class="text-success"><?= $approved_sellers->rowCount(); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Approved Sellers</p>
            </div>
         </div>
      </div>

      <!-- Sellers Table -->
      <div class="card shadow-sm border-0" data-aos="fade-up">
         <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">All Sellers</h5>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-striped table-hover align-middle">
                  <thead class="table-dark">
                     <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Date Joined</th>
                        <th>Products</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        $select_sellers = $conn->prepare("SELECT * FROM `sellers` ORDER BY created_at DESC");
                        $select_sellers->execute();
                        if($select_sellers->rowCount() > 0){
                           while($fetch = $select_sellers->fetch(PDO::FETCH_ASSOC)){
                              // Count seller's products
                              $count_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
                              $count_products->execute([$fetch['id']]);
                              $product_count = $count_products->rowCount();

                              $status = $fetch['status'];
                              if($status == 'pending') $badge = 'warning';
                              elseif($status == 'approved') $badge = 'success';
                              else $badge = 'danger';
                     ?>
                     <tr>
                        <td><?= $fetch['id']; ?></td>
                        <td class="fw-bold"><?= $fetch['name']; ?></td>
                        <td><?= $fetch['email']; ?></td>
                        <td><span class="badge bg-<?= $badge; ?>"><?= ucfirst($status); ?></span></td>
                        <td><?= $fetch['created_at']; ?></td>
                        <td><?= $product_count; ?></td>
                        <td>
                           <div class="d-flex gap-1 flex-wrap">
                              <?php if($status == 'pending' || $status == 'rejected'): ?>
                                 <a href="manage_sellers.php?approve=<?= $fetch['id']; ?>" class="btn btn-success btn-sm" title="Approve"><i class="fas fa-check"></i></a>
                              <?php endif; ?>
                              <?php if($status == 'pending' || $status == 'approved'): ?>
                                 <a href="manage_sellers.php?reject=<?= $fetch['id']; ?>" class="btn btn-warning btn-sm" title="Reject"><i class="fas fa-times"></i></a>
                              <?php endif; ?>
                              <a href="manage_sellers.php?delete=<?= $fetch['id']; ?>" class="btn btn-danger btn-sm swal-confirm-delete" data-swal-msg="Delete this seller and all their products?" title="Delete"><i class="fas fa-trash"></i></a>
                           </div>
                        </td>
                     </tr>
                     <?php
                           }
                        }else{
                           echo '<tr><td colspan="7" class="text-center text-muted">No sellers registered yet.</td></tr>';
                        }
                     ?>
                  </tbody>
               </table>
            </div>
         </div>
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
