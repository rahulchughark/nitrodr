<?php

include("includes/include.php");
admin_protect();
$d_from = $_POST['d_from'];
$d_to = $_POST['d_to'];
$d_type = $_POST['date_type'];
$col_data = $_POST['check_list'];  //checkbox data
$check_list = implode("','", $col_data);
$campaign = intval($_POST['campaign']);
$_POST['validation_type'] = $_POST['validation_type'];
//print_r($d_type);
?>
<div class="table-responsive">
    <table id="leads" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
        <thead>
            <tr>
                <?php

                $sql_select = personalReport_tableLabels('orderPartner_pivot', $check_list);
                foreach ($sql_select as $row) {
                    echo '<th>' . $row['field_label'] . '</th>';
                }
                ?>
            </tr>
        </thead>
    </table>
</div>


<?php

$check_label = implode(',', $_POST['check_list']);
$industry_arr = $_POST['industry'] ? implode("','", $_POST['industry']) : '';
$region_arr = $_POST['region'] ? implode("','", $_POST['region']) : '';
$city_arr = $_POST['city'] ? implode("','", $_POST['city']) : '';
$association_arr = $_POST['association_name'] ? implode("','", $_POST['association_name']) : '';
?>


<script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
				
        
				
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],

            "processing": true,
            "serverSide": true,
            "retrieve": true,
            "ajax": {
                "url": "personalRep_Partnerajax.php", // json datasource
                "type": "post", // method  , by default get
                "data": function(d) {
                    d.d_from = "<?= $_POST['d_from'] ?>";
                    d.d_to = "<?= $_POST['d_to'] ?>";
                    d.d_type = "<?= $_POST['date_type']; ?>";
                    d.check_label = "<?= $check_label ?>";
                    d.col_data = "<?= $_POST['check_list'] ?>";
                    d.industry = "<?= $industry_arr ?>";
                    d.region = "<?= $region_arr ?>";
                    d.city = "<?= $city_arr ?>";
                    d.campaign = "<?= $_POST['campaign'] ?>";
                    d.product = "<?= intval($_POST['product']) ?>";
                    d.product_type = "<?= intval($_POST['product_type']) ?>";
                    d.association_name = "<?= $association_arr  ?>";
                    d.validation_type = '<?= $_POST['validation_type'] ?>';
                },
                "error": function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");

                },

                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            },
        });
   

    function clear_search() {
        window.location = 'personalReport_partner.php';
    }
    $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);				
				$("#leads").tableHeadFixer(); 

            });

</script>