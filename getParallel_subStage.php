<?php include("includes/include.php"); ?>
<?php
$add_Parallelcomm = getSingleresult("select add_Parallelcomm from orders where id=" . $_POST['id']);
if (getSingleresult("select count(id) from sub_stage where name='".$_POST['pstage']."'")) {
    if($_POST['pstage'] == 'Lost to competition'){ ?>
    <td>List of Products</td>
    <td>
    <input type="hidden" id="hidden_parallel_stage" name="parallel_sub_stage">
        <select id="add_Pcomment_dd" name="add_Pcomm" class="form-control" onchange="selectParallel(this.value)">
            <option value="" disabled>--Select--</option>
            <option value=" Citrix" <?= $add_Parallelcomm == 'Citrix' ? 'selected' : '' ?>>Citrix</option>
            <option value="Vmware" <?= $add_Parallelcomm == 'Vmware' ? 'selected' : '' ?>>Vmware</option>
            <option value="Microsoft" <?= $add_Parallelcomm == 'Microsoft' ? 'selected' : '' ?>>Microsoft</option>
            <option value="Terminal Services Plus" <?= $add_Parallelcomm == 'Terminal Services Plus' ? 'selected' : '' ?>>Terminal Services Plus</option>
            <option value="Accops" <?= $add_Parallelcomm == 'Accops' ? 'selected' : '' ?>>Accops</option>
        </select>
    </td>
<?php } }else {
    "Nosub";
    exit;
}

?>
<script>
    function selectParallel(val){
        
        $('#hidden_parallel_stage').val(val);
       
    }
    </script>