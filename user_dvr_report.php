
<?php include('includes/header.php');admin_page();

/*
    if($_GET['date'])
    {
        $dat=$_GET['date'];
    }
    else
    {
        $dat=date('Y-m-d');
    }*/

    if(isset($_POST['search'])){

        $where = '';

        if($_POST['partner']!=''){

            $where .=" AND team_id = '".$_POST['partner']."'"; 
        }

        if($_POST['users']!=''){
            $where .= " AND created_by ='".$_POST['users']."'";

        }
    }


     $sql=db_query("select count('id') as total ,date(created_date) as create_date from orders where dvr_flag ='1' $where group by date(created_date)");
       

      $count = 0;                             
     while($data=db_fetch_array($sql)){
        $rowData[$count]['title'] = $data['total']." DVR Entry";
        $rowData[$count]['start'] = $data['create_date'];
        $count++; 

     }

    $reportData =  json_encode(array("events" => $rowData));
    

    
    ?>


    <style> 
     #calendar {

      max-width: 900px;
    margin: 0 auto;
      }
    </style>

<!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Reports</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Daily Report</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            
                             
                            <div class="">
                                <a href="add_partner.php"><button  data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Partner" class="right-side bottom-right waves-effect waves-light btn-success btn btn-circle btn-lg pull-right m-l-10"><i class="ti-plus text-white"></i></button></a>
                                 
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body page_wrap">
                                <h4 class="card-title">DVR Report</h4>
                                  <div style="float:right;margin-right:20px">
                         <form method="POST" name="search">
                             <!-- <input type="text" value="<?php echo @$_GET['date']?>" class="datepicker" id="date" name="date" placeholder="Date" />
                             -->
                            <div class="row form-group">
                                 <?php $res = db_query("select * from partners"); ?>
                                 <label class="control-label text-right">Partners:&nbsp;</label>     
                                 <select name="partner" id="partner" class="form-control col-3">
                                     <option value="" >---Select---</option>
                                     <?php while($row=db_fetch_array($res))
                                     { ?>
                                         <option <?=(($_POST['partner' ]== $row['id'])?'selected':'')?>  value='<?=$row['id']?>'><?=$row['name']?></option>
                                     <?php } ?>
                                    </select>



                                   &nbsp;<label class="control-label text-right">Submitted By:&nbsp;</label>    
                                    <select name="users" id="users" class="form-control col-3">

                                         <option value=""> Select Users </option>
                                          <?php if($_POST['users'])
                                         {
                                             $res=db_query("select * from users where team_id=".$_POST['partner']); 
                                                    while($row=db_fetch_array($res))
                                                     { ?>
                                                    <option <?=(($_POST['users']==$row['id'])?'selected':'')?>  value='<?=$row['id']?>'><?=$row['name']?></option>
                                            <?php } } ?>                                                    
                                                                                             
                                     </select>
                                     &nbsp;

                                      <input type="submit" name="search" value="Search" />
                                     
                                </div>           

                             
                         </form>
                         </div> 
                                <!-- <h6 class="card-subtitle">Hi</h6> -->
                                 <div class="table-responsive m-t-40">
                                    <div id='calendar'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
               
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

  

</div>

        
             
 
   
<?php include('includes/footer.php') ?>
<script>


 $(document).ready(function() {

    function GetTodayDate() {
       var tdate = new Date();
       var dd = tdate.getDate(); //yields day
       var MM = tdate.getMonth(); //yields month
       var yyyy = tdate.getFullYear(); //yields year
       //var currentDate= dd + "-" +( MM+1) + "-" + yyyy;

       var currentDate= yyyy + "-" +( MM+1) + "-" + dd;

       return currentDate;
    }   

    //console.log(<?php echo $reportData; ?>);

    $('#calendar').fullCalendar({
       //  header: {
       //  left: 'prev,next today',
       //  center: 'title',
       //  right: 'month,agendaWeek,agendaDay'
       // },

      defaultDate: GetTodayDate(),
      editable: true,
      eventLimit: true, // allow "more" link when too many events
        
      events: <?php echo $reportData; ?>
     
    });


    $('#partner').on('change',function(){
        //alert("hi");
        var partnerID = $(this).val();
        if(partnerID){
            $.ajax({
                type:'POST',
                url:'ajaxusers.php',
                data:'partner_id='+partnerID,
                success:function(html){
                    //alert(html);
                    $('#users').html(html);
                }
            }); 
        } 
    }); 


 
    });   



</script>
      <script>
$(document).ready(function(){
	
	 var wfheight = $(window).height();
                  
                  $('.page_wrap').height(wfheight-210);
                  


      $('.page_wrap').slimScroll({
        color: '#00f',
        size: '10px',
       height: 'auto',
	   
      
    });
	
});
</script>