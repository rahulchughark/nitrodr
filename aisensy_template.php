<?php
include('includes/header.php');

admin_page();

$projectId = getSensyAiCredentials('projectId');
$projectApiPwd = getSensyAiCredentials('pswdKey');


?>
<style>
    select option:disabled {
        color: red;       
        font-weight: 600;   /* optional */
    }
</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- ====================== AiSensy Templates ====================== -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="m-0">AiSensy Templates </h4>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTemplateModal">
                                <i class="ti-plus"></i> Create Template
                            </button>
                            </div>
                            <?php
                            // ------------------------- cURL Request -------------------------
                            $curl = curl_init();

                            curl_setopt_array($curl, [
                                CURLOPT_URL => "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_HTTPHEADER => [
                                    "Accept: application/json", 
                                    "X-AiSensy-Project-API-Pwd: {$projectApiPwd}"
                                ],
                            ]);

                            $response = curl_exec($curl);
                            $err = curl_error($curl);
                            curl_close($curl);

                            if ($err) {
                                echo "<div class='alert alert-danger'>API Error: $err</div>";
                            } else {
                                $data = json_decode($response, true);

                                if (!empty($data['template'])) {
                                    echo "<div class='table-responsive'>
                                            <table id='aiSensyTable' class='table table-bordered table-striped display nowrap' width='100%'>
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Name</th>
                                                        <th>Label</th>
                                                        <th>Status</th>
                                                        <th>Type</th>
                                                        <th>Language</th>
                                                        <th>Parameters</th>
                                                        <th>Rejected Reason</th>
                                                        <th>Created At</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>";
                                                // <th>Action</th>
                                    $i = 1;
                                   foreach ($data['template'] as $template) {

                                   

                                                        $createdAt = !empty($template['created_at'])
                                                            ? date('Y-m-d H:i:s', $template['created_at'] / 1000)
                                                            : '-';

                                                        echo "<tr>
                                                                <td>{$i}</td>
                                                                <td>{$template['name']}</td>
                                                                <td>{$template['label']}</td>
                                                                <td>{$template['status']}</td>
                                                                <td>{$template['type']}</td>
                                                                <td>{$template['language']}</td>
                                                                <td>{$template['total_parameters']}</td>
                                                                <td>{$template['rejected_reason']}</td>
                                                                <td>{$createdAt}</td>
                                                                
                                                            </tr>";

                                                        $i++;
                                                    }
                                                    // <td>
                                                    //                 <button type='button' class='btn btn-danger btn-sm delete-template'
                                                    //                 data-name='{$template['name']}' 
                                                    //                 data-id='{$template['id']}'>
                                                    //                     <i class='ti-trash'></i>
                                                    //                 </button>
                                                    //             </td>
                                    echo "</tbody></table></div>";
                                } else {
                                    echo "<div class='alert alert-info'>No templates found.</div>";
                                }
                            }
                            ?>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div> <!-- col-md-12 -->
            </div> <!-- row -->

        </div> <!-- container-fluid -->
    </div>
</div>

<div class="modal fade" id="createTemplateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" id="createTemplateForm">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Create AiSensy Template</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Label</label>
                        <input type="text" name="label" class="form-control" placeholder="Eg: Broadcast template label">
                        <small class="text-danger error-label"></small>
                    </div>

                    <div class="form-group">
                        <label>Template Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Eg: Broadcast-Template">
                        <small class="text-danger error-name"></small>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="">Select Category</option>
                            <option value="MARKETING">MARKETING</option>
                            <option disabled value="UTILITY">UTILITY</option>
                            <option disabled value="AUTHENTICATION">AUTHENTICATION</option>
                        </select>
                        <small class="text-danger error-category"></small>
                    </div>

                    <div class="form-group">
                        <label>Language</label>
                        <!-- <input type="text" name="language" class="form-control" value="English"> -->
                         <input type="text" name="language" class="form-control" value="English" readonly>
                    </div>

                    <div class="form-group">
                        <label>Message Text</label>
                        <textarea name="text" class="form-control" rows="3" placeholder="Eg: Announcing {{1}} – The Broadcast Channel for {{2}}"></textarea>
                        <small class="text-danger error-text"></small>
                    </div>

                    <div class="form-group">
                        <label>Sample Text</label>
                        <textarea name="sample_text" class="form-control" rows="3" placeholder="Eg: Announcing TechED 360 – The Broadcast Channel for Education Leaders"></textarea>
                        <small class="text-danger error-sample"></small>
                    </div>

                    <input type="hidden" name="type" value="TEXT">
                    <input type="hidden" name="message_action_type" value="All">

                </div>

                <div class="modal-footer">
                    <button type="submit" name="create_template" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </form>
    </div>
</div>


<?php include('includes/footer.php'); ?>

<script>
    $(document).ready(function() {
        $('#aiSensyTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'],
            lengthMenu: [[15, 25, 50, 100], ['15', '25', '50', '100']],
            displayLength: 15,
            columnDefs: [
                { orderable: false, targets: [0, 9] } // Disable sorting on S.No. and Action
            ],
            language: {
                paginate: {
                    previous: '<i class="fas fa-arrow-left"></i>',
                    next: '<i class="fas fa-arrow-right"></i>'
                }
            }
        });
    });
</script>
<script>
$('#createTemplateForm').on('submit', function (e) {
    e.preventDefault();

    let formData = $(this).serialize() 
        + '&create_template=true'
        + '&projectId=<?php echo $projectId; ?>'
        + '&projectApiPwd=<?php echo $projectApiPwd; ?>';

    $.ajax({
        url: 'ajax_data_update.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function () {
            $('#createTemplateForm button[type="submit"]')
                .prop('disabled', true)
                .text('Creating...');
        },
        success: function (res) {
            if (res.status) {
                toastr.success('Template created successfully');
                $('#createTemplateModal').modal('hide');
                $('#createTemplateForm')[0].reset();
            } else {
                toastr.error(res.message || 'Something went wrong');
            }
            location.reload();
        },
        error: function () {
            toastr.error('Server error. Please try again.');
        },
        complete: function () {
            $('#createTemplateForm button[type="submit"]')
                .prop('disabled', false)
                .text('Create');
        }
    });
});
</script>
<script>
    let typingIntervals = [];

    function startTyping(el, texts, speed = 70, pause = 1200) {
        let textIndex = 0;
        let charIndex = 0;
        el.attr('placeholder', '');

        const interval = setInterval(() => {
            if (charIndex < texts[textIndex].length) {
                el.attr('placeholder', texts[textIndex].substring(0, charIndex + 1));
                charIndex++;
            } else {
                clearInterval(interval);
                setTimeout(() => {
                    charIndex = 0;
                    textIndex = (textIndex + 1) % texts.length;
                    el.attr('placeholder', '');
                    startTyping(el, texts, speed, pause);
                }, pause);
            }
        }, speed);

        typingIntervals.push(interval);
    }

    function stopTyping() {
        typingIntervals.forEach(clearInterval);
        typingIntervals = [];
    }

    $('#createTemplateModal').on('shown.bs.modal', function () {

        stopTyping(); // safety reset

        startTyping(
            $('input[name="label"]'),
            [
                'Eg: Broadcast template label',
                'Eg: TechED Marketing Template'
            ]
        );

        startTyping(
            $('input[name="name"]'),
            [
                'Eg: Broadcast-Template',
                'Eg: TechED_360_Announcement'
            ]
        );

        startTyping(
            $('textarea[name="text"]'),
            [
                'Eg: Announcing {{1}} – The Broadcast Channel for {{2}}',
                'Eg: Join {{1}} for the latest education insights'
            ]
        );

        startTyping(
            $('textarea[name="sample_text"]'),
            [
                'Eg: Announcing TechED 360 – The Broadcast Channel for Education Leaders',
                'Eg: Join TechED 360 for the latest education insights'
            ]
        );
    });

    $('#createTemplateModal').on('hide.bs.modal', function () {
        stopTyping(); // STOP when modal closes
    });

    $(document).on('click', '.delete-template', function() {
        let templateName = $(this).data('name');
        let templateId = $(this).data('id');
        let btn = $(this);

        swal({
            title: "Are you sure?",
            text: "You want to delete this template (" + templateName + ")?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: 'ajax_data_update.php',
                    type: 'POST',
                    data: {
                        delete_template: true,
                        template_id: templateId,
                        projectId: '<?php echo $projectId; ?>',
                        projectApiPwd: '<?php echo $projectApiPwd; ?>'
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status) {
                            swal("Deleted!", "Template has been deleted.", "success");
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            swal("Error!", res.message || "Failed to delete template", "error");
                        }
                    },
                    error: function() {
                        swal("Error!", "Server error. Please try again.", "error");
                    }
                });
            }
        });
    });
</script>

