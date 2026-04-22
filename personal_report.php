<?php include('includes/header.php');
admin_page(); ?>



<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">



                                    <small class="text-muted">Home >Reports</small>
                                    <h4 class="font-size-14 m-0 mt-1">Personal Report</h4>
                                </div>
                            </div>
							<div class="clearfix"></div>
                            <form id="personal_data" action="export_personal_report.php">

                                <div class="date_export mt-2">
                                    <input type="button" id="per_repo_list" class="btn btn-light" name="data_search" value="Report List" style="display:none;" />

                                    <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                        <div class="dropdown dropdown-lg">
                                            <!-- <div class="report_export"> -->
                                                <?php if($_SESSION['download_status'] == 1){ ?>
                                            <button id="export" data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-download"></i></button>
                                            <?php } ?>
                                            <!-- </div> -->


                                            <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>


                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <div class="data_export_box_2">
                                                    <div class="row">

                                                        <div class="form-group col-md-4">
                                                            <select name="date_type" class="form-control" id="dateformate">
                                                                <option value="">Date Type</option>
                                                                <?php $date_query = personalReport_dateType('orders');
                                                                foreach ($date_query as $row) { ?>
                                                                    <option value="<?= $row->name ?>"><?= str_replace('_', ' ', ucwords($row->name)) ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <!--co-md-3-->

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

                                                </div>
                                                <!--data_export_box_2-->
                                                <div class="data_export_box">
                                                    <div class="row">
                                                        <div class="form-group col-md-4 region" style="display: none;">

                                                            <span>Industry</span>

                                                            <select name="industry[]" data-live-search="true" multiple class="form-control" id="multiselect">

                                                                <?php $query = personalReport_industrySelect('industry');
                                                                while ($row = db_fetch_array($query)) { ?>
                                                                    <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                        <!--col-md-4-->
                                                        <div class="form-group col-md-4 region" style="display: none;">

                                                            <span>Region</span>

                                                            <select name="region[]" id="region" data-live-search="true" multiple class="multiselect_region form-control ">

                                                                <?php
                                                                $query = personalReport_stateSelect('states');
                                                                while ($row = db_fetch_array($query)) { ?>
                                                                    <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                <?php  } ?>
                                                            </select>

                                                        </div>
                                                        <!--col-md-4-->
                                                        <div class="form-group col-md-4 region" style="display: none;">

                                                            <span>City</span>

                                                            <select name="city[]" id="city" data-live-search="true" multiple class="multiselect_city form-control ">

                                                                <?php
                                                                $query = personalReport_citySelect('orders');
                                                                while ($row = db_fetch_array($query)) {
                                                                    if (!empty($row['city'])) { ?>
                                                                        <option value="<?= $row['city'] ?>"><?= ucwords($row['city']) ?></option>
                                                                <?php  }
                                                                } ?>
                                                            </select>

                                                        </div>
                                                        <!--col-md-4-->

                                                    </div>
                                                    <!--row-->
                                                    <div class="row">

                                                        <!-- <div class="form-group col-md-4 region" style="display: none; ">
                                                            <span>Product</span>
                                                            <select name="product" class="product_data form-control">
                                                                <option value="">Select Product</option>
                                                                <?php $query = selectProduct('tbl_product');
                                                                while ($row = db_fetch_array($query)) { ?>
                                                                    <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div> -->

                                                        <!-- <div class="form-group col-md-4 region" style="display: none;">

                                                            <span>Product Type</span>
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

                                                        </div> -->

                                                    <!-- <div class="form-group col-md-4 region" style="display: none;">
                                           
                                                    <span>Association Name</span>
                                                                                                   
                                                <select class="multiselect_assoc form-control" data-live-search="true" multiple id="association_name" name="association_name[]">
                                               
                                                        <?php $query = searchAssociationAdmin('orders'); 
                                                        while($row = db_fetch_array($query)){ 
                                                           ?>
                                                            <option value="<?= $row['association_name']?>"><?= $row['association_name']?></option>
                                                            
                                                            <?php } ?>
                                                    </select>
                                                
                                           </div> -->

                                           <!-- <div class="form-group col-md-4 region" style="display: none;">
                                           <span>Validation Type</span>
                                           <select name="validation_type" class="form-control">
              <option value="">Type of validation</option>
              <option value="profiling_validation" <?= (($_GET['validation_type']=='profiling_validation')?'selected':'') ?>>Validation through call (Profiling)</option>
              <option value="emailer_validation" <?= (($_GET['validation_type']=='emailer_validation')?'selected':'') ?> >Validation through emailer</option>
              </select>
                                        
                                        </div> -->

                                                    </div>
                                                    <!--data_export_box-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">

                                    <div class="col-md-12">

                                        <div id="checkbox_data">
                                            <div class="data-check-box row">


                                                <?php $sql_select = personalReport_columnData('order_pivot');
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
                });

                $('#search').on('click', function() {
                    checked = $("input[type=checkbox]:checked").length;
                    if (!checked) {
                        swal('You must check at least 1 box');
                        return false;
                    }

                    if ($('#d_from').val() != "" && $('#d_to').val() != "" && $('#dateformate').val() == "") {
                        swal('Select date type!!');
                        return false;
                    }

                    $("#checkbox_data").hide();
                    $('.region').css('display', 'block');
                    $("#per_repo_list").show();
                    // $(".region").show();
                    //$("#city").show();
                    $("#tag_lines").show();
                    var check_data = $('#check_data').attr("checked")
                    var formdata = $("#personal_data").serialize()
                    //alert(formdata);
                    //$('#table_wrapper').prepend(data);
                    $.ajax({
                        type: 'post',
                        url: 'personalReport_dataTable.php',
                        data: formdata,
                        success: function(response) {
                            $("#table_wrapper").html(response);
                        }
                    });
                    return false;
                });
            });



            $('#per_repo_list').on('click', function() {
                $("#checkbox_data").toggle();
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

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'personal_report.php';
            }

            $(document).ready(function() {
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });

                $('.multiselect_region').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                $('.multiselect_assoc').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                
            });
        </script>

        <script type="text/javascript">
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("fast");
            });

            $(document).ready(function() {

                var wfheight = $(window).height();
                $('.scroll_div').height(wfheight - 350);
				$('#checkbox_data').height(wfheight - 210);
				

                $('.fixed-table-body').slimScroll({
                    color: '#00f',
                    size: '10px',
                    height: 'auto',


                });

            });
        </script>