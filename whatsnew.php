
                <!-- ============================================================== -->
                <!-- Start Page Content -->

        <div class="modal-dialog modal-lg" >

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header ">
                      <h5 class="modal-title " id="exampleModalLongTitle">Whats new</h5>
                     
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>


                  <div class="modal-body">
                   
                    <div class="row">
                    <div class="col-12">
                      
                        <div class="card">
                            <div class="card-body">
                            

                             <h4 class="card-title">Add </h4>                                
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                     <form  name="whatsnewform" method="post" id="whatsnewform"> 
                                        <tr>
                                            <td width="20%">Title</td>
                                            <td>
                                                <input type="title" name="title" id="title" value="" class="form-control" onkeyup="validateFields()">
                                            </td>
                                        </tr>
                                        
                                        
                                        <tr>
                                            <td width="20%">Description</td>
                                            <td>
                                                <textarea class="form-control" name="description" id="description" rows="10" onkeyup="validateFields()"></textarea>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td colspan=2 ><button type="submit" name="submit" value="submit" id="whatnewbutton" class="btn btn-primary">Save</button>
                                           <!--  <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse">Back</button> -->
                                            
                                            </td>
                                        </tr>   
                                        </form>                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>

                    
                                                
            <div class="modal-footer">
                
             <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
            
          </div>  


          </div>
         
           
        </div>
     
      </div>

<script type="text/javascript">

    function validateFields(){
        title = $('#title').val();
        desc = $('#description').val();

        if(title !='' && desc !=''){
            $('#whatnewbutton').prop('disabled', false);
        }else{
            $('#whatnewbutton').prop('disabled', true);
        }
    }
    
    $('document').ready(function(){
         $('#whatnewbutton').prop('disabled', true);
         $('#whatnewbutton').click(function(){
             title = $('#title').val();
             desc = $('#description').val();
            
               $.ajax({
                    type :'post',
                    url: 'whatsnew_add.php',
                    data :{title : title,description:desc},
                    success : function(res){
                      
                        if(res){   
                            swal({title:"Done!",  text:"Whats new added successfully.",  type:"success"}, function() {
                                       window.location = "whatsnew_list.php";
                                });
                        }else{
                             swal({title:"Error!",  text:"Unable to insert..",  type:"error"}, function() {
                                    window.location = "whatsnew_list.php";
                                });

                        }
                    }

               });  

            });


    });


</script>

   
