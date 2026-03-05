<?php

include '../components/connect.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:seller_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard - Seller Panel</title>
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
      <h1 class="heading">Seller Dashboard</h1>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">

         <!-- My Products -->
         <div class="col" data-aos="fade-up">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
                  $select_products->execute([$seller_id]);
                  $total_products = $select_products->rowCount();
               ?>
               <h3><?= $total_products; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">My Products</p>
               <a href="products.php" class="btn btn-primary btn-sm">View Products</a>
            </div>
         </div>

         <!-- Total Orders -->
         <div class="col" data-aos="fade-up" data-aos-delay="50">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  // Get seller's product names
                  $seller_products = $conn->prepare("SELECT name FROM `products` WHERE seller_id = ?");
                  $seller_products->execute([$seller_id]);
                  $product_names = [];
                  while($sp = $seller_products->fetch(PDO::FETCH_ASSOC)){
                     $product_names[] = $sp['name'];
                  }
                  
                  $total_orders = 0;
                  if(!empty($product_names)){
                     $all_orders = $conn->prepare("SELECT * FROM `orders`");
                     $all_orders->execute();
                     while($order = $all_orders->fetch(PDO::FETCH_ASSOC)){
                        foreach($product_names as $pname){
                           if(stripos($order['total_products'], $pname) !== false){
                              $total_orders++;
                              break;
                           }
                        }
                     }
                  }
               ?>
               <h3><?= $total_orders; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Total Orders</p>
               <a href="orders.php" class="btn btn-primary btn-sm">View Orders</a>
            </div>
         </div>

         <!-- Monthly Revenue -->
         <div class="col" data-aos="fade-up" data-aos-delay="100">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $monthly_revenue = 0;
                  $current_month = date('Y-m');
                  if(!empty($product_names)){
                     $month_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'completed' AND placed_on LIKE ?");
                     $month_orders->execute([$current_month.'%']);
                     while($mo = $month_orders->fetch(PDO::FETCH_ASSOC)){
                        foreach($product_names as $pname){
                           if(stripos($mo['total_products'], $pname) !== false){
                              $monthly_revenue += $mo['total_price'];
                              break;
                           }
                        }
                     }
                  }
               ?>
               <h3>$<?= number_format($monthly_revenue); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Monthly Revenue</p>
               <a href="orders.php" class="btn btn-primary btn-sm">View Details</a>
            </div>
         </div>

         <!-- Account Status -->
         <div class="col" data-aos="fade-up" data-aos-delay="150">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3><span class="badge bg-success fs-5">Approved</span></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Account Status</p>
               <p class="small text-muted mb-0"><?= $fetch_seller['email']; ?></p>
            </div>
         </div>

      </div>

      <!-- Sales Chart -->
      <div class="row justify-content-center" data-aos="fade-up">
         <div class="col-lg-8">
            <div class="card shadow-sm border-0">
               <div class="card-header bg-white border-bottom">
                  <h5 class="mb-0 fw-bold">My Sales - Last 30 Days</h5>
               </div>
               <div class="card-body">
                  <canvas id="sellerSalesChart" height="300"></canvas>
               </div>
            </div>
         </div>
      </div>

      <?php
         // Build last 30 days sales data
         $chart_labels = [];
         $chart_data = [];
         for($i = 29; $i >= 0; $i--){
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart_labels[] = date('M d', strtotime($date));
            $day_total = 0;
            if(!empty($product_names)){
               $day_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'completed' AND placed_on = ?");
               $day_orders->execute([$date]);
               while($dord = $day_orders->fetch(PDO::FETCH_ASSOC)){
                  foreach($product_names as $pname){
                     if(stripos($dord['total_products'], $pname) !== false){
                        $day_total += $dord['total_price'];
                        break;
                     }
                  }
               }
            }
            $chart_data[] = $day_total;
         }
      ?>

   </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/seller_script.js"></script>
<script>
   const ctx = document.getElementById('sellerSalesChart').getContext('2d');
   new Chart(ctx, {
      type: 'line',
      data: {
         labels: <?= json_encode($chart_labels); ?>,
         datasets: [{
            label: 'Revenue ($)',
            data: <?= json_encode($chart_data); ?>,
            borderColor: '#27ae60',
            backgroundColor: 'rgba(39,174,96,0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: '#27ae60'
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         scales: {
            y: { beginAtZero: true, ticks: { callback: function(v){ return '$' + v; } } },
            x: { ticks: { maxTicksLimit: 10 } }
         },
         plugins: { legend: { display: false } }
      }
   });
</script>

</body>
</html>
