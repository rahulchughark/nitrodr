<?php include('includes/include.php');

$_POST['pid'] = intval($_POST['pid']);
//print_r($_POST['pid']);
?>
<style>
    .role-access-module .checkmark {
        margin-left: 6px;
        line-height: 1;
    }

    .role-access-module .checkmark [type="checkbox"]:checked {
        position: absolute;
        opacity: 1;
        left: 10px;
    }
</style>
<div class="modal-dialog modal-dialog-centered modal-lg">

    <div class="w-100">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">Role Permission</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="#" method="post" name="leads">
            <div class="modal-body role-access-module">
                
                    <?php
                    $exp = get_role_permission($_POST['pid']);
                    $data = db_fetch_array($exp);
                    ?>

                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck1" name="edit_log" value="1" <?= (($data['edit_log'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck1">Edit Log</label><span class="checkmark"></span>
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck2" name="edit_lead" value="1" <?= (($data['edit_lead'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck2">Edit Lead</label><span class="checkmark"></span> 
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck3" name="edit_stage" value="1" <?= (($data['edit_stage'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck3">Edit Stage</label><span class="checkmark"></span> 
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck4" name="edit_date" value="1" <?= (($data['edit_date'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck4">Edit Close Date</label><span class="checkmark"></span> 
                    </div>

                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck5" name="edit_ownership" value="1" <?= (($data['edit_ownership'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck5">Edit Ownership</label><span class="checkmark"></span> 
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck6" name="edit_status" value="1" <?= (($data['edit_status'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck6">Edit Status</label><span class="checkmark"></span> 
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck7" name="edit_review_log" value="1" <?= (($data['edit_review_log'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck7">Edit Review Log</label><span class="checkmark"></span> 
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="accesscheck8" name="edit_product" value="1" <?= (($data['edit_product'] == 1) ? 'checked' : '') ?>><label class="checkmark" for="accesscheck8">Edit Product</label><span class="checkmark"></span> 
                    </div>

                    <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

                    <input type="hidden" name="permission_edit" value="<?= $_POST['pid'] ?>" />
                   
                 
            </div>
                <div class="modal-footer justify-content-center">
                    <input type="submit" name="save" value="Save" class="btn btn-primary" />
                    <!-- <button type="submit" class="btn btn-primary" name="save">Save </button> -->
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                    </div>
                </form>

        </div>
    </div>
    <!--role-access-module-->

</div>

<script>
    $(document).ready(function() {

        var wfheight = $(window).height();

        $('.role-access-module').height(wfheight - 415);



        $('.role-access-module').slimScroll({
            color: '#00f',
            size: '10px',
            height: 'auto',


        });

    });
</script>