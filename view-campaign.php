<?php 
include('includes/header.php');
include_once('helpers/DataController.php');
admin_page();


$dataObj = new DataController;

?>

<style>
    .mdi {
        font-size: 16px;
    }
    .table thead th {
        vertical-align: middle;
    }

    /* .table tbody td:first-child {
        text-align: center;
    } */

    .inner-items {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .inner-items p {
        white-space: nowrap;
        margin-bottom: 0;
    }

    .inner-items p .mdi {
        font-size: 16px;
        line-height: 1;
    }

    .inner-items .form-fields {
        display: flex;
        gap: 5px;
    }
    
    .inner-items .form-control {
        width: 140px;
    }

    .inner-items p, .inner-items .form-control:not(textarea), .inner-items .form-fields .btn {
        height: 24px;
    }

    .modal-body {
        min-height: 300px;
    }

    #uploadModal {
        background: rgba(0, 0, 0, .32);
        backdrop-filter: blur(5px);
    }


    .status-card {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 12px;
    color: #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: transform 0.3s;
}

.status-card:hover {
    transform: translateY(-5px);
}

.status-card .card-icon {
    font-size: 50px;
    margin-right: 15px;
    opacity: 0.8;
}

.status-card .card-content h6 {
    margin: 0;
    font-weight: 500;
    font-size: 16px;
    opacity: 0.9;
}

.status-card .card-content h3 {
    margin: 5px 0 0 0;
    font-size: 32px;
    font-weight: 700;
}

/* Success Gradient */
.success-card {
    background: linear-gradient(135deg, #28a745, #7bd68b);
}

/* Failed Gradient */
.failed-card {
    background: linear-gradient(135deg, #dc3545, #f88b91);
}

.status-box {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 15px 20px;
    border-radius: 6px;
    box-shadow: 0 0px 5px rgb(0 0 0 / 10%);
    transition: transform .25s, box-shadow .25s;
    border: 1px solid #d9d9d9;
}

.status-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
}

.status-box .box-text {
    flex: 1 1 100%;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.status-box .box-icon {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgb(21 93 252 / 13%);
    color: #155DFC;
    border-radius: 50%;
}

.box-icon i {
    font-size: 34px;
}

.box-sent .box-icon {
    background: rgb(0 166 62 / 10%);
    color: #00A63E;
}
.box-failed .box-icon {
    background: rgb(231 0 11 / 13%);
    color: #E7000B;
}


.box-text h6 {
    margin: 0;
    font-size: 14px;
    opacity: 0.9;
}

.box-text h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

/* Colors */
/* .box-total {
    background: linear-gradient(135deg, #007bff, #0056d6);
}

.box-sent {
    background: linear-gradient(135deg, #28a745, #1f7d35);
}

.box-failed {
    background: linear-gradient(135deg, #dc3545, #a71d2a);
} */

.tab-btn-group {
    border: 1px solid #86C9D0;
    border-radius: 10px;
}

.btn.btn-outline-primary {
    border-color: #86C9D0;
    color: #86C9D0;
}

.btn-outline-primary:not(:disabled):not(.disabled).active, .btn-outline-primary:not(:disabled):not(.disabled):active, .show>.btn-outline-primary.dropdown-toggle, .btn-outline-primary:hover {
    background-color: #86C9D0;
    border-color: #86C9D0;
    color: #212529;
}

</style>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">


                    <div class="row g-3" id="status-cards">

                        <!-- Total -->
                        <div class="col-md-4">
                            <div class="status-box box-total">
                                <div class="box-text">
                                    <h6>Total</h6>
                                    <h3 id="total-count">0</h3>
                                </div>
                                <div class="box-icon">
                                    <i class="mdi mdi-account-group-outline"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Sent -->
                        <div class="col-md-4">
                            <div class="status-box box-sent">
                                <div class="box-text">
                                    <h6>Sent</h6>
                                    <h3 id="sent-count">0</h3>
                                </div>
                                <div class="box-icon">
                                    <i class="mdi mdi-send-check-outline"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Failed -->
                        <div class="col-md-4">
                            <div class="status-box box-failed">
                                <div class="box-text">
                                    <h6>Failed</h6>
                                    <h3 id="failed-count">0</h3>
                                </div>
                                <div class="box-icon">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                            <div class="row mt-3">
                                <div class="col-sm">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home > Campaign Report > <span class="page-label"></span> </small>
                                            <h4 class="font-size-14 m-0 mt-1">Campaign Report > <span class="page-label"></span></h4>
                                        </div>
                                    </div>
                                </div>

                                    <!-- Here Radio Button -->
                                <div class="col-sm-auto d-flex align-items-center">
                                    <div class="btn-group btn-group-toggle tab-btn-group" data-toggle="buttons">
                                        <label class="btn btn-outline-primary active">
                                            <input type="radio" name="reportType" value="overview" autocomplete="off" checked> Overview (Total)
                                        </label>
                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="reportType" value="failed" autocomplete="off"> Failed
                                        </label>
                                    </div>
                                </div>
                                 
                            </div>
                            <div class="table-responsive">
                               <table id="leads" 
                                       class="table display nowrap table-striped" 
                                       data-height="wfheight" 
                                       data-mobile-responsive="true" 
                                       cellspacing="0" 
                                       width="100%">
                                    <thead>
                                        <tr>
                                        <th>S.no</th>
                                        <th>Campaign ID</th>
                                        <th>User Number</th>
                                        <th>User Name</th>
                                        <th>Failed Reason</th>
                                        <!-- <th>Request JSON</th> -->
                                        <!-- <th>Status</th> -->
                                        <th>Failed At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <!-- DataTables will fill this body dynamically via AJAX -->
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <!-- Modal -->
        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>

        <?php include('includes/footer.php') ?>
<script>

var table = $('#leads').DataTable({
    dom: 'Bfrtip',
    "displayLength": 15,
    processing: true,
    serverSide: true,

    language: {
        paginate: {
            previous: '<i class="fas fa-arrow-left"></i>',
            next: '<i class="fas fa-arrow-right"></i>'
        }
    },

    buttons: [
        // 'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength',
    ],

    ajax: { 
        url: 'ajax_campaign_failed_phones_data.php', 
        type: 'post',
        data: function (d) {
            d.campaign_id = "<?= $_GET['campaign_id']; ?>"
            d.type = $('input[name="reportType"]:checked').val();
        },
        dataSrc: function(response) {
        
        const total = response?.totalCount || 0;
        const failed = response?.latestFailedCount || 0;
        const sent = total - failed;

        // update counts
        $("#total-count").html(total);
        $("#sent-count").html(sent < 0 ? 0 : sent);   // ensure no negative value
        $("#failed-count").html(failed);
        $(".page-label").html(response?.page_label);

        // MUST return table rows
        return response.data ?? [];

        }
    },

    columns: [
        // { data: 'sno' },            // 1
        { data: 'sno' },             // 2 (failed row id)
        { data: 'campaign_id' },    // 3
        { data: 'user_number' },    // 4
        { data: 'user_name' },      // 5
        { data: 'failed_reason' },  // 6
        // { data: 'request_json' },   // 7
        // { data: 'status' },         // 8
        { data: 'failedAt' }      // 9
    ],

    columnDefs: [
        { orderable: false, targets: '_all' }
    ]
});


$('input[name="reportType"]').on('change', function () {
    table.ajax.reload();
});

// Fix previous button going back to last page
$('#example23_previous').on('click', function () {
    if (table.page() === 0) {
        table.page('last').draw('page');
    }
});


// Reload table (optional function)
function refreshDatatable() {
    $('#example23').DataTable().ajax.reload(null, false);
}

</script>


<script>
$(document).ready(function () {
    var wfheight = $(window).height();
    $('.dataTables_wrapper').height(wfheight - 320);
    $("#example23").tableHeadFixer();
});
</script>
