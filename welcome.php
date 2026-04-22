<?php

session_start();

$userType = $_SESSION['user_type'] ?? '';

if (in_array($userType, ['SALES MNGR', 'OPERATIONS', 'MNGR', 'USR'], true)) {
    header("Location: user_dashboard.php");
    exit();
}

include('includes/header.php');
include_once('helpers/dashboard_helper.php');



?>
<style>
   .welcome{
    position: relative;;
   }
  .center-content{
    position:absolute;
    text-align: center;
    top: 45%;
    left: 25%;
  }
  .dashboard-button a{
   color:#fff
  }
</style>
<div class="main-content">
    <div class="page-content">
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
        <div class="card"> 
        <div class="card-body welcome"> 
        
       <div class="center-content">
       <h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to Nitro DR Portal.</h1>

       </div>
       

    </div>
</div>
</div>

</div>
</div>
</div>
</div>
<?php
 include('includes/footer.php')
 
 
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var buttons = document.querySelectorAll('.dashboard-button');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                this.disabled = true; // Disables the clicked button
                this.textContent = 'Please wait..';
            });
        });
    });
    $(document).ready(function() {
                var wfheight = $(window).height();
                $('.welcome').height(wfheight - 180);
                

            });
</script>

