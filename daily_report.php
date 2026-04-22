<?php include('includes/header.php');
admin_page();
if ($_GET['date']) {
    $dat = $_GET['date'];
} else {
    $dat = date('Y-m-d');
}

if ($_REQUEST['product']) {
    $p_data .= " and p.product_id='" . $_REQUEST['product'] . "'";
}
if ($_REQUEST['product_type']) {
    $p_data .= " and p.product_type_id='" . $_REQUEST['product_type'] . "'";
}

?>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Reports</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Daily Report</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">


                    <div class="">
                        <a href="add_partner.php"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Partner" class="right-side bottom-right waves-effect waves-light btn-success btn btn-circle btn-lg pull-right m-l-10"><i class="ti-plus text-white"></i></button></a>

                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body fixed-table-body">
                        <h4 class="card-title">Data Export</h4>
                        <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>

                        <form method="get" name="search">
                            <div class="row">
                                <div class="col-2 form-group">
                                    <select name="product" class="product_data form-control">
                                        <option value="">Select Product</option>
                                        <?php $query = selectProduct('tbl_product');
                                        while ($row = db_fetch_array($query)) { ?>
                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-2 form-group">
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
                                <div style="float:right;margin-right:20px">
                                    <input type="text" value="<?= $dat ?>" class="datepicker" id="date" name="date" placeholder="Date" />

                                    <input type="submit" value="Search" />
                                    <input type="button" value="Clear" onclick="clear_search()" />
                                </div>
                            </div>
                        </form>


                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Data Received</th>
                                        <th>Data Qualified</th>
                                        <th>Under Validation</th>
                                        <th>Unqualified</th>
                                        <th>Pending</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $sql = db_query("select * from partners where reseller_id!='' order by partners.id desc");

                                    while ($data = db_fetch_array($sql)) {

                                    ?>

                                        <tr>
                                            <td><?= $data['1'] ?></td>
                                            <td><?= $data['2'] ?></td>
                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.team_id='" . $data['id'] . "' and date(o.created_date)='" . $dat . "' $p_data ") ?></td>

                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.team_id='" . $data['id'] . "' and date(o.created_date)='" . $dat . "' and o.status='Approved' $p_data") ?></td>

                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.team_id='" . $data['id'] . "' and date(o.created_date)='" . $dat . "' and o.status='Undervalidation' $p_data" ) ?></td>

                                            <td><?= getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.team_id='" . $data['id'] . "' and date(o.created_date)='" . $dat . "' and o.status='Cancelled' $p_data") ?></td>

                                            <?php $goal = db_query("select * from lead_goals where partner_id=" . $data['0']);
                                            $goal_data = db_fetch_array($goal);
                                            $final = $goal_data['daily'] - getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.team_id='" . $data['id'] . "' and date(o.created_date)='" . $dat . "' and o.status='Approved' $p_data")
                                            ?>
                                            <td><?php if ($goal_data['daily']) { ?><?= (($final < 0) ? '0' : $final) ?><?php } else {
                                                                                                                        echo "Please fill Goals for the partner";
                                                                                                                    } ?> </td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->

        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <div id="myModal" class="modal fade" role="dialog">


    </div>
    <?php include('includes/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
            $(document).ready(function() {
                var table = $('#example').DataTable({
                    "columnDefs": [{
                        "visible": false,
                        "targets": 2
                    }],
                    "order": [
                        [2, 'desc']
                    ],
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                    ],
                    lengthMenu: [
                        [10, 25, 50, 100, 500, 1000],
                        ['10', '25', '50', '100', '500', '1000']
                    ],
                    "displayLength": 25,
                    "drawCallback": function(settings) {
                        var api = this.api();
                        var rows = api.rows({
                            page: 'current'
                        }).nodes();
                        var last = null;
                        api.column(2, {
                            page: 'current'
                        }).data().each(function(group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                last = group;
                            }
                        });
                    }
                });
                // Order by the grouping
                $('#example tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
            });
        });
        $('#example23').DataTable({
            "displayLength": 25,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ]
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
        });

        /* function change_goal(a)
        {
        	 $.ajax({  
            type: 'POST',  
            url: 'get_goal.php',
        	data:{pid:a},
            success: function(response) { 
        	$("#myModal").html();
                  $("#myModal").html(response);
                $('#myModal').modal('show');
         }
             });
        }	 */
        $(function() {
            $('.datepicker').daterangepicker({

                "singleDatePicker": true,
                "showDropdowns": true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                //startDate: '2017-01-01',
                //autoUpdateInput: false,

            });
        });

        function clear_search() {
            window.location = 'daily_report.php';
        }
    </script>
    <script>
        jQuery("#search_toogle").click(function() {
            jQuery(".search_form").toggle("fast");
        });

        var wfheight = $(window).height();

        $('.fixed-table-body').height(wfheight - 195);



        $('.fixed-table-body').slimScroll({
            color: '#00f',
            size: '10px',
            height: 'auto',


        });
    </script>