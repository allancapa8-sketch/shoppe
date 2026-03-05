<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Handle payment status update
if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';
}

// Date range filter
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Transactions - Admin Panel</title>
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
      <h1 class="heading">Transaction Monitoring</h1>

      <!-- Summary Cards -->
      <?php
         $total_transactions = $conn->prepare("SELECT COUNT(*) as cnt, SUM(total_price) as total FROM `orders`");
         $total_transactions->execute();
         $trans_summary = $total_transactions->fetch(PDO::FETCH_ASSOC);

         $pending_payments = $conn->prepare("SELECT COUNT(*) as cnt, SUM(total_price) as total FROM `orders` WHERE payment_status = 'pending'");
         $pending_payments->execute();
         $pending_summary = $pending_payments->fetch(PDO::FETCH_ASSOC);

         $completed_payments = $conn->prepare("SELECT COUNT(*) as cnt, SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed'");
         $completed_payments->execute();
         $completed_summary = $completed_payments->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
         <div class="col" data-aos="fade-up">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3><?= $trans_summary['cnt'] ?? 0; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Total Transactions</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="50">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3>$<?= number_format($trans_summary['total'] ?? 0); ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Total Revenue</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="100">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3 class="text-warning"><?= $pending_summary['cnt'] ?? 0; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Pending Payments</p>
            </div>
         </div>
         <div class="col" data-aos="fade-up" data-aos-delay="150">
            <div class="card dashboard-card shadow-sm border-0 text-center p-4 h-100">
               <h3 class="text-success"><?= $completed_summary['cnt'] ?? 0; ?></h3>
               <p class="text-muted bg-light rounded p-2 my-2">Completed Payments</p>
            </div>
         </div>
      </div>

      <!-- Filter Form -->
      <div class="card shadow-sm border-0 mb-4" data-aos="fade-up">
         <div class="card-body">
            <form action="" method="get" class="row g-3 align-items-end">
               <div class="col-md-3">
                  <label class="form-label">From Date</label>
                  <input type="date" name="date_from" class="form-control" value="<?= $date_from; ?>">
               </div>
               <div class="col-md-3">
                  <label class="form-label">To Date</label>
                  <input type="date" name="date_to" class="form-control" value="<?= $date_to; ?>">
               </div>
               <div class="col-md-3">
                  <label class="form-label">Payment Status</label>
                  <select name="filter_status" class="form-select">
                     <option value="">All</option>
                     <option value="pending" <?= ($filter_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                     <option value="completed" <?= ($filter_status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                  </select>
               </div>
               <div class="col-md-3">
                  <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
               </div>
            </form>
         </div>
      </div>

      <!-- Transactions Table -->
      <div class="card shadow-sm border-0" data-aos="fade-up">
         <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">All Transactions</h5>
            <button onclick="window.print();" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print me-1"></i> Print</button>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-striped table-hover align-middle">
                  <thead class="table-dark">
                     <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Total</th>
                        <th>Method</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        // Build query based on filters
                        $query = "SELECT * FROM `orders` WHERE 1=1";
                        $params = [];

                        if(!empty($date_from)){
                           $query .= " AND placed_on >= ?";
                           $params[] = $date_from;
                        }
                        if(!empty($date_to)){
                           $query .= " AND placed_on <= ?";
                           $params[] = $date_to;
                        }
                        if(!empty($filter_status)){
                           $query .= " AND payment_status = ?";
                           $params[] = $filter_status;
                        }

                        $query .= " ORDER BY placed_on DESC";
                        $select_orders = $conn->prepare($query);
                        $select_orders->execute($params);

                        if($select_orders->rowCount() > 0){
                           while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                              $ps = $fetch_orders['payment_status'];
                              $ps_badge = ($ps == 'completed') ? 'success' : 'warning';
                     ?>
                     <tr>
                        <td><?= $fetch_orders['id']; ?></td>
                        <td>
                           <strong><?= $fetch_orders['name']; ?></strong><br>
                           <small class="text-muted"><?= $fetch_orders['email']; ?></small>
                        </td>
                        <td><small><?= $fetch_orders['total_products']; ?></small></td>
                        <td class="fw-bold">$<?= number_format($fetch_orders['total_price']); ?></td>
                        <td><span class="badge bg-secondary"><?= $fetch_orders['method']; ?></span></td>
                        <td><span class="badge bg-<?= $ps_badge; ?>"><?= ucfirst($ps); ?></span></td>
                        <td><?= $fetch_orders['placed_on']; ?></td>
                        <td>
                           <form action="" method="post" class="d-flex gap-1">
                              <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                              <select name="payment_status" class="form-select form-select-sm" style="width:120px;">
                                 <option value="pending" <?= ($ps == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                 <option value="completed" <?= ($ps == 'completed') ? 'selected' : ''; ?>>Completed</option>
                              </select>
                              <button type="submit" name="update_payment" class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i></button>
                           </form>
                        </td>
                     </tr>
                     <?php
                           }
                        }else{
                           echo '<tr><td colspan="8" class="text-center text-muted">No transactions found.</td></tr>';
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
