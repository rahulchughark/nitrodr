<?php
session_start();
  if($_GET['donotshow']!=''){

    $_SESSION['donotshow'] = $_GET['donotshow'];
  }
//echo $_SESSION['donotshow'];

  

?>