<?php

include("includes/include.php");

$d_from = $_POST['d_from'];
$d_to = $_POST['d_to'];
$partner = $_POST['partner'];
$users = $_POST['users'];
$cat_type = $_POST['cat_type'];
$contains = $_POST['contains'];
$duration = $_POST['duration'];

?>


<div class="table-responsive">
    <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th data-sortable="true">S.No.</th>
                <th data-sortable="true">Lead Owner</th>
                <th data-sortable="true">Company Name</th>
                <th data-sortable="true">Lead Type</th>
                <th data-sortable="true">Quantity</th>
                <th data-sortable="true">Stage</th>
                <th data-sortable="true">Closed Date</th>
                <th data-sortable="true">Created Date</th>
                <th data-sortable="true">Last Updated on</th>
            </tr>
        </thead>
</div>
</table>


<script>
    $('#leads').DataTable({
       // "stateSave": true,
        dom: 'Bfrtip',
        "displayLength": 25,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
        ],
        lengthMenu: [
            [5, 15, 25, 50, 100, 500, 1000],
            ['5', '15', '25', '50', '100', '500', '1000']
        ],
        "processing": true,
        "serverSide": true,
       // "retrieve": true,
        //"paging": false,
        "ajax": {
            url: "leadStatus_ajax.php", // json datasource
            type: "post", // method  , by default get
            data: function(d) {
                d.d_from = "<?= $d_from ?>";
                d.d_to = "<?= $d_to ?>";
                d.partner = "<?= $partner ?>";
                d.users = "<?= $users ?>";
                d.cat_type = "<?= $cat_type ?>";
                d.duration = "<?= $duration ?>";
                d.contains = "<?= $contains ?>"
            },

            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                $("#leads_processing").css("display", "none");

            }
        },
        columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
        'columns': [{
                data: 'serial'
            },
            {
                data: 'submitted_by'
            },
            {
                data: 'company_name'
            },
            {
                data: 'lead_type'
            },
            {
                data: 'quantity'
            },
            {
                data: 'stage'
            },
            {
                data: 'closed_date'
            },
            {
                data: 'created_date'
            },
            {
                data: 'updated_date'
            },


        ],
    });



    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.dataTables_wrapper').height(wfheight - 320);
        $("#leads").tableHeadFixer();

    });

    function clear_search() {
        window.location = 'lead_update_status.php';
    }
</script>