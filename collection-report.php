<?php 

include('includes/header.php');
include_once('helpers/DataController.php');
admin_page();

$dataObj = new DataController;


$collectionData = $dataObj->getCollectionData();
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
        z-index: 9999999;
    }


    /* Chrome, Safari, Edge, Opera */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
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
                            <div class="row">
                                <div class="col-sm">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Collection Report  </small>
                                            <h4 class="font-size-14 m-0 mt-1">Collection Report</h4>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-sm-auto pt-2 pt-sm-0">
                                    <div class="" role="group">
                                   
                                  
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" id="search-form" name="search">
                                                    <div class="row">
                                                            
                                                         <div class="form-group col-md-6 col-xl-3">
                                                            <select name="dtype" class="form-control" id="multiselect_state">
                                                                <option value="">Select Type</option>
                                                                <option value="fresh" <?= (isset($_GET['dtype']) && $_GET['dtype'] == 'fresh') ? 'selected' : '' ?>>Fresh</option>
                                                                <option value="renewal" <?= (isset($_GET['dtype']) && $_GET['dtype'] == 'renewal') ? 'selected' : '' ?>>Renewal</option>
                                                            </select>
                                                        </div>

                                                      
                                                             <!-- Financial Year -->
                                                            <div class="form-group col-md-6 col-xl-3">
                                                                <select name="financial_year" class="form-control" id="financial_year">
                                                                    <option value="">Select Financial Year</option>
                                                                    <?php 
                                                                        $startYear = 2023;
                                                                        $currentYear = date('Y');
                                                                        $selectedFY = isset($_GET['financial_year']) ? $_GET['financial_year'] : '';

                                                                        // Determine the current financial year upper limit
                                                                        $endYear = (date('n') >= 4) ? $currentYear + 1 : $currentYear;

                                                                        // Generate options from 2023 to current FY
                                                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                                                            $nextYear = $year + 1;
                                                                            $value = "{$year}-{$nextYear}";
                                                                            $selected = ($selectedFY == $value) ? 'selected' : '';
                                                                            echo "<option value='{$value}' {$selected}>{$year}-{$nextYear}</option>";
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                                  
                                                        
                                                        <div class="col-md-3 col-xl-2">
                                                            <button type="submit" class="btn btn-primary font-14"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>

                                                        <?php
                                                        $month = date('n'); // Numeric month (1-12)

                                                        if ($month >= 4) {
                                                            // From April to December → FY starts this year
                                                            $fyStart = date('Y');
                                                            $fyEnd = $fyStart + 1;
                                                        } else {
                                                            // From January to March → FY started last year
                                                            $fyEnd = date('Y');
                                                            $fyStart = $fyEnd - 1;
                                                        }
                                                        ?>

                                                        <b class="text-danger">
                                                            Note: If no financial year filter is selected, the system defaults to the current financial year (<?= $fyStart ?>–<?= $fyEnd ?>).
                                                        </b>

                                                    </div>

                                                </form>
                                            </div>
                                        </div>
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
                                            <th>S.No.</th>
                                            <th>PO View</th>
                                            <th>PI View</th>
                                            <th>Invoice View</th>
                                            <th>School Name</th>
                                            <th>Billing Name</th>
                                            <th>New/Renewal</th>
                                            <th>Module</th>
                                            <th class="text-center">Student Count / No<br>of Platform</th>
                                            <th class="text-center">No of License Count<br>Activated by vaishnavi</th>
                                            <th class="text-center">Total Rate <br>Exc. GST</th>
                                            <th class="text-center">Total Rate <br>Inc. GST</th>
                                            <!-- <th>Total Value</th> -->
                                            <th>Category</th>
                                            <th>Sub-Category</th>
                                            <th>Receivable Amount</th>
                                            <th>Receivable Date</th>
                                            <th>Received Amount</th>
                                            <th>Received Date</th>
                                            <th>Value of TDS</th>
                                            <th>Overdue Payment</th>
                                            <th>Total Overdue Outstanding</th>
                                            <th>Status of Invoicing</th>
                                            <th>Remarks</th>
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

            function getUrlParameter(name) {
                    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                    var results = regex.exec(location.search);
                    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                }

           $('#leads').DataTable({
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
                            'copy', 'csv', 'excel', 'pdf',  'print', 'pageLength',                            
                        ],
                        ajax: { 
                            url: 'get_data_collection_query.php', 
                            type: 'post',
                            data: function (d) {
                                    d.renewal_type = getUrlParameter('dtype'),
                                    d.financial_year = getUrlParameter('financial_year')
                                }
                        },
                        columns: [
                            { data: 'sno' },               // 1: S.No.
                            { data: 'po_btn' },            // 2: PO View
                            { data: 'pi_btns' },           // 3: PI View
                            { data: 'invoice_view' },      // 4: Invoice View
                            { data: 'school_name' },       // 5: School Name
                            { data: 'billing_name' },      // 6: Billing Name
                            { data: 'agreement_type' },    // 7: New Renewal
                            { data: 'modules' },           // 8: Module
                            { data: 'student_count', class: 'text-center' },     // 9: Student Count / Platform
                            { data: 'license_count' },     // 10: No of License Count
                            { data: 'rate_basic', class: 'text-nowrap' },        // 12: Rate per Student Basic Value
                            { data: 'rate_gst', class: 'text-nowrap' },          // 11: Rate per Student Inc. GST
                            // { data: 'total_value' },       // 13: Total Value
                            { data: 'category' },          // 14: Category
                            { data: 'sub_category' },      // 15: Sub-Category
                            { data: 'receivable_amount' }, // 16: Receivable Amount
                            { data: 'receivable_date' },   // 17: Receivable Date
                            { data: 'received_amount' },   // 18: Received Amount
                            { data: 'received_date' },     // 19: Received Date
                            { data: 'tds_value' },         // 20: Value of TDS
                            { data: 'overdue_payment' },   // 21: Overdue Payment
                            { data: 'total_overdue' },     // 22: Total Overdue Outstanding
                            { data: 'status' },            // 23: Status of Invoicing
                            { data: 'remarks' }            // 24: Remarks
                        ],
                        columnDefs: [
                           { orderable: false, targets: '_all' }, 
                           { orderable: true, targets: [21] } 

                        ]
                    });

           // Detect click on Previous when on page 1
            $('#leads_previous').on('click', function () {
                if (table.page() === 0) {
                    table.page('last').draw('page');
                }
            });

            // PO View
            function po_view(order_id,is_group, group_name) {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'po_view.php',
                    data: { page_access: page_access, 
                            order_id:order_id,
                            is_group:is_group,
                            group_name:group_name
                         },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            // PI View
            function pi_view() {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'pi_view.php',
                    data: { page_access: page_access },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            // PI Attach
            function pi_attach(order_id, is_group, group_name) {
                var page_access = 'true';
                order_id = order_id;

                $.ajax({
                    type: 'POST',
                    url: 'pi_attach.php',
                    data: { 
                        age_access: page_access,
                        order_id:order_id,
                        is_group: is_group,
                        group_name: group_name
                         },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function invoice_attach(order_id, is_group, group_name) {
                var page_access = 'true';
                order_id = order_id;

                $.ajax({
                    type: 'POST',
                    url: 'invoice_attach.php',
                    data: { 
                        age_access: page_access,
                        order_id:order_id,
                        is_group: is_group,
                        group_name: group_name
                         },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            // PI Attach
            function pi_edit(order_id,type = 1,is_group, group_name, openTab = null) {
                // type = 1 (Edit) 2 (View)
                var page_access = 'true';
                order_id = order_id;

                $.ajax({
                    type: 'POST',
                    url: 'pi_edit.php',
                    data: { 
                        age_access: page_access,
                        type: type,
                        order_id:order_id,
                        is_group: is_group,
                        group_name: group_name
                         },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();

                         if (openTab === 'attach') {
                                // console.log("2",openTab);
                                $('#attach-tab').tab('show');
                            }

                        
                    }
                });
            }

            function invoice_attach_edit(order_id,type = 1,is_group, group_name) {
                // type = 1 (Edit) 2 (View)
                var page_access = 'true';
                order_id = order_id;

                $.ajax({
                    type: 'POST',
                    url: 'invoice_attach_edit.php',
                    data: { 
                        age_access: page_access,
                        type: type,
                        order_id:order_id,
                        is_group: is_group,
                        group_name: group_name
                         },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            // Module View
            function module_view(orderID, masterProduct,is_group, group_id) {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'module_view.php',
                    data: { 
                        page_access: page_access,
                        order_id: orderID,
                        master_product_id: masterProduct,
                        is_group: is_group,
                        group_id: group_id
                         },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            // Rate Per Student Inc
            function rate_per_student(type,lead_id, is_group, group_name,isModel3) {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'rate_per_student.php',
                    data: { lead_id:lead_id, 
                            type:type, 
                            is_group:is_group, 
                            group_name:group_name, 
                            isModel3:isModel3,
                            page_access: page_access },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }


            function invoiceRemarkAdd(leadID) {
                const $button = $("#btn-remark-" + leadID);
                const $textarea = $("#remark-textarea-" + leadID);
                const remark = $textarea.val();
                
                $button.prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: 'update_remark_order.php',
                    data: {
                        order_id: leadID,
                        remark: remark
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || "Something went wrong.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                        toastr.error("Failed to save remark. Try again.");
                    },
                    complete: function() {
                        $button.prop("disabled", false);
                    }
                });
            }

            // function updateReceivingData(event, type, emi_id) {
            //       let amount = $("#receiving-amount-" + emi_id).val();

            //        if (!amount) {
            //             toastr.info("Info: Enter an amount");
            //             return;
            //         }

            //         if (!/^\d+$/.test(amount)) {
            //             toastr.error("Error: Please enter an integer value only");
            //             return;
            //         }

            //         if (parseInt(amount) <= 0) {
            //             toastr.info("Info: Enter an amount greater than 0");
            //             return;
            //         }


            //             Swal.fire({
            //                 title: "Confirm Update",
            //                 text: "Do you want to update this record?",
            //                 icon: "question",
            //                 showCancelButton: true,
            //                 confirmButtonColor: "#3085d6",
            //                 cancelButtonColor: "#d33",
            //                 confirmButtonText: "Yes, update it!"
            //             }).then((result) => {
            //                 if (!result.isConfirmed) return;

                            
            //                 let date = "";
                            

                            
            //                 if (type === 1) {
            //                     date = $("#receiving-date-" + emi_id).val();
            //                 } else if (type === 2) {
            //                     date = event.target.value;
            //                 }

            //                 $.ajax({
            //                     type: 'POST',
            //                     url: 'update_receiving.php',
            //                     data: {
            //                         emi_id: emi_id,
            //                         date: date,
            //                         amount: amount
            //                     },
            //                     dataType: 'json',
            //                     beforeSend: function () {
            //                         Swal.showLoading();
            //                     },
            //                     success: function (response) {
            //                         Swal.close();
            //                         if (response.status === 'success') {
            //                             toastr.success(response.message);
            //                         } else {
            //                             toastr.error(response.message || "Something went wrong.");
            //                         }
            //                         refreshDatatable();
            //                     },
            //                     error: function (xhr, status, error) {
            //                         Swal.close();
            //                         console.error("AJAX error:", error);
            //                         toastr.error("Failed to update. Try again.");
            //                     }
            //                 });
            //             });
            //         }

                    function updateReceivingData(event, type, emi_id) {
                        let amount = $("#receiving-amount-" + emi_id).val();

                        if (!amount) {
                            toastr.info("Info: Enter an amount");
                            return;
                        }

                        if (!/^\d+$/.test(amount)) {
                            toastr.error("Error: Please enter an integer value only");
                            return;
                        }

                        if (parseInt(amount) <= 0) {
                            toastr.info("Info: Enter an amount greater than 0");
                            return;
                        }

                        swal({
                            title: "Confirm Update",
                            text: "Do you want to update this record?",
                            type: "warning", // v1 uses "type" instead of "icon"
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, update it!"
                        }, function (isConfirm) {
                            if (!isConfirm) return;

                            let date = "";

                            if (type === 1) {
                                date = $("#receiving-date-" + emi_id).val();
                            } else if (type === 2) {
                                date = event.target.value;
                            }

                            $.ajax({
                                type: 'POST',
                                url: 'update_receiving.php',
                                data: {
                                    emi_id: emi_id,
                                    date: date,
                                    amount: amount
                                },
                                dataType: 'json',                               
                                success: function (response) {
                                    swal.close();
                                    if (response.status === 'success') {
                                        toastr.success(response.message);
                                    } else {
                                        toastr.error(response.message || "Something went wrong.");
                                    }
                                    refreshDatatable();
                                },
                                error: function (xhr, status, error) {
                                    swal.close();
                                    toastr.error("Failed to update. Try again.");
                                }
                            });
                            
                        });
                    }




// function updateTDSDetail(order_id) {
//                         Swal.fire({
//                             title: "Confirm Update",
//                             text: "Do you want to update TDS?",
//                             icon: "question",
//                             showCancelButton: true,
//                             confirmButtonColor: "#3085d6",
//                             cancelButtonColor: "#d33",
//                             confirmButtonText: "Yes, update it!"
//                         }).then((result) => {
//                             if (!result.isConfirmed) return;

//                             tds = $("#amount-tds-"+order_id).val()

//                             $.ajax({
//                                 type: 'POST',
//                                 url: 'update_tds.php',
//                                 data: {
//                                     tds: tds,
//                                     order_id: order_id
//                                 },
//                                 dataType: 'json',
//                                 beforeSend: function () {
//                                     Swal.showLoading();
//                                 },
//                                 success: function (response) {
//                                     Swal.close();
//                                     if (response.status === 'success') {
//                                         toastr.success(response.message);
//                                     } else {
//                                         toastr.error(response.message || "Something went wrong.");
//                                     }
//                                     refreshDatatable();
//                                 },
//                                 error: function (xhr, status, error) {
//                                     Swal.close();
//                                     console.error("AJAX error:", error);
//                                     toastr.error("Failed to update. Try again.");
//                                 }
//                             });
//                         });
//                     }

function updateTDSDetail(order_id) {
    swal({
        title: "Confirm Update",
        text: "Do you want to update TDS?",
        type: "warning", // SweetAlert v1 uses "type" instead of "icon"
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
    }, function (isConfirm) {
        if (!isConfirm) return;

        let tds = $("#amount-tds-" + order_id).val();

        $.ajax({
            type: 'POST',
            url: 'update_tds.php',
            data: {
                tds: tds,
                order_id: order_id
            },
            dataType: 'json',
            beforeSend: function () {
                swal({
                    title: "Processing...",
                    text: "Please wait",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function (response) {
                swal.close();
                if (response.status === 'success') {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || "Something went wrong.");
                }
                refreshDatatable();
            },
            error: function (xhr, status, error) {
                swal.close();
                console.error("AJAX error:", error);
                toastr.error("Failed to update. Try again.");
            }
        });
    });
}


    function refreshDatatable(){
            $('#leads').DataTable().ajax.reload(null, false);
    }


        </script>

        <script>
            // Ensure ModalTwo is shown properly above ModalOne
            $('#uploadModal').on('show.bs.modal', function () {
                $('#myModal1').css('z-index', 1040); // Push back the first modal
                $(this).css('z-index', 1050);        // Bring second modal forward
            });
            $('#uploadModal').on('hidden.bs.modal', function () {
                $('#myModal1').css('z-index', 1050); // Restore first modal on top
            });

        </script>

        <script>

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#leads").tableHeadFixer();
            });


            function clear_search() {
                window.location = 'collection-report.php';
            }

            function checkValue(input) {
                var value = parseFloat(input.value);
                if (value < 0) {
                    toastr.error("Error: Please enter a non-negative value.");
                    input.value = '';
                }
            }
        </script>