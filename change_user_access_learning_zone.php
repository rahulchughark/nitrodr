<?php include('includes/include.php');

$_POST['pid'] = intval($_POST['pid']);
//print_r($_POST['pid']);
?>
<div class="modal-dialog modal-dialog-centered">

<div class="w-100">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
		<h4 class="modal-title">User Role Access</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            
        </div>
        <form action="#" method="post" name="leads">
            <div class="modal-body">
        
            <?php
                // if ($_SESSION['sales_manager'] != 1) {
                //     $res = db_query("select u.*,p.name as r_name from users as u left join partners as p on u.team_id=p.id where u.status='Active'");
                // } else {
                //     $res = db_query("select u.*,p.name as r_name from users as u left join partners as p on u.team_id=p.id where p.id in (" . $_SESSION['access'] . ") and status='Active'");
                // }
                $res = db_query("select * from user_type_role where status=1");
                $usrs = getSingleresult("SELECT users_access from learning_zone where id=".$_POST['pid']);
                $usrArr = $usrs != null ? explode(",",$usrs) : '';
                // print_r($usrArr);die;
                ?>

            <!-- </div>
            <div class="row"> -->


                <div class="form-group">
                    <label class="control-label">Users<span class="text-danger">*</span></label>
                    <select name="usersTypes[]" id="usersTypes" class="multiselect form-control" data-live-search="true" multiple>
                    <?php while ($row = db_fetch_array($res)) { ?>
                        <option value="<?= $row['role_code'] ?>" <?= (@in_array($row['role_code'], $usrArr) ? 'selected' : '') ?>> <?= $row['role_type'] ?></option>
                        <!-- <option value='<?= $row['id'] ?>'><?= $row['name'] ?> (<?= $row['r_name'] ?>)</option> -->
                    <?php } ?>
                    </select>
                </div>
                <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />
            <br>
            
        </div>
        <div class="modal-footer justify-content-center border-0">
                <input type="submit" value="Save" name="edit_access" class="btn btn-primary" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>

    </div>
	</div><!--role-access-module-->

</div>

<script>
        $(document).ready(function() {

            var wfheight = $(window).height();

            $('.role-access-module').height(wfheight - 315);



            $('.role-access-module').slimScroll({
                color: '#00f',
                size: '10px',
                height: 'auto',


            });

        });

        $(document).ready(function() {
                $('.multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select User',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });
    </script>