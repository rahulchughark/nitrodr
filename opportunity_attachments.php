<?php include("includes/include.php");
$pid = intval($_POST['pid']);
$product = intval($_POST['product']);
$oppAttachPO = db_query("SELECT * from opportunity_attachments where lead_id='".$pid."' and product_id='".$product."' and attachment_type='po_attachments' and status=1 ");
$oppAttachPI = db_query("SELECT * from opportunity_attachments where lead_id='".$pid."' and product_id='".$product."' and attachment_type='pi_attachments' and status=1 ");
$oppAttachInv = db_query("SELECT * from opportunity_attachments where lead_id='".$pid."' and product_id='".$product."' and attachment_type='invoice_attachments' and status=1 ");
?>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0">
                View</b>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body py-4">
                <div class="custom-tabs">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="active" id="po-tab" data-toggle="tab" href="#po3" role="tab" aria-controls="po" aria-selected="true">PO</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="pi-tab" data-toggle="tab" href="#pi3" role="tab" aria-controls="pi" aria-selected="false">PI</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="invoice-tab" data-toggle="tab" href="#invoice3" role="tab" aria-controls="invoice" aria-selected="false">Invoice</a>
                        </li>
                    </ul>
<!-- po  tab  -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="po3" role="tabpanel" aria-labelledby="po-tab">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Document Name</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(mysqli_num_rows($oppAttachPO) > 0){
                                        $i = 1;
                                        while($po = db_fetch_array($oppAttachPO))
                                        { 
                                        ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $po['attachment_name'] ?></td>
                                            <td><?= $po['created_at'] ?></td>
                                            <td>
                                                <div class="d-inline-flex">
                                                <a href="<?= $po['attachment_path'] ?>" target="_blank"><button class="btn btn-primary px-2 py-1 mr-1" ><i style="font-size:16px" class="mdi mdi-eye"></i></button></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                        $i++;
                                        }}else{ ?>
                                        <tr>
                                            <td colspan="5">No attachment available</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
<!-- pi tab -->
                        <div class="tab-pane fade" id="pi3" role="tabpanel" aria-labelledby="pi-tab">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Document Name</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(mysqli_num_rows($oppAttachPI) > 0){
                                        $i = 1;
                                        while($pi = db_fetch_array($oppAttachPI))
                                        { 
                                        ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $pi['attachment_name'] ?></td>
                                            <td><?= $pi['created_at'] ?></td>
                                            <td>
                                                <div class="d-inline-flex">
                                                <a href="<?= $pi['attachment_path'] ?>" target="_blank"><button class="btn btn-primary px-2 py-1 mr-1" ><i style="font-size:16px" class="mdi mdi-eye"></i></button></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                        $i++;
                                        }}else{ ?>
                                        <tr>
                                            <td colspan="5">No attachment available</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
<!-- invoice tab -->
                        <div class="tab-pane fade" id="invoice3" role="tabpanel" aria-labelledby="invoice-tab">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Document Name</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(mysqli_num_rows($oppAttachInv) > 0){
                                        $i = 1;
                                        while($inv = db_fetch_array($oppAttachInv))
                                        { 
                                        ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $inv['attachment_name'] ?></td>
                                            <td><?= $inv['created_at'] ?></td>
                                            <td>
                                                <div class="d-inline-flex">
                                                <a href="<?= $inv['attachment_path'] ?>" target="_blank"><button class="btn btn-primary px-2 py-1 mr-1" ><i style="font-size:16px" class="mdi mdi-eye"></i></button></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                        $i++;
                                        }}else{ ?>
                                        <tr>
                                            <td colspan="5">No attachment available</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>