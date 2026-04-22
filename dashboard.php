<?php
session_start();


$userType = $_SESSION['user_type'] ?? '';

if (in_array($userType, ['CLR'], true)) {
    header("Location: user_dashboard.php");
    exit();
}

include('includes/header.php');
include_once('helpers/dashboard_helper.php');

if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-d');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-d');
}




?>
<style>

    body[data-layout=horizontal] .page-content {
        padding-bottom: 40px;
    }
    /* bar-graph css */
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 210px;     
        max-width: 500px;
        margin: 0 auto;
    }
    .highcharts-credits{
        display: none;
    }
    #container {
        height: 300px;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    /* bar-graph css ends */

    /* pie chart css */

    #highcharts-figure,
    #highcharts-data-table table {
        min-width: 320px;
        max-width: 500px;
        margin: 0 auto;
    }

    .container {
        height: 170px;
    }

    #highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    #highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    #highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    #highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    #highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    #highcharts-data-table tr:hover {
        background: #f1f7ff;
    }


    .table-fixed-column-outter {
        position: relative;
        margin: 1rem auto;
        max-width: 100%;
    }

    .table-fixed-column-inner {
        overflow-x: scroll;
        overflow-y: visible;

        margin-left: 150px;

    }

    .table1 {
        margin-bottom: 0.25rem;
    }

    .table1.table-fixed-column {
        table-layout: fixed;
        width: 100%
    }

    .table1 td,
    .table1 th {
        width: 240px;
        min-height: 43px;
        height: 43px;
    }

    .table1 th:first-child,
    .table1 tr td:first-child {
        position: absolute;
        left: 0;
        width: 150px;
        line-height: 43px;
        min-height: 43px;
    }

    .table-nowrap td,
    .table-nowrap th {

        text-align: center;
    }

    .welcome-text-card {
        height: calc(100vh - 200px);
    }

    .welcome-text-card .card-body {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .welcome-text-card h1 {
        font-size: 40px
    }
</style>


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

                        <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between dashboard-title-box">
                            <h4 class="mb-0 font-size-18">Dashboard</h4>

                            <div class="page-title-right">
                            </div>

                        </div>
                    </div>
                </div>
                        <div class="row">
                            <div class="col">
                                <div class="card welcome-text-card">
                                    <div class="card-body">
                                        <h1>Welcome To Nitro DR Portal</h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> 

                    </div>



        </div>
        <!-- end main content-->
       

        <?php

        include('includes/footer.php') ?>

        <!-- JAVASCRIPT -->

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>
        <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>

        <script language="JavaScript">
            $(function() {
                $('#date_range').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });
            });
            $(function() {
                $('#date_range1').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });
            });
        </script>

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.fixed-table-body').height(wfheight - 530);

                $('.fixed-table-body').slimScroll({
                    color: '#00f',
                    size: '10px',
                    height: 'auto',
                });
            });
        </script>

