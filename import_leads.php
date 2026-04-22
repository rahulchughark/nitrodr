<?php include("includes/include.php"); 
// print_r($_POST);die;
?>

<div class="modal-dialog modal-dialog-centered">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title ">Import</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body">
            Please be careful while uploading file and ensure that all the below condtions are followed.
            <ul>
                <li>Keep column order same as attached csv format</li>
                <li>Avoid Special Characters such as "Apostrophe(')"</li>
            </ul>
            <?php if($_POST['importFor'] == 'partner'){ ?>
                <a href="assets/ICT-DR-Data-Format-csv-for-partner.csv" download>Download CSV Format</a>
           <?php }else if($_POST['type'] == 'Lead'){ ?>
                <a href="assets/ICT-DR-Data-Format-csv-from-business.csv" download>Download CSV Format</a>
            <?php } else { ?>
                <a href="assets/opportunity_import_format.csv" download>Download CSV Format</a>
            <?php } ?>
            <?php if($_POST['importFor'] == 'partner'){ 
                                       $res=db_query("select * from partners where status='Active'");
                ?>
                <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="exampleInputuname">Select Partner</label>
                    <div class="input-group">
                        <select name="reseller" id="reseller" class="form-control" required>
                            <option value="">---Select---</option>
                            <?php while($row=db_fetch_array($res)) { ?>
                                <option value='<?=$row['id']?>'><?=$row['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" id="file-upload-group" style="display: none;">
                    <label for="exampleInputuname">Select CSV File</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="ti-upload"></i></div>
                        <input required type="file" onchange='triggerValidation(this)' name="file" class="form-control" id="exampleInputuname" placeholder="">
                    </div>
                </div>
                <?php }else{ ?>
            <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="exampleInputuname">Select CSV File</label>
                    <div class="input-group">
                        <div class="btn btn-primary px-2 mr-1"><i class="ti-upload"></i></div> 
                        <input required type="file" onchange='triggerValidation(this)' name="file"  id="exampleInputuname" placeholder="">
                    </div>
                    </label>
                </div>
                    <?php } ?>


                <div class="mt-3 text-center">
                <?php if($_POST['importFor'] == 'partner'){ ?>
                        <input type="submit" name="save_partner_csv" value="Save" class="btn btn-primary waves-effect waves-light m-r-10" />
                    <?php }else{ ?>
                        <input type="submit" name="save_csv" value="Save" class="btn btn-primary waves-effect waves-light m-r-10" />
                   <?php } ?>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>


                </div>
            </form>
        </div>


    </div>

</div>
<script>
    document.getElementById('reseller').addEventListener('change', function () {
        var fileUploadGroup = document.getElementById('file-upload-group');
        if (this.value !== "") {
            fileUploadGroup.style.display = 'block';
        } else {
            fileUploadGroup.style.display = 'none';
        }
    });
</script>
<script>
    // var regex = new RegExp("(.*?)\.(csv)$");

    // function triggerValidation(el) {
    //     if (!(regex.test(el.value.toLowerCase()))) {
    //         el.value = '';
    //         alert('Please select correct file format');
    //     }
    // }
</script>