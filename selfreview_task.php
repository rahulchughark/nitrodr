<?php include("includes/include.php"); ?>
<div class="modal-dialog modal-lg review_dr">
<?php 
?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title text-white">To DO Task</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <div class="table-responsive">
                                    <table id="review_task" class="display nowrap table table-hover table-striped table-bordered font-14" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                               <tr>
                                                <th>S.No.</th>
                                                <th>Submitted by</th>
                                                <th>Lead Type</th>
                                                <th>Company Name</th>
                                                <th>Tile</th>
                                                <th>Assigned To</th>
                                                <th>Date of Submission</th>
											    <th>Status</th>
                                                <th>Update Status</th>
                                          
                                                </tr>
                                        </thead>
                                         
                                         
                                    </table>
                                </div>
      </div>
     
	   
    </div>
 
  </div>
 
  <script>
    $(document).ready(function() {
      //     $('#myTable').DataTable();
        $(document).ready(function() {
            var dataTable = $('#review_task').DataTable( {
                "stateSave": true,
		dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"get_selfreview_task.php", // json datasource
                type: "post",  // method  , by default get
				data:function ( d ) {
                d.d_from = "<?=$_GET['d_from']?>";
                d.d_to = "<?=$_GET['d_to']?>";
                // d.custom = $('#myInput').val();
                // etc
					},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#review_task").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                }
            },
            "order": [[ 6, "desc" ]],
            columnDefs: [
               { orderable: false, targets: 0 }
            ],

           'columns': [
                 { data: 'id' },
                 { data: 'r_user' },
                   { data: 'lead_type' },
                   { data: 'company_name' },
                   { data: 'title' },
                   { data: 'user' },
                   {data:'created_date'},
                 {data:'status'},
                   {data:'action'},  
                
              ]
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
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

function complete_srt(id)
{
    if(id !=''){
   
   swal({   
       title: "Are you sure?",   
       text: "You want mark this task as complete.",   
       type: "warning",   
       showCancelButton: true,   
       confirmButtonColor: "#DD6B55",   
       confirmButtonText: "Yes!",   
       cancelButtonText: "No, Cancel!",   
       closeOnConfirm: false,   
       closeOnCancel: false 
   }, function(isConfirm){  
          
       if(isConfirm)    
         {

           $.ajax({
       type :'post',
       url : 'change_selfreview_task.php',
       data :{rev_id:id},
       success:function(res){
          // alert(res)
           if(res == 'success'){

           
            swal({title:"Done!",  text:"Task marked as complete successfully.",  type:"success"});
            $('#review_task').DataTable().ajax.reload();
               
           }
       }
       });
               
          
       } else {     
           swal("Cancelled", "No action done!", "error");   
           
       } 
  
   });

}
}

  </script>