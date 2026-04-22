<?php include("includes/include.php"); ?>


<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Follow-up Details
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="table-responsive" id="MyDiv">

                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-sortable="true">S.No.</th>
                            <th data-sortable="true">Task Type</th>
                            <th data-sortable="true">Account Name</th>
                            <th data-sortable="true">Date</th>
                            <th data-sortable="true">Time</th>
                            <th data-sortable="true">Status</th>
                        </tr>
                    </thead>


                </table>
            </div>
        </div>


    </div>
</div>

<script>
    $('#leads').DataTable({
        dom: 'Bfrtip',
        "displayLength": 15,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
        ],
        lengthMenu: [
            [5, 15, 25, 50, 100, 500, 1000],
            ['5', '15', '25', '50', '100', '500', '1000']
        ],

        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "get_followup.php", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                $("#leads_processing").css("display", "none");

            }
        },
        "order": [
            [1, "desc"]
        ],
        columnDefs: [{
            orderable: false,
            targets: 0
        }],

        'columns': [{
                data: 'id'
            },
            {
                data: 'task'
            },
            {
                data: 'account_name'
            },
            {
                data: 'date'
            },
            {
                data: 'time'
            },
            {
                data: 'status'
            },


        ]
    });
</script>