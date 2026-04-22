<?php include('includes/header.php');
admin_page();

$_GET['f_date'] = preg_replace("([^0-9/] | [^0-9-])", "", $_GET['f_date']);
$_GET['t_date'] = preg_replace("([^0-9/] | [^0-9-])", "", $_GET['t_date']);


if ($_GET['f_date'] && $_GET['t_date']) {
    $f_dat = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($_GET['f_date']));
    $t_dat = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($_GET['t_date']));
} else {
    $t_dat = date('Y-m-d');
    $f_dat = date('Y-m-d', strtotime("- 7 days"));
}

// if ($_REQUEST['product']) {
//     $dat .= " and p.product_id='" . $_REQUEST['product'] . "'";
// }
// if ($_REQUEST['product_type']) {
//     $dat .= " and p.product_type_id='" . $_REQUEST['product_type'] . "'";
// }
if ($_REQUEST['partner']) {
	$contd .= " and id='" . $_REQUEST['partner'] . "'";
}
?>

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
                            <div class="media bredcrum-title">
							<img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Data Submission Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">Data Submission Report</h4>
                                </div>
                            </div>


                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false" id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                        <form method="get" name="search">
                           
                                <?php if ($_SESSION['sales_manager'] != 1) {
                                    $res = db_query("select * from partners where status='Active'");
                                } else {
                                    $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                }
                                //print_r($res); die;

                                ?>
                                 <div class="row">
                                 <div class="form-group col-md-5">
                                    <select name="partner" id="partner" class="form-control">
                                        <option value="">Select Partners</option>
                                        <?php while ($row = db_fetch_array($res)) { ?>
                                            <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                 <div class="input-daterange input-group" id="datepicker-close-date">
                                    <input type="text" class="form-control" value="<?php echo @$_GET['f_date'] ?>"  id="f_date" name="f_date" placeholder="Date" />
                                    <input type="text" class="form-control" value="<?php echo @$_GET['t_date'] ?>"  id="t_date" name="t_date" placeholder="Date" />
                                 </div>
                                 </div>
                                 </div>

                                 <div class="row">
                                <!-- <div class="form-group col-md-5">
                                    <select name="product" class="product_data form-control">
                                        <option value="">Select Product</option>
                                        <?php $query = selectProduct('tbl_product');
                                        while ($row = db_fetch_array($query)) { ?>
                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                        <?php } ?>
                                    </select>
                                </div> -->
                                
                                <!-- <div class="form-group col-md-5">
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
                                 </div>

                                 
                                 

                                 <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                        </form>
                                    </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Name</th>
                                        <th>Data Received</th>
                                        <th>Data Qualified</th>
                                        <th>Re-Submission</th>
                                        <th>Unqualified</th>
                                        <th>Pending</th>
                                        <th>On-Hold</th>

                                    </tr>
                                </thead>

                                <tbody>
                                <?php if ($_SESSION['sales_manager'] != 1) {
                                    $sql = db_query("select * from partners where reseller_id!='' $contd and status='Active' order by partners.id desc");
                                } else {
                                    $sql = db_query("select * from partners where reseller_id!='' $contd and status='Active' and id in (" . $_SESSION['access'] . ") order by partners.id desc");
                                }
                                    $i = 1;
                                    while ($data = db_fetch_array($sql)) {

                                    ?>

                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $data[2] ?></td>
                                            <td><?= //$query="select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and date(o.created_date)>='" . $f_dat . "' and date(o.created_date)<='" . $t_dat . "' $dat";
                                            //print_r($query);
                                            getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and date(o.created_date)>='" . $f_dat . "' and date(o.created_date)<='" . $t_dat . "' $dat") ?></td>

                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and date(o.created_date)>='" . $f_dat . "' and date(o.created_date)<='" . $t_dat . "'  and o.status='Approved' $dat") ?></td>

                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and date(o.created_date)>='" . $f_dat . "' and date(o.created_date)<='" . $t_dat . "' and o.status='Undervalidation' $dat") ?></td>

                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and date(o.created_date)>='" . $f_dat . "' and date(o.created_date)<='" . $t_dat . "' and o.status='Cancelled' $dat") ?></td>

                                          
                                            <td>
                                            <?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and date(o.created_date)>='" . $f_dat . "' and date(o.created_date)<='" . $t_dat . "' and o.status='Pending' $dat") ?>
                                               
                                                </td>
                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and date(o.created_date)>='" . $f_dat . "' and date(o.created_date)<='" . $t_dat . "' and o.status='On-Hold' $dat") ?></td>
                                        </tr>
                                    <?php $i++;
                                    } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <div id="myModal" class="modal fade" role="dialog">


    </div>
    <?php include('includes/footer.php') ?>
    <script>

        $('#example23').DataTable({
            "displayLength": 15,
            dom: 'Bfrtip',
            buttons: [
                <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel',  'print', 'pageLength',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                <?php }else{ ?> 'pageLength'  <?php } ?>
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000],
                ['15', '25', '50', '100', '500', '1000']
            ],
        });

        $(document).ready(function() {
            $('.product_data').on('change', function() {
                var productID = $(this).val();
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
       

        $(function() {
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0

            });

        });
    });
    
        function change_goal(a) {
            $.ajax({
                type: 'POST',
                url: 'get_goal.php',
                data: {
                    pid: a
                },
                success: function(response) {
                    $("#myModal").html();
                    $("#myModal").html(response);
                    $('#myModal').modal('show');
                }
            });
        }



        function clear_search() {
            window.location = 'weekly_report.php';
        }
    </script>
    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 320);
            $("#example23").tableHeadFixer();

        });
    </script>