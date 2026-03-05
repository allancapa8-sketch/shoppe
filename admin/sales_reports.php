<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// ---- Summary Calculations ----
// Today's sales
$today = date('Y-m-d');
$today_sales = $conn->prepare("SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed' AND placed_on = ?");
$today_sales->execute([$today]);
$today_total = $today_sales->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// This month's sales
$current_month = date('Y-m');
$month_sales = $conn->prepare("SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed' AND placed_on LIKE ?");
$month_sales->execute([$current_month.'%']);
$month_total = $month_sales->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Overall revenue
$overall_revenue = $conn->prepare("SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed'");
$overall_revenue->execute();
$overall_total = $overall_revenue->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Total orders count
$total_orders = $conn->prepare("SELECT COUNT(*) as cnt FROM `orders`");
$total_orders->execute();
$orders_count = $total_orders->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;

// ---- Daily Sales Chart Data (current month) ----
$days_in_month = date('t');
$daily_labels = [];
$daily_data = [];
for($d = 1; $d <= $days_in_month; $d++){
   $date_str = date('Y-m').'-'.str_pad($d, 2, '0', STR_PAD_LEFT);
   $daily_labels[] = 'Day '.$d;
   $day_sales = $conn->prepare("SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed' AND placed_on = ?");
   $day_sales->execute([$date_str]);
   $daily_data[] = (int)($day_sales->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
}

// ---- Monthly Sales Chart Data (current year) ----
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$monthly_data = [];
$current_year = date('Y');
for($m = 1; $m <= 12; $m++){
   $month_str = $current_year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT);
   $m_sales = $conn->prepare("SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed' AND placed_on LIKE ?");
   $m_sales->execute([$month_str.'%']);
   $monthly_data[] = (int)($m_sales->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
}

// ---- Seller Performance Data ----
$seller_names = [];
$seller_revenues = [];
$seller_product_counts = [];
$seller_order_counts = [];

$all_sellers = $conn->prepare("SELECT * FROM `sellers` WHERE status = 'approved' ORDER BY name ASC");
$all_sellers->execute();
while($seller = $all_sellers->fetch(PDO::FETCH_ASSOC)){
   $seller_names[] = $seller['name'];

   // Product count
   $s_products = $conn->prepare("SELECT COUNT(*) as cnt FROM `products` WHERE seller_id = ?");
   $s_products->execute([$seller['id']]);
   $seller_product_counts[] = (int)($s_products->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

   // Get seller's product names for order matching
   $s_pnames = $conn->prepare("SELECT name FROM `products` WHERE seller_id = ?");
   $s_pnames->execute([$seller['id']]);
   $pnames = [];
   while($pn = $s_pnames->fetch(PDO::FETCH_ASSOC)){ $pnames[] = $pn['name']; }

   $s_revenue = 0;
   $s_orders = 0;
   if(!empty($pnames)){
      $all_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'completed'");
      $all_orders->execute();
      while($order = $all_orders->fetch(PDO::FETCH_ASSOC)){
         foreach($pnames as $pn){
            if(stripos($order['total_products'], $pn) !== false){
               $s_revenue += $order['total_price'];
               $s_orders++;
               break;
            }
         }
      }
   }
   $seller_revenues[] = $s_revenue;
   $seller_order_counts[] = $s_orders;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sales Reports - Admin Panel</title>
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
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h1 class="heading mb-0">Sales Reports</h1>
         <button onclick="window.print();" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print me-1"></i> Print Report</button>
      </div>

      <!-- Summary Cards -->
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
         <div class="col" data-aos="fade-up">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3>$<?= number_format($today_total); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Daily Sales (Today)</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="50">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3>$<?= number_format($month_total); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Monthly Sales (<?= date('F Y'); ?>)</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="100">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3>$<?= number_format($overall_total); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Overall Revenue</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="150">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3><?= $orders_count; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Total Orders</p>
            </div>
         </div>
      </div>

      <!-- Daily Sales Bar Chart -->
      <div class="row mb-5">
         <div class="col-12" data-aos="fade-up">
            <div class="card shadow-sm border-0">
               <div class="card-header bg-white border-bottom">
                  <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>Daily Sales - <?= date('F Y'); ?></h5>
               </div>
               <div class="card-body">
                  <canvas id="dailySalesChart" height="300"></canvas>
               </div>
            </div>
         </div>
      </div>

      <!-- Monthly Sales Line Chart -->
      <div class="row mb-5">
         <div class="col-12" data-aos="fade-up">
            <div class="card shadow-sm border-0">
               <div class="card-header bg-white border-bottom">
                  <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-success"></i>Monthly Sales - <?= $current_year; ?></h5>
               </div>
               <div class="card-body">
                  <canvas id="monthlySalesChart" height="300"></canvas>
               </div>
            </div>
         </div>
      </div>

      <!-- Seller Performance -->
      <div class="row mb-5">
         <div class="col-12" data-aos="fade-up">
            <div class="card shadow-sm border-0">
               <div class="card-header bg-white border-bottom">
                  <h5 class="mb-0 fw-bold"><i class="fas fa-users me-2 text-warning"></i>Seller Performance</h5>
               </div>
               <div class="card-body">
                  <?php if(!empty($seller_names)): ?>
                     <div class="row">
                        <div class="col-lg-7">
                           <canvas id="sellerPerformanceChart" height="300"></canvas>
                        </div>
                        <div class="col-lg-5">
                           <div class="table-responsive mt-3 mt-lg-0">
                              <table class="table table-sm table-striped align-middle">
                                 <thead class="table-dark">
                                    <tr>
                                       <th>Seller</th>
                                       <th>Products</th>
                                       <th>Orders</th>
                                       <th>Revenue</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php for($i = 0; $i < count($seller_names); $i++): ?>
                                    <tr>
                                       <td class="fw-bold"><?= $seller_names[$i]; ?></td>
                                       <td><?= $seller_product_counts[$i]; ?></td>
                                       <td><?= $seller_order_counts[$i]; ?></td>
                                       <td class="fw-bold text-success">$<?= number_format($seller_revenues[$i]); ?></td>
                                    </tr>
                                    <?php endfor; ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  <?php else: ?>
                     <p class="text-center text-muted my-4">No approved sellers to show performance data.</p>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>

   </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/admin_script.js"></script>
<script>
// Daily Sales Bar Chart
const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
new Chart(dailyCtx, {
   type: 'bar',
   data: {
      labels: <?= json_encode($daily_labels); ?>,
      datasets: [{
         label: 'Revenue ($)',
         data: <?= json_encode($daily_data); ?>,
         backgroundColor: 'rgba(41,128,185,0.7)',
         borderColor: '#2980b9',
         borderWidth: 1,
         borderRadius: 4
      }]
   },
   options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
         y: { beginAtZero: true, ticks: { callback: function(v){ return '$' + v; } } },
         x: { ticks: { maxTicksLimit: 15 } }
      },
      plugins: { legend: { display: false } }
   }
});

// Monthly Sales Line Chart
const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
new Chart(monthlyCtx, {
   type: 'line',
   data: {
      labels: <?= json_encode($months); ?>,
      datasets: [{
         label: 'Revenue ($)',
         data: <?= json_encode($monthly_data); ?>,
         borderColor: '#27ae60',
         backgroundColor: 'rgba(39,174,96,0.1)',
         fill: true,
         tension: 0.4,
         pointRadius: 5,
         pointBackgroundColor: '#27ae60',
         pointBorderColor: '#fff',
         pointBorderWidth: 2
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

// Seller Performance Horizontal Bar Chart
<?php if(!empty($seller_names)): ?>
const sellerCtx = document.getElementById('sellerPerformanceChart').getContext('2d');
const sellerColors = [
   '#2980b9', '#27ae60', '#f39c12', '#e74c3c', '#8e44ad',
   '#1abc9c', '#d35400', '#2c3e50', '#c0392b', '#16a085'
];
new Chart(sellerCtx, {
   type: 'bar',
   data: {
      labels: <?= json_encode($seller_names); ?>,
      datasets: [{
         label: 'Revenue ($)',
         data: <?= json_encode($seller_revenues); ?>,
         backgroundColor: sellerColors.slice(0, <?= count($seller_names); ?>),
         borderRadius: 4
      }]
   },
   options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
      scales: {
         x: { beginAtZero: true, ticks: { callback: function(v){ return '$' + v; } } }
      },
      plugins: { legend: { display: false } }
   }
});
<?php endif; ?>
</script>

</body>
</html>
