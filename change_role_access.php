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
    .role-access-module{overflow: auto;}
</style>

<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="w-100">
    <div class="modal-content">
        <div class="modal-header">

            <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Role Access</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="#" method="post" name="leads">
        <div class="modal-body role-access-module" >
           
			
                <?php
                $exp = explode(',', get_role_access($_POST['pid']));

                foreach (get_tree() as $mod) {
                    if($mod['menu'] == "child"){
                        // $spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        $childSpacing = 'spacing';
                    }else{
                        // $spacing = '';
                        $childSpacing='';
                    }
                    $mod['id'] = intval($mod['id']);
                    echo '<div class="'. $childSpacing .'"><div class="custom-checkbox"><input type="checkbox" id="accesscheck' . $mod['id'] . '"  name="chk[]" value="' . $mod['id'] . '" ' . (in_array($mod['id'], $exp) ? 'checked' : '') . '><label class="checkmark" for="accesscheck' . $mod['id'] . '">' . $mod['name'] . '</label><span class="checkmark"></span> </div></div>';
                }
                ?>
                <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

               

        </div>
		 <div class="modal-footer justify-content-center">
                    <input type="submit" name="role_edit" value="Save" class="btn btn-primary" />
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

        $('.role-access-module').height(wfheight - 315);



        // $('.role-access-module').slimScroll({
        //     color: '#00f',
        //     size: '10px',
        //     height: 'auto',


        // });

    });
</script>