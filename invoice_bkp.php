<?php include('includes/header.php');
admin_page(); 

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
                                            <small class="text-muted">Home >Invoice</small>
                                            <h4 class="font-size-14 m-0 mt-1">Invoice</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-auto pt-2 pt-sm-0">
                                    
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>PO View</th>
                                                <th>PI View</th>
                                                <th>Invoice View</th>
                                                <th>School Name</th>
                                                <th>Billing Name</th>
                                                <th>New Renewal</th>
                                                <th>Module</th>
                                                <th class="text-center">Student Count / No<br>of Platform</th>
                                                <th class="text-center">No of License Count<br>Activated by vaishnavi</th>
                                                <th class="text-center">Rate per Student<br>Inc. GST</th>
                                                <th class="text-center">Rate per Student<br>Basic Value</th>
                                                <th>Total Value</th>
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
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <button class="btn btn-primary px-2 py-1" onclick="po_view()">
                                                    <span class="mdi mdi-eye"></span>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_view()">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_attach()">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_edit()">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_view()">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_attach()">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_edit()">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">Sacred Hearts Sr.Sec Public School</td>
                                            <td class="text-nowrap">MBS Books Private Limited</td>
                                            <td>New</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>Module 1 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 2 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 3 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="inner-items">
                                                    <p>650</p>
                                                    <p>700</p>
                                                    <p>790</p>
                                                </div>
                                            </td>
                                            <td class="text-center">650</td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>194,610</td>
                                            <td>ICT Books</td>
                                            <td>Direct</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>299.40 </p>
                                                    <p>299.40</p>
                                                    <p>299.40</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>Invoice Done</td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields flex-column align-items-start">
                                                        <textarea name="" class="form-control"></textarea>
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <button class="btn btn-primary px-2 py-1" onclick="po_view()">
                                                    <span class="mdi mdi-eye"></span>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_view()">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_attach()">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_edit()">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">Sacred Hearts Sr.Sec Public School</td>
                                            <td class="text-nowrap">MBS Books Private Limited</td>
                                            <td>New</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>Module 1 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 2 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 3 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="inner-items">
                                                    <p>650</p>
                                                    <p>700</p>
                                                    <p>790</p>
                                                </div>
                                            </td>
                                            <td class="text-center">650</td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>194,610</td>
                                            <td>ICT Books</td>
                                            <td>Direct</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>299.40 </p>
                                                    <p>299.40</p>
                                                    <p>299.40</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>Invoice Done</td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields flex-column align-items-start">
                                                        <textarea name="" class="form-control"></textarea>
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <button class="btn btn-primary px-2 py-1" onclick="po_view()">
                                                    <span class="mdi mdi-eye"></span>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_view()">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_attach()">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_edit()">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">Sacred Hearts Sr.Sec Public School</td>
                                            <td class="text-nowrap">MBS Books Private Limited</td>
                                            <td>New</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>Module 1 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 2 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 3 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="inner-items">
                                                    <p>650</p>
                                                    <p>700</p>
                                                    <p>790</p>
                                                </div>
                                            </td>
                                            <td class="text-center">650</td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>194,610</td>
                                            <td>ICT Books</td>
                                            <td>Direct</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>299.40 </p>
                                                    <p>299.40</p>
                                                    <p>299.40</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>Invoice Done</td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields flex-column align-items-start">
                                                        <textarea name="" class="form-control"></textarea>
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <button class="btn btn-primary px-2 py-1" onclick="po_view()">
                                                    <span class="mdi mdi-eye"></span>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_view()">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_attach()">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1" onclick="pi_edit()">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-eye"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-paperclip"></span>
                                                    </button>
                                                    <button class="btn btn-primary px-2 py-1">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">Sacred Hearts Sr.Sec Public School</td>
                                            <td class="text-nowrap">MBS Books Private Limited</td>
                                            <td>New</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>Module 1 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 2 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                    <p>Module 3 <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view()"></span></p>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="inner-items">
                                                    <p>650</p>
                                                    <p>700</p>
                                                    <p>790</p>
                                                </div>
                                            </td>
                                            <td class="text-center">650</td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>299.40 <button class="btn btn-primary px-2 py-1" onclick="rate_per_student()"><span class="mdi mdi-eye"></span></button></td>
                                            <td>194,610</td>
                                            <td>ICT Books</td>
                                            <td>Direct</td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>299.40 </p>
                                                    <p>299.40</p>
                                                    <p>299.40</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                    <p>1/28/2025</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="text" name="amount" class="form-control">
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                    <div class="form-fields">
                                                        <input type="date" name="amount" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>299.40</td>
                                            <td>Invoice Done</td>
                                            <td>
                                                <div class="inner-items">
                                                    <div class="form-fields flex-column align-items-start">
                                                        <textarea name="" class="form-control"></textarea>
                                                        <button class="btn btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
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
        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

        <?php include('includes/footer.php') ?>

        <script>
            // PO View
            function po_view() {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'po_view.php',
                    data: { page_access: page_access },
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
            function pi_attach() {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'pi_attach.php',
                    data: { page_access: page_access },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            // Module View
            function module_view() {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'module_view.php',
                    data: { page_access: page_access },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            // Rate Per Student Inc
            function rate_per_student() {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'rate_per_student.php',
                    data: { page_access: page_access },
                    success: function(response) {
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
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