<?php
include('includes/header.php');
include("includes/include.php"); 
include_once('helpers/DataController.php');

 $dataObj = new DataController();

// Sanitize and get 'id' and 'school_id'
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : 0;
 
$isOrderExists = $dataObj->checkOrderExists($school_id, $id);
 $isOrderExists = true;


// Fetch school name if id is present
$school_name = '';
if ($id > 0) {
    $sql = db_query("SELECT school_name FROM orders WHERE id = $id");
    $data = db_fetch_array($sql);
    if ($data) {
        $school_name = $data['school_name'];
    }
}

// Get helpdesk tickets if both id and school_id exist
$tickets_data = [];
// $school_id = 505;
if ($id > 0 && $school_id > 0) {       
    $token = $dataObj->getKMSAuthToken($_SESSION['name'],"MTc1NjM3MzM2OQ==");  
    // $school_id = 505;
    $training_data = $dataObj->getSchoolTraningDetails($token, $school_id,$_SESSION['name']);
}


// Prepare tickets array for table display
$trainings = [];
if (!empty($training_data) && is_array($training_data)) {
    foreach ($training_data as $training) {
        if (!empty($training['ticket_id'])) {
            $trainings[] = [
                'ticket_id' => $training['ticket_id'],
                'title' => $training['additional_information'] ? $training['additional_information'] : 'NA',
                'training_dateTime' => $training['training_dateTime'] ? $training['training_dateTime'] : 'NA',
                'created_date' => $training['created_at'] ? $training['created_at'] : 'NA',
                'query_status_name' => $training['query_status_name'] ? $training['query_status_name'] : 'NA',
                'addedByName' => $training['addedByName'] ? $training['addedByName'] : 'NA',
                'closed_time' => $training['closed_time'] ? $training['closed_time'] : 'NA',
                'closed_by' => $training['closed_by_name'] ? $training['closed_by_name'] : 'NA',
                'data' => $training
            ];
        }
    }
}

// print_r($trainings);
//   exit;
?>


<style>
    .drHelpdesk.modal-xl {
        max-width: 1400px;
    }

.custom-select {
    border: 2px solid #007bff;   /* Blue border */
    border-radius: 6px;
    font-weight: 500;
    padding: 6px 10px;
    background-color: #f8f9ff;   /* Light blue background */
    transition: all 0.2s ease-in-out;
}

.custom-select:focus {
    border-color: #0056b3;
    box-shadow: 0 0 6px rgba(0, 91, 187, 0.4);
    background-color: #fff;
}
.no-data-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px; /* keeps center alignment in modal */
    background: #f9fafb;
}

.no-data-card {
    background: #fff;
    padding: 30px 40px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    display: inline-block;
    max-width: 350px;
}

.no-data-img {
    width: 200px;   /* increase size */
    max-width: 100%;
    opacity: 0.95;
}

.no-data-card h4 {
    margin-top: 15px;
    font-weight: 600;
    color: #333;
}

.no-data-card p {
    color: #666;
    font-size: 14px;
    margin: 5px 0 0;
}
#ticketTabs {
    display: flex !important;               /* Flex container */
    justify-content: flex-start !important; /* Left align tabs */
    align-items: center;
    margin-left: 0;
    overflow-x: auto;                       /* Scroll if too many tabs */
    white-space: nowrap;                     /* Prevent wrapping */
    padding-bottom: 5px;                     /* optional spacing */
}

#ticketTabs::-webkit-scrollbar {
    height: 6px;                             /* thin scrollbar */
}

#ticketTabs::-webkit-scrollbar-thumb {
    background-color: #007bff;              /* blue scrollbar thumb */
    border-radius: 3px;
}

.nav-tabs .nav-link.active {
    color: #0d6efd !important;
    background: #ffffff !important;
    border: 1px solid #dee2e6;
    border-bottom: 2px solid #ffffff;
    font-weight: 600;
    box-shadow: 0 -2px 6px rgba(0,0,0,0.05);
}

.nav-tabs .nav-link {
    margin-right: 6px;
    border-radius: 8px 8px 0 0;
    padding: 8px 16px;
    background: #f1f3f5;
    transition: all 0.2s;
}
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid py-3">
            
            <div class="row">
                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                        <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home > Training</small>
                                            <h4 class="font-size-14 m-0 mt-1">Training</h4>
                                        </div>
                                        <!-- <a href="#" id="addCopy">
                                            <button type="button" class="btn btn-xs  ml-1 waves-effect btn-primary waves-light clonner-page" data-toggle="modal" data-original-title="Copy Lead as New" data-animation="bounce" data-target=".bs-example-modal-task" style="float: right;"><i class="ti-reload" data-toggle="tooltip" data-placement="left" title="" data-original-title="Copy Lead as New"></i></button>
                                        </a> -->

                                    </div>
                                </div>
                                <div class="col-auto">
                                   <?php if(isset($_GET['id'], $_GET['school_id'], $_GET['type']) 
                                     && $_GET['type'] === "opportunity"): ?>
                                        <a href="view_opportunity.php?id=<?= $_GET['id'] ?>"
                                            class="btn1 btn-primary mt-1"                                         
                                            data-animation="bounce" >
                                            Back
                                        </a>

                                    <?php elseif(isset($_GET['id'], $_GET['school_id'], $_GET['type']) 
                                     && $_GET['type'] === "renewal"): ?>
                                        <a href="view_renewal_opportunity.php?id=<?= $_GET['id'] ?>"
                                            class="btn1 btn-primary mt-1"                                         
                                            data-animation="bounce" >
                                            Back
                                        </a>
                                    <?php endif; ?>
                                  
                                </div>
                            </div>

                            

                                <div class="col-12">
                                        <?php if ($isOrderExists && (!empty($trainings) && count($trainings) > 0)): ?>
                                            <div class="table-responsive mt-3">
                                                <table class="table" id="ticketsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>S.no</th>
                                                            <th>Ticket ID</th>
                                                            <th>Subject</th>
                                                            <th>Added By</th>
                                                            <th>Closed By</th>
                                                            <th>Status</th>
                                                            <th>Created On</th>
                                                            <th>Closed On</th>
                                                            <th>View</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($trainings as $training): ?>
                                                            <tr>
                                                                <td><?= $i++ ?></td>
                                                                <td><?= $training['ticket_id'] ?></td>
                                                                <td><?= $training['title'] ?: '--' ?></td>
                                                                <td><?= $training['training_dateTime'] ?></td>
                                                                <td><?= $training['closed_by'] ?></td>
                                                                <td><?= $training['query_status_name'] ?></td>
                                                                <td><?= $training['created_date'] ?></td>
                                                                <td><?= date('d-M-Y H:i:s',strtotime($training['closed_time'])) ?></td>
                                                                <td>
                                                                    <button style="background:#44a2d2" class="btn btn-sm btn-info view-ticket" 
                                                                            data-json='<?= htmlspecialchars(json_encode($training), ENT_QUOTES) ?>'>
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center my-5">
                                                <img src="images/no-data.png" alt="No Data" style="max-width: 250px; width: 100%; height: auto;">
                                                <h4 class="mt-3 text-muted">No Training Found</h4>
                                            </div>
                                        <?php endif; ?>
                                    </div>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



  <?php include 'includes/footer.php'?>

        <div id="viewDoc" class="modal fade" role="dialog">
        <div class="modal-dialog drHelpdesk modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button 
                        type="button" 
                        class="close" 
                        data-dismiss="modal"
                    >&times;</button>
                    <h4 class="modal-title"><?= $school_name ?></h4>
                </div>

                <div class="modal-body communicationHTML"></div>
            </div>
        </div>
        </div>

<!-- Attachment Preview Modal -->
<div class="modal fade" id="attachmentPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Preview</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center" id="attachmentPreviewBody">
                <!-- dynamic content -->
            </div>

        </div>
    </div>
</div>
<script>
$(document).on("click", ".view-ticket", function () {
    let ticketData = $(this).data("json");

    $.post("view-training.php", { ticket: ticketData }, function (response) {
        console.log("Response:", response);

        // Inject the response into the modal body
        $(".communicationHTML").html(response);

        // Then show the modal
        $("#viewDoc").modal("show"); 
    });
});
</script>

<script>
$(document).ready(function() {
    $('#ticketsTable').DataTable({
        dom: 'Bfrtip',
        "displayLength": 15,
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        buttons: [
            <?php if($_SESSION['download_status'] == 1){ ?>
            'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            <?php } else { ?>
            'pageLength'
            <?php } ?>
        ],
        lengthMenu: [
            [5, 15, 25, 50, 100, 500, 1000],
            ['5', '15', '25', '50', '100', '500', '1000']
        ],
        "stateSave": true,
        "processing": false,  // Set to false for client-side processing
        "serverSide": false,  // Set to false for client-side processing
        "order": [
            [1, "desc"]  // Order by Ticket ID column (index 1)
        ],
        columnDefs: [
            {
                orderable: false,
                targets: [0, 6]  // Disable ordering on S.no and View columns
            },
            {
                type: 'date',
                targets: [5]  // Specify date type for Created On column
            }
        ]
    });
});
</script>

<script>
function openAttachmentModal(url, type) {
    
   

    let html = '';

    if (type === 'image') {
        html = `<img src="${url}" class="img-fluid rounded">`;
    }
    else if (type === 'video') {
        html = `
            <video controls autoplay class="w-100">
                <source src="${url}">
            </video>`;
    }

    $('#attachmentPreviewBody').html(html);
    $('#attachmentPreviewModal').modal('show');
}
</script>