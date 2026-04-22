<?php include('includes/header.php');
admin_protect(); ?>


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
 
                        <div  class="card-body">
						
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">



                                    <small class="text-muted">Home >Reports</small>
                                    <h4 class="font-size-14 m-0 mt-1">Personal Report</h4>
                                </div>
                            </div>
							<div class="clearfix"></div>
							
							<div data-simplebar class=""> 
							
                           <form id="personal_data" action="export_perReport_partner.php" name="search" role="form">

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <div class="dropdown dropdown-lg">
                                   

                                        <button id="export" data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share "></i></button>

                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>


                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                            <div class="date_export">
                                                <div class="data_export_box_2">
                                                    <div class="row">

                                                        <div class="form-group col-md-4">

                                                            <select name="date_type" id="dateformat" class="form-control">
                                                                <option value="">Date Type</option>
                                                                <?php $date_query = personalReport_dateType('orders');
                                                                foreach ($date_query as $row) { ?>
                                                                    <option value="<?= $row->name ?>"><?= str_replace('_', ' ', ucwords($row->name)) ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">
                                                                <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From">

                                                                <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <button type="submit" id="search" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>

                                                    <div class="data_export_box" style="display: none;">

                                                        <div class="row">
                                                            <div class="form-group col-md-3">

                                                                <label class="mt-2">Industry</label>

                                                                <select name="industry[]" id="multiselect" multiple="multiple" class="form-control ">

                                                                    <?php $query = personalReportPartner_industry('orders', $_SESSION['team_id']);
                                                                    while ($row = db_fetch_array($query)) {
                                                                    ?>
                                                                        <option value="<?= $row['id'] ?>"><?= ucwords($row['industry']) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3">

                                                                <label class="mt-2">Region</label>

                                                                <select name="region[]" id="state" multiple="multiple" class="form-control ">

                                                                    <?php
                                                                    $query = personalReportPartner_state('orders', $_SESSION['team_id']);
                                                                    while ($row = db_fetch_array($query)) {
                                                                        if (!empty($row['state'])) { ?>
                                                                            <option value="<?= $row['id'] ?>"><?= ucwords($row['state']) ?></option>
                                                                    <?php  }
                                                                    } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-3">

                                                                <label class="mt-2">City</label>

                                                                <select name="city[]" id="city" multiple="multiple" class="form-control " disabled>

                                                                </select>
                                                            </div>

                                                       
                                                            <div class="form-group col-md-3">
                                                                <label class="mt-2">Campaign</label>
                                                                <select name="campaign" id="campaign" class="form-control">
                                                                    <option value="">--Select--</option>
                                                                    <?php $query = personalReportPartner_campaign('campaign');
                                                                    while ($row = db_fetch_array($query)) {
                                                                    ?>
                                                                        <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                            <div class="row">

                                                            <div class="form-group col-md-3">
                                                                <label class="mt-2">Product</label>
                                                                <select name="product" class="product_data form-control">
                                                                    <option value="">Select Product</option>
                                                                    <?php $query = selectProductPartner($_SESSION['team_id']);
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="mt-2">Product Type</label>
                                                                <?php if ($_GET['product']) { ?>
                                                                    <select name="product_type" id="product_type" class="form-control">
                                                                        <option value="">Select Product Type</option>
                                                                        <?php $query = selectProductType('tbl_product_pivot', $_GET['product']);
                                                                        while ($row = db_fetch_array($query)) { ?>
                                                                            <option <?= (($_GET['product_type'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_type']) ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <select name="product_type" id="product_type" class="form-control">
                                                                        <option value="">Select Product Type</option>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                            <label class="mt-2">Association Name</label>
                                            <select class="form-control" data-live-search="true" multiple id="multiselect_association" name="association_name[]">
                                                
                                                <?php $assoc_name = searchAssociation('orders',$_SESSION['team_id']); 
                                                while($row = db_fetch_array($assoc_name)){ ?>
                                                <option <?= (in_array($row['association_name'],$association_name) ? 'selected' : '') ?> value="<?= $row['association_name']?>"><?= $row['association_name']?></option>
                                                
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                        <label class="mt-2">Validation Type</label>
                                        <select name="validation_type" class="form-control">
              <option value="">Type of validation</option>
              <option value="profiling_validation" <?= (($_GET['validation_type']=='profiling_validation')?'selected':'') ?>>Validation through call (Profiling)</option>
              <option value="emailer_validation" <?= (($_GET['validation_type']=='emailer_validation')?'selected':'') ?> >Validation through emailer</option>
              </select>
                                        
                                        </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            <div class="row mt-3">

                                <div class="col-md-12">
                                    <input type="button" id="per_repo_list" name="data_search" class="btn btn-primary" value="Report List" style="display:none;" />

                                </div>
                            </div>

                            <div class="row mt-3">

                                <div class="col-md-12">

                                    <div id="checkbox_data">
                                        <div class="data-check-box row">

                                            <?php $sql_select = personalReport_columnData('orderPartner_pivot');
                                            foreach ($sql_select as $row) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-check-inline my-2">
                                                        <div class="custom-control custom-checkbox">

                                                            <input type="checkbox" class="custom-control-input" name="check_list[]" value="<?= $row['order_field_name'] ?>" id="check_data{<?= $row['order_field_name'] ?>}" />
                                                            <label class="custom-control-label" for="check_data{<?= $row['order_field_name'] ?>}"><?= $row['field_label'] ?></label>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>


                                        </div>
                                        <!--data-check-box-->
                                    </div>

                                </div>
                            </div>




                            </form>
                            <div id="table_wrapper">
                            </div>
                            </div> 


                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?php include('includes/footer.php') ?>

        <script>
            $(document).ready(function() {

                $('#export').on('click', function() {
                    checked = $("input[type=checkbox]:checked").length;
                    if (!checked) {
                        swal('You must check at least 1 box');
                        return false;
                    }
                        var formdata = $("#personal_data").serialize()
                        $.ajax({
                        type: 'post',
                        url: 'export_perReport_partner.php',
                        data: formdata,
                        success: function(response) {
                            $("#table_wrapper").html(response);
                        }
                    });
                       // window.location.href = 'export_perReport_partner.php'
                    
                });

                $('#search').on('click', function() {

                    checked = $("input[type=checkbox]:checked").length;
                    if (!checked) {
                        swal('You must check at least 1 box');
                        return false;
                    }

                    if ($('#d_from').val() != "" && $('#d_to').val() != "" && $('#dateformat').val() == "") {
                        swal('Select date type!!');
                        return false;
                    }

                    $("#checkbox_data").hide();
                    $('.data_export_box').css('display', 'block');
                    $("#per_repo_list").show();

                    var check_data = $('#check_data').attr("checked")
                    var formdata = $("#personal_data").serialize()

                    $.ajax({
                        type: 'post',
                        url: 'personalRep_partnerData.php',
                        data: formdata,
                        success: function(response) {
                            $("#table_wrapper").html(response);
                        }
                    });
                    return false;
                });

                $(document).ready(function() {
                    $('.product_data').on('change', function() {
                        //alert('abc');
                        var productID = $(this).val();
                        //alert(productID);
                        if (productID) {
                            $.ajax({
                                type: 'POST',
                                url: 'ajaxProductTypeAdmin.php',
                                data: 'product=' + productID,
                                success: function(html) {
                                    $('#product_type').html(html);

                                },
                            });
                        }
                    });
                });

                $('#state').on('change', function() {

                    var stateID = $(this).val();
                    //alert(stateID);
                    if (stateID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxstatePersonalReport.php',
                            data: 'region=' + stateID,
                            success: function(response) {
                                $('#city').html(response);
                                // $('#city').multiselect({
                                //     buttonWidth: '100%',
                                //     includeSelectAllOption: true,
                                //     nonSelectedText: 'Select an Option'
                                // });
                            },
                            error: function() {
                                $('#city').html('There was an error!');
                            }
                        });
                    } else {
                        $('#city').html('<option value="">Select state first</option>');
                    }
                });

            });

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });



            $('#per_repo_list').on('click', function() {
                $("#checkbox_data").toggle();
            });



            function clear_search() {
                window.location = 'personalReport_partner.php';
            }
        </script>

        <script>
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("fast");
            });

            $(document).ready(function() {

                var wfheight = $(window).height();
                $('.scroll_div').height(wfheight - 420);

                $('.fixed-table-body').slimScroll({
                    color: '#00f',
                    size: '10px',
                    height: 'auto',


                });

            });
        </script>
        <script>
            $(document).ready(function() {
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });

                $('#state').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });

                $('#city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                $('#multiselect_association').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                                
            });


            // $(document).ready(function() {
            //     $('#multiselect').multiselect({
            //         buttonWidth: '100%',
            //         includeSelectAllOption: true,
            //         nonSelectedText: 'Select an Option'
            //     });
            //     $('#state').multiselect({
            //         buttonWidth: '100%',
            //         includeSelectAllOption: true,
            //         nonSelectedText: 'Select an Option'
            //     });
            //     $('#city').multiselect({
            //         buttonWidth: '100%',
            //         includeSelectAllOption: true,
            //         nonSelectedText: 'Select an Option'
            //     });


            // });
        </script>
		  <script>
                $(document).ready(function() {
                    var wfheight = $(window).height();
                    $('.personal_Report').height(wfheight - 190);
                });
            </script>