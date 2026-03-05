// ===== Admin Panel - Script =====

// AOS Initialization
document.addEventListener('DOMContentLoaded', function () {
   if (typeof AOS !== 'undefined') {
      AOS.init({
         duration: 800,
         once: true,
         easing: 'ease-out'
      });
   }
});

// Update Product Image Gallery
let mainImage = document.querySelector('.update-product-form .main-image img');
let subImages = document.querySelectorAll('.update-product-form .sub-image img');

if (mainImage && subImages.length > 0) {
   subImages.forEach(function (img) {
      img.onclick = function () {
         mainImage.src = img.getAttribute('src');
      };
   });
}

// SweetAlert2 - Logout Confirmation
document.querySelectorAll('.swal-logout').forEach(function (el) {
   el.addEventListener('click', function (e) {
      e.preventDefault();
      var href = this.getAttribute('href');
      Swal.fire({
         title: 'Logout?',
         text: 'Are you sure you want to logout?',
         icon: 'question',
         showCancelButton: true,
         confirmButtonColor: '#2980b9',
         cancelButtonColor: '#6c757d',
         confirmButtonText: 'Yes, logout'
      }).then(function (result) {
         if (result.isConfirmed) {
            window.location.href = href;
         }
      });
   });
});

// SweetAlert2 - Delete Confirmation (links)
document.querySelectorAll('.swal-confirm-delete').forEach(function (el) {
   el.addEventListener('click', function (e) {
      e.preventDefault();
      var href = this.getAttribute('href');
      var msg = this.getAttribute('data-swal-msg') || 'This action cannot be undone.';
      Swal.fire({
         title: 'Are you sure?',
         text: msg,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#e74c3c',
         cancelButtonColor: '#6c757d',
         confirmButtonText: 'Yes, delete it'
      }).then(function (result) {
         if (result.isConfirmed) {
            window.location.href = href;
         }
      });
   });
});

// SweetAlert2 - Delete Confirmation (form submit buttons)
document.querySelectorAll('.swal-confirm-submit').forEach(function (el) {
   el.addEventListener('click', function (e) {
      e.preventDefault();
      var form = this.closest('form');
      var msg = this.getAttribute('data-swal-msg') || 'This action cannot be undone.';
      Swal.fire({
         title: 'Are you sure?',
         text: msg,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#e74c3c',
         cancelButtonColor: '#6c757d',
         confirmButtonText: 'Yes, do it'
      }).then(function (result) {
         if (result.isConfirmed) {
            form.submit();
         }
      });
   });
});
