<?php include("includes/include.php");

$_POST['email'] = $_POST['email'];


  $query = db_query("select email from users where email='" . $_POST['email']."'");

  if(mysqli_num_rows($query) > 0){
     echo "exist";
  }else{
    echo "not_exist";
  }


?>
