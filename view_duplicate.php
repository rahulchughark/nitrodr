<?php include("includes/include.php"); ?>
<style>

.dataTables_wrapper {
  max-width: 100%;
}
</style>
<div class="modal-dialog  modal-xl modal-dialog-centered">
<?php 
// print_r($_POST);die;
if(trim($_POST['type'])=='school_name')
{
  $duplicate_name='school_name';
  $search=trim($_POST['search']);
  $keys=explode(" ",$search);

  $query="select id,r_name,school_name,eu_email,eu_name,created_date,stage,status from orders where is_opportunity=0 and school_name ='".$search."'  and id!='".$_POST['id']."' order by id asc ";
  $sql=db_query($query);
  
}else if($_POST['type']=='eu_email'){
  $duplicate_name='eu_email';
  $search=trim($_POST['search']);
  $keys=explode("@",$search);
  
  $query="select id,r_name,school_name,eu_email,eu_name,created_date,stage,status from orders where is_opportunity=0 and eu_email ='".$search."'  and id!='".$_POST['id']."' order by id asc ";

  $sql=db_query($query);

} else if(($_POST['type']=='contact' || $_POST['type']=='eu_mobile' || $_POST['type']=='eu_mobile1' || $_POST['type']=='eu_mobile2') && $_POST['search']) {
  $value=trim($_POST['search']);
  $check1='0'.$value;
  $check2='+91'.$value;
  $check3='91'.$value;
  $duplicate_name=$_POST['type'];
  $query="select id,r_name,school_name,eu_email,eu_name,created_date,stage,status,".$duplicate_name." from orders where  is_opportunity=0 and (".$_POST['type']." like '%".$value."%' or  ".$_POST['type']." like '%".$check1."%'  or ".$_POST['type']." like '%".$check2."%' or ".$_POST['type']." like '%".$check3."%') and id!='".$_POST['id']."'";
  $query.=" order by id asc ";
  $sql=db_query($query);

} else {
  // $duplicate_name=ucwords(str_replace('_',' ',$_POST['type']));
  $duplicate_name=$_POST['type'];
  $sql=db_query("select id,r_name,school_name,eu_email,eu_name,created_date,stage,status,".$duplicate_name." from orders where is_opportunity=0 and ".$_POST['type']." like '%".trim($_POST['search'])."%' and id!='".$_POST['id']."' order by id asc");
}
?>
    <!-- Modal content-->
    <form id="myform" class="w-100">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Posssible Duplicate Leads</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
            <div class="modal-body">
            <table id="leads" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" data-toggle="table" data-height="wfheight" data-mobile-responsive="true">
            <thead>
              <tr>
             <th>S.No.</th>
             <th>Partner Name</th>
             <th>School Name</th>
            
             <th><?=$duplicate_name?></th>
             <th>Created Date</th>
             <th>Stage</th>
             <th>Status</th>
            
             </tr>
                                </thead>

                                <tbody>
             <?php $i=1; while($row=db_fetch_array($sql))
             {  @extract($row);?>
            <tr>
            <td><?=$i?></td>
            <td><?=$r_name?></td>
            <td><a href="view_order.php?id=<?=$id?>" target="_blank"><?=$school_name?></a></td>
             
            <td><?=$row[$duplicate_name]?></td>
            <td><?=$created_date?></td>
            <td><?=$stage?></td>
            <td><?php if($status=='Approved')
												{													 
												echo 'Qualified';
												}
												else if($status=='Cancelled')
												{
													echo 'Unqualified';
													
												}
												else if($status=='Pending'){
														echo 'Pending';
                          }else{
                          echo $status;
                        }
															?></td>
              </tr>
                                    <?php $i++; } ?>
                                </tbody>
                            </table>
             </div>
                                    
                                    
                             
                                    
            <!-- <div class="mt-3 text-center">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>   -->
        </form>                           
      </div>
     
	   
    </div>
 
  </div>
  <script>
  $(document).ready(function() {
      //     $('#myTable').DataTable();
        $(document).ready(function() {
            var dataTable = $('#leads').DataTable( {
                "stateSave": true,
                fixedHeader: true,
                // responsive: true,
                dom: 'Bfrtip',
                language: {
                        paginate: {
                            previous: '<i class="fas fa-arrow-left"></i>',
                            next: '<i class="fas fa-arrow-right"></i>'
                        }
                    },
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
                    ],
                lengthMenu: [
                    [ 15, 25, 50, 100,500,1000 ],
                    [ '15', '25', '50','100','500', '1000' ]
                ],
             
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                },
            //"order": [[ 5, "desc" ]],
            columnDefs: [
               { orderable: false, targets: 0 }
            ],

           
        } );
            // Order by the grouping
            $('#leads tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
      });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    
  </script>