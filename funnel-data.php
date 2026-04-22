<?php include('includes/header.php');
include('includes/audit_log_helper.php');
$isPartner = (strtoupper((string) ($_SESSION['role'] ?? '')) === 'PARTNER');
// admin_page(); 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel']) && $_FILES['excel']['error'] === UPLOAD_ERR_OK) {
    // Load PhpSpreadsheet classes
    require_once __DIR__ . '/vendor/autoload.php';
    // Using fully qualified class name for IOFactory

    $tmpPath = $_FILES['excel']['tmp_name'];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpPath);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray(null, true, true, true);
    // Assume first row contains headers matching DB columns
    $header = array_shift($rows);
    $headerMap = [];
    foreach ($header as $col => $title) {
        $key = strtolower(str_replace(' ', '_', trim($title)));
        $headerMap[$col] = $key;
    }
    $inserted = 0;
    foreach ($rows as $row) {
        // Build data array based on known columns
        $data = [];
        foreach ($row as $col => $value) {
            $key = $headerMap[$col] ?? null;
            if ($key) {
                $data[$key] = $value;
            }
        }
        // Prepare fields for insertion (ignore id, created_at, updated_at if not provided)
        $fields = [];
        $values = [];
        foreach (['source','type','month','reseller_code','reseller','end_customer','brand','quote','price','qty','total','closure_date','closure_month'] as $field) {
            if (isset($data[$field])) {
                $value = (string)$data[$field];
                if ($field === 'source' && !empty($value)) {
                    $sourceName = trim($value);
                    $sourceId = getSingleresult("SELECT id FROM lead_source WHERE lead_source = '" . db_escape($sourceName) . "' LIMIT 1");
                    if ($sourceId) {
                        $value = $sourceId;
                    }
                }

                // Robust date parsing for closure_date
                if ($field === 'closure_date' && !empty($value)) {
                    $value = trim($value);
                    if (is_numeric($value)) {
                        // Numeric excel serial date
                        $timestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                        $value = date('Y-m-d', $timestamp);
                    } else {
                        // String date formats
                        $timestamp = strtotime($value);
                        if ($timestamp !== false) {
                            $value = date('Y-m-d', $timestamp);
                        } else {
                            // Fallback: try replacing - with / or other formats if needed
                            $timestamp2 = strtotime(str_replace('-', '/', $value));
                            if ($timestamp2 !== false) {
                                $value = date('Y-m-d', $timestamp2);
                            } else {
                                $value = '0000-00-00';
                            }
                        }
                    }
                }
                $fields[] = $field;
                $values[] = "'" . db_escape($value) . "'";
            }
        }
        // Add timestamps
        $now = date('Y-m-d H:i:s');
        $fields[] = 'created_at';
        $values[] = "'{$now}'";
        $fields[] = 'updated_at';
        $values[] = "'{$now}'";
        $sql = "INSERT INTO funnel_data (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
        db_query($sql);
        $inserted++;
    }
    $msg = "Successfully imported {$inserted} records.";
    echo "<div class='alert alert-success'>{$msg}</div>";
}
?>
<style>
    .dt-buttons {
        margin-top: 30px;
        margin-left: -191px;
        margin-bottom: 10px;
    }
    /* Hide all potential loaders */
    #preloader, #status, .preloader, .dataTables_processing {
        display: none !important;
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
                                            <small class="text-muted">Home > Funnel</small>
                                            <h4 class="font-size-14 m-0 mt-1">Funnel Data  </h4>
                                        </div>
                                <!-- <form method="POST" enctype="multipart/form-data" style="margin-bottom:20px;">
                                    <div class="form-group">
                                        <label for="excel">Upload Funnel Data Excel:</label>
                                        <input type="file" name="excel" id="excel" class="form-control" accept=".xlsx,.xls,.csv" required />
                                    </div>
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </form> -->
                                    </div>
                                </div>
                                <div class="col-sm-auto pt-2 pt-sm-0">
                                    <div class="" role="group">
                                        <!-- Add buttons if needed here -->
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                        
                                        <div class="dropdown dropdown-lg">
                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2 shadow-lg" id="filter-container" role="menu" style="display: none;">
                                                <form method="get" id="search-form" name="search">
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xl-4">
                                                            <label class="font-size-12">Lead Source</label>
                                                            <select name="lead_source_id[]" class="form-control" id="multiselect_lead_source" multiple>
                                                                <?php 
                                                                $lsRes = db_query("SELECT DISTINCT fd.source, COALESCE(ls.lead_source, fd.source) as display_name FROM funnel_data fd LEFT JOIN lead_source ls ON fd.source = ls.id WHERE fd.source IS NOT NULL AND fd.source != '' ORDER BY display_name ASC");
                                                                while ($lsRow = db_fetch_array($lsRes)) { ?>
                                                                    <option value="<?= $lsRow['source'] ?>"><?= $lsRow['display_name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php if (!$isPartner) { ?>
                                                        <div class="form-group col-md-6 col-xl-4">
                                                            <label class="font-size-12">Reseller</label>
                                                            <select name="reseller_id[]" class="form-control" id="multiselect_reseller" multiple>
                                                                <?php 
                                                                $resellerRes = db_query("SELECT DISTINCT fd.reseller_code, fd.reseller, COALESCE(p.name, fd.reseller) as display_name FROM funnel_data fd LEFT JOIN partners p ON fd.reseller_code = p.id WHERE (fd.reseller_code IS NOT NULL AND fd.reseller_code != '') OR (fd.reseller IS NOT NULL AND fd.reseller != '') ORDER BY display_name ASC");
                                                                while ($resellerRow = db_fetch_array($resellerRes)) { 
                                                                    $val = !empty($resellerRow['reseller_code']) ? $resellerRow['reseller_code'] : $resellerRow['reseller'];
                                                                ?>
                                                                    <option value="<?= $val ?>"><?= $resellerRow['display_name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="form-group col-md-6 col-xl-4">
                                                            <label class="font-size-12">Closure Date</label>
                                                            <div class="input-daterange input-group" id="datepicker-closure-date">
                                                                <input type="text" class="form-control" id="closure_from" name="closure_from" placeholder="From" autocomplete="off" />
                                                                <input type="text" class="form-control" id="closure_to" name="closure_to" placeholder="To" autocomplete="off" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xl-2 pt-4">
                                                            <button type="submit" class="btn btn-primary font-14"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table id="funnel_table" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Source</th>
                                            <th>Type</th>
                                            <th>Month</th>
                                            <th>Reseller</th>
                                            <th>End Customer</th>
                                            <th>Brand</th>
                                            <th>Stage</th>
                                            <?php if (!$isPartner) { ?>
                                            <th>Price</th>
                                            <?php } ?>
                                            <th>Qty</th>
                                            <?php if (!$isPartner) { ?>
                                            <th>Total</th>
                                            <?php } ?>
                                            <th>Closure Date</th>
                                            <th>Closure Month</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                </table>
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
                $('#funnel_table').DataTable({
                    "dom": '<"top"if>Brt<"bottom"ip><"clear">',
                    "displayLength": 15,
                    "scrollX": true,
                    "fixedHeader": true,
                    "language": {
                        paginate: {
                            previous: '<i class="fas fa-arrow-left"></i>',
                            next: '<i class="fas fa-arrow-right"></i>'
                        }
                    },
                    buttons: [
                        'copy', 'csv', 'excel', 'print', 'pageLength'
                    ],
                    lengthMenu: [
                        [15, 25, 50, 100, 500, 1000],
                        ['15', '25', '50', '100', '500', '1000']
                    ],
                    "processing": false,
                    "serverSide": true,
                    "ajax": {
                        url: "get_funnel_data.php",
                        type: "post",
                        data: function(d) {
                            d.lead_source_id = JSON.stringify($('#multiselect_lead_source').val());
                            <?php if (!$isPartner) { ?>
                            d.reseller_id = JSON.stringify($('#multiselect_reseller').val());
                            <?php } ?>
                            d.closure_from = $('#closure_from').val();
                            d.closure_to = $('#closure_to').val();
                        },
                        error: function() {
                            $(".employee-grid-error").html("");
                            $("#funnel_table").append('<tbody class="employee-grid-error"><tr><th colspan="15">No data found on server!</th></tr></tbody>');
                        }
                    },
                    "order": [[0, "desc"]],
                    'columns': [
                        { data: 'id' },
                        { data: 'source' },
                        { data: 'type' },
                        { data: 'month' },
                        { data: 'reseller' },
                        { data: 'end_customer' },
                        { data: 'brand' },
                        { data: 'quote' },
                        <?php if (!$isPartner) { ?>
                        { data: 'price' },
                        <?php } ?>
                        { data: 'qty' },
                        <?php if (!$isPartner) { ?>
                        { data: 'total' },
                        <?php } ?>
                        { data: 'closure_date' },
                        { data: 'closure_month' },
                        { data: 'created_at' }
                    ]
                });

                // Init Multi-select for Lead Source filter
                $('#multiselect_lead_source').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Filter Source',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });

                <?php if (!$isPartner) { ?>
                $('#multiselect_reseller').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Filter Reseller',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                <?php } ?>

                // Handle Filter Form Submit
                $('#search-form').on('submit', function(e) {
                    e.preventDefault();
                    $('#funnel_table').DataTable().ajax.reload();
                    $('#filter-container').hide();
                });
            });

            function clear_search() {
                $('#multiselect_lead_source').val([]).multiselect('refresh');
                <?php if (!$isPartner) { ?>
                $('#multiselect_reseller').val([]).multiselect('refresh');
                <?php } ?>
                $('#closure_from, #closure_to').val('');
                $('#funnel_table').DataTable().ajax.reload();
                $('#filter-container').hide();
            }

            $(function() {
                $('#datepicker-closure-date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });
        </script>
