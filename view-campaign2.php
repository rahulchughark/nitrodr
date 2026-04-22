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
    padding: 20px;
    border-radius: 15px;
    color: #fff;
    background: #1e1e1e;
    background: linear-gradient(135deg, #1e1e1e, #2a2a2a);
    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    transition: transform .25s, box-shadow .25s;
}

.status-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.25);
}

.box-icon i {
    font-size: 40px;
    opacity: 0.9;
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
.box-total {
    background: linear-gradient(135deg, #007bff, #0056d6);
}

.box-sent {
    background: linear-gradient(135deg, #28a745, #1f7d35);
}

.box-failed {
    background: linear-gradient(135deg, #dc3545, #a71d2a);
}

.status-card {
    cursor: pointer;
    transition: all 0.2s ease;
    opacity: 0.6;
}

.status-card:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.status-card.active {
    opacity: 1;
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    border: 2px solid #0d6efd;
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

<input type="hidden" id="reportType" name="reportType" value="all">
                    <div class="row g-3" id="status-cards">

                        <!-- Total -->
                        <div class="col-md-3">
                            <div class="status-box box-total status-card" data-type="all">
                                <div class="box-icon">
                                    <i class="mdi mdi-account-group-outline"></i>
                                </div>
                                <div class="box-text">
                                    <h6>Total</h6>
                                    <h3 id="total-count">0</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Sent -->
                        <div class="col-md-3">
                            <div class="status-box box-sent status-card" data-type="sent">
                                <div class="box-icon">
                                    <i class="mdi mdi-send-check-outline"></i>
                                </div>
                                <div class="box-text">
                                    <h6>Sent</h6>
                                    <h3 id="sent-count">0</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Failed -->
                        <div class="col-md-3">
                            <div class="status-box box-failed status-card" data-type="failed">
                                <div class="box-icon">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                </div>
                                <div class="box-text">
                                    <h6>Failed</h6>
                                    <h3 id="failed-count">0</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Invalid -->
                        <div class="col-md-3">
                            <div class="status-box box-failed status-card" data-type="invalid">
                                <div class="box-icon">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                </div>
                                <div class="box-text">
                                    <h6>Invalid</h6>
                                    <h3 id="invalid-count">0</h3>
                                </div>
                            </div>
                        </div>

                    </div>


                            <div class="row mt-4">
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
                                <!-- <div class="col-sm-auto d-flex align-items-center">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-primary active">
                                            <input type="radio" name="reportType" value="all" autocomplete="off" checked> Overview (Total)
                                        </label>

                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="reportType" value="sent" autocomplete="off"> Sent
                                        </label>
                                        
                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="reportType" value="failed" autocomplete="off"> Failed
                                        </label>
                                        

                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="reportType" value="invalid" autocomplete="off"> Invalid
                                        </label>
                                    </div>
                                </div> -->
                                 
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
                                        <th>Phone Code</th>
                                        <th>Contact</th>
                                        <th>Attempts</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Timestamp</th>
                                        <!-- <th>Request JSON</th> -->
                                        <!-- <th>Status</th> -->
                                        <!-- <th>Failed At</th> -->
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
    displayLength: 15,
    processing: true,
    serverSide: true,

    language: {
        paginate: {
            previous: '<i class="fas fa-arrow-left"></i>',
            next: '<i class="fas fa-arrow-right"></i>'
        }
    },
    buttons: [],
    ajax: {
        url: 'ajax_campaign_failed_phones_data2.php',
        type: 'post',
        data: function (d) {
            d.mst_id = "<?= $_GET['id']; ?>";
            d.filter = $('#reportType').val();
            // d.filter = $('input[name="reportType"]:checked').val();   // change to failed / sent dynamically if needed
        },
        dataSrc: function (response) {

            $("#sent-count").html(response?.successCount ?? 0);
            $("#failed-count").html(response?.failedCount ?? 0);
            $("#invalid-count").html(response?.invalidCount ?? 0);
            $("#total-count").html(response?.totalCount ?? 0);
            // Page label
            $(".page-label").html(response.page_label ?? '');

            // MUST return table rows
            return response.data ?? [];
        }
    },

    columns: [
        { data: 'sno' },
        { data: 'campaign_id' },
        { data: 'code' },
        { data: 'user_number' },
        {data : 'attempt'},
        { data: 'failed_reason' },
        {
            data: 'status',
            render: function (data) {
                switch (parseInt(data)) {
                    case 0: return '<span class="badge badge-secondary">Pending</span>';
                    case 1: return '<span class="badge badge-success">Sent</span>';
                    case 2: return '<span class="badge badge-danger">Failed</span>';
                    case 3: return '<span class="badge badge-warning">Invalid</span>';
                    case 4: return '<span class="badge badge-dark">Unknown</span>';
                    default: return '<span class="badge badge-light">N/A</span>';
                }
            }
        },
        { data: 'created_at' }
    ],

    columnDefs: [
        { orderable: false, targets: '_all' }
    ]
});


// $('input[name="reportType"]').on('change', function () {
//     table.ajax.reload();
// });
$('#reportType').on('change', function () {
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
    $('.dataTables_wrapper').height(wfheight - 310);
    $("#example23").tableHeadFixer();
});
</script>

<script>
$('.status-card').on('click', function () {

    const type = $(this).data('type');

    // update hidden input
    $('#reportType').val(type).trigger('change');

    // update URL
    const url = new URL(window.location.href);
    url.searchParams.set('reportType', type);
    window.history.pushState({}, '', url);

    // active UI
    $('.status-card').removeClass('active');
    $(this).addClass('active');
});
</script>