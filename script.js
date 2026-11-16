// Confirm Delete function
function confirmDelete(id, name) {
   if (confirm(`Are you sure you want to delete "${name}"?\n\nThis action cannot be undone.`)) {
       // Detect if we are on categories or products page
       const currentPage = window.location.pathname.includes('categories') ? 'categories.php' : 'products.php';
       window.location.href = `${currentPage}?delete=${id}`;
   }
}
// Auto-hide success alerts after 3 seconds
document.addEventListener('DOMContentLoaded', function () {
   const alertBox = document.getElementById('alertBox');
   if (alertBox) {
       setTimeout(() => {
           alertBox.style.transition = 'opacity 0.5s';
           alertBox.style.opacity = '0';
           setTimeout(() => {
               alertBox.remove();
           }, 500);
       }, 3000);
   }
});