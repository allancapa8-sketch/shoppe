<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard - Admin Panel</title>
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
      <h1 class="heading">Dashboard</h1>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

         <!-- Welcome -->
         <div class="col" data-aos="fade-up">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3>Welcome!</h3>
               <p class="text-muted bg-light rounded p-2 my-2"><?= $fetch_profile['name']; ?></p>
               <a href="update_profile.php" class="btn btn-primary btn-sm">Update Profile</a>
            </div>
         </div>

         <!-- Total Pendings -->
         <div class="col" data-aos="fade-up" data-aos-delay="50">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $total_pendings = 0;
                  $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                  $select_pendings->execute(['pending']);
                  while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                     $total_pendings += $fetch_pendings['total_price'];
                  }
               ?>
               <h3>$<?= number_format($total_pendings); ?>/-</h3>
               <p class="text-muted bg-light rounded p-2 my-2">Total Pendings</p>
               <a href="placed_orders.php" class="btn btn-primary btn-sm">See Orders</a>
            </div>
         </div>

         <!-- Completed Orders -->
         <div class="col" data-aos="fade-up" data-aos-delay="100">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $total_completes = 0;
                  $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                  $select_completes->execute(['completed']);
                  while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                     $total_completes += $fetch_completes['total_price'];
                  }
               ?>
               <h3>$<?= number_format($total_completes); ?>/-</h3>
               <p class="text-muted bg-light rounded p-2 my-2">Completed Orders</p>
               <a href="placed_orders.php" class="btn btn-primary btn-sm">See Orders</a>
            </div>
         </div>

         <!-- Number of Orders -->
         <div class="col" data-aos="fade-up" data-aos-delay="150">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $select_orders = $conn->prepare("SELECT * FROM `orders`");
                  $select_orders->execute();
                  $number_of_orders = $select_orders->rowCount();
               ?>
               <h3><?= $number_of_orders; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Orders Placed</p>
               <a href="placed_orders.php" class="btn btn-primary btn-sm">See Orders</a>
            </div>
         </div>

         <!-- Monthly Revenue -->
         <div class="col" data-aos="fade-up" data-aos-delay="200">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $current_month = date('Y-m');
                  $monthly_revenue = 0;
                  $select_monthly = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'completed' AND placed_on LIKE ?");
                  $select_monthly->execute([$current_month.'%']);
                  while($fetch_monthly = $select_monthly->fetch(PDO::FETCH_ASSOC)){
                     $monthly_revenue += $fetch_monthly['total_price'];
                  }
               ?>
               <h3>$<?= number_format($monthly_revenue); ?>/-</h3>
               <p class="text-muted bg-light rounded p-2 my-2">Monthly Revenue</p>
               <a href="sales_reports.php" class="btn btn-primary btn-sm">See Reports</a>
            </div>
         </div>

         <!-- Products -->
         <div class="col" data-aos="fade-up" data-aos-delay="250">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $select_products = $conn->prepare("SELECT * FROM `products`");
                  $select_products->execute();
                  $number_of_products = $select_products->rowCount();
               ?>
               <h3><?= $number_of_products; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Products Added</p>
               <a href="products.php" class="btn btn-primary btn-sm">See Products</a>
            </div>
         </div>

         <!-- Users -->
         <div class="col" data-aos="fade-up" data-aos-delay="300">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $select_users = $conn->prepare("SELECT * FROM `users`");
                  $select_users->execute();
                  $number_of_users = $select_users->rowCount();
               ?>
               <h3><?= $number_of_users; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Normal Users</p>
               <a href="users_accounts.php" class="btn btn-primary btn-sm">See Users</a>
            </div>
         </div>

         <!-- Admins -->
         <div class="col" data-aos="fade-up" data-aos-delay="350">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $select_admins = $conn->prepare("SELECT * FROM `admins`");
                  $select_admins->execute();
                  $number_of_admins = $select_admins->rowCount();
               ?>
               <h3><?= $number_of_admins; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Admin Users</p>
               <a href="admin_accounts.php" class="btn btn-primary btn-sm">See Admins</a>
            </div>
         </div>

         <!-- Sellers -->
         <div class="col" data-aos="fade-up" data-aos-delay="400">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $select_sellers = $conn->prepare("SELECT * FROM `sellers`");
                  $select_sellers->execute();
                  $number_of_sellers = $select_sellers->rowCount();
                  $select_pending_sellers = $conn->prepare("SELECT * FROM `sellers` WHERE status = 'pending'");
                  $select_pending_sellers->execute();
                  $pending_sellers = $select_pending_sellers->rowCount();
               ?>
               <h3><?= $number_of_sellers; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Total Sellers</p>
               <?php if($pending_sellers > 0): ?>
                  <span class="badge bg-warning text-dark mb-2"><?= $pending_sellers; ?> pending approval</span><br>
               <?php endif; ?>
               <a href="manage_sellers.php" class="btn btn-primary btn-sm">Manage Sellers</a>
            </div>
         </div>

         <!-- Messages -->
         <div class="col" data-aos="fade-up" data-aos-delay="450">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <?php
                  $select_messages = $conn->prepare("SELECT * FROM `messages`");
                  $select_messages->execute();
                  $number_of_messages = $select_messages->rowCount();
               ?>
               <h3><?= $number_of_messages; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">New Messages</p>
               <a href="messages.php" class="btn btn-primary btn-sm">See Messages</a>
            </div>
         </div>

      </div>

      <!-- Revenue Chart - Last 7 Days -->
      <div class="row justify-content-center mt-5" data-aos="fade-up">
         <div class="col-lg-8">
            <div class="card shadow-sm border-0">
               <div class="card-header bg-white border-bottom">
                  <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>Revenue - Last 7 Days</h5>
               </div>
               <div class="card-body">
                  <canvas id="revenueChart" height="300"></canvas>
               </div>
            </div>
         </div>
      </div>

      <?php
         // Build last 7 days revenue data
         $chart_labels = [];
         $chart_data = [];
         for($i = 6; $i >= 0; $i--){
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart_labels[] = date('M d', strtotime($date));
            $day_total = 0;
            $day_orders = $conn->prepare("SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed' AND placed_on = ?");
            $day_orders->execute([$date]);
            $day_result = $day_orders->fetch(PDO::FETCH_ASSOC);
            $day_total = $day_result['total'] ?? 0;
            $chart_data[] = (int)$day_total;
         }
      ?>

   </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/admin_script.js"></script>
<script>
   const ctx = document.getElementById('revenueChart').getContext('2d');
   new Chart(ctx, {
      type: 'bar',
      data: {
         labels: <?= json_encode($chart_labels); ?>,
         datasets: [{
            label: 'Revenue ($)',
            data: <?= json_encode($chart_data); ?>,
            backgroundColor: 'rgba(41,128,185,0.7)',
            borderColor: '#2980b9',
            borderWidth: 1,
            borderRadius: 6
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         scales: {
            y: { beginAtZero: true, ticks: { callback: function(v){ return '$' + v; } } }
         },
         plugins: { legend: { display: false } }
      }
   });
</script>

</body>
</html>
