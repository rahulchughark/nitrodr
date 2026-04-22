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
        <div class="modal-body role-access-module">
        
            <?php
            $exp = explode(',',get_user_access($_POST['pid']));

            foreach (get_tree() as $mod) {
                if($mod['menu'] == "child"){
                        // $spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        $childSpacing = 'spacing';
                    }else{
                        // $spacing = '';
                        $childSpacing = '';
                    }
                $mod['id'] = intval($mod['id']);
                echo '<div class="'. $childSpacing .'"><div class="custom-checkbox"><input type="checkbox" id="accesscheck'.$mod['id'].'"  name="chk[]" value="'.$mod['id'].'" '.(in_array($mod['id'],$exp)?'checked':'').'> <label class="checkmark" for="accesscheck'.$mod['id'].'">'.$mod['name'].'</label> <span class="checkmark"></span> </div></div>';
            }
            ?>
            <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />
            <br>
            
        </div>
            <div class="modal-footer justify-content-center">
                <input type="submit" value="Save" name="role_edit" class="btn btn-primary" />
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
    </script>