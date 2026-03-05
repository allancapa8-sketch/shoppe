<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages - Admin Panel</title>
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
      <h1 class="heading">Messages</h1>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         if($select_messages->rowCount() > 0){
            while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
      ?>
         <div class="col" data-aos="fade-up">
            <div class="card admin-message-card shadow-sm border-0 h-100">
               <div class="card-body">
                  <p>User ID: <span><?= $fetch_message['user_id']; ?></span></p>
                  <p>Name: <span><?= $fetch_message['name']; ?></span></p>
                  <p>Email: <span><?= $fetch_message['email']; ?></span></p>
                  <p>Number: <span><?= $fetch_message['number']; ?></span></p>
                  <p>Message: <span><?= $fetch_message['message']; ?></span></p>
               </div>
               <div class="card-footer bg-transparent border-0 pt-0">
                  <a href="messages.php?delete=<?= $fetch_message['id']; ?>" class="btn btn-danger btn-sm w-100 swal-confirm-delete" data-swal-msg="Delete this message?">Delete</a>
               </div>
            </div>
         </div>
      <?php
            }
         }else{
            echo '<p class="empty">You have no messages</p>';
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
