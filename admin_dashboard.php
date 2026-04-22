<?php

include('includes/header.php');

?>

<?php
// user counts
$totalUsers = 0;
$activeUsers = 0;
if (function_exists('getSingleresult')) {
    $totalUsers = (int)getSingleresult("select count(*) from users");
    $activeUsers = (int)getSingleresult("select count(*) from users where status='Active'");
} else {
    $res = db_query("select count(*) as cnt from users");
    if ($row = db_fetch_array($res)) { $totalUsers = (int)$row['cnt']; }
    $res2 = db_query("select count(*) as cnt from users where status='Active'");
    if ($row2 = db_fetch_array($res2)) { $activeUsers = (int)$row2['cnt']; }
}
// fresh leads counts (agreement_type='Fresh' and is_opportunity=0)
$totalFreshLeads = 0;
$todayFreshLeads = 0;
if (function_exists('getSingleresult')) {
    $totalFreshLeads = (int)getSingleresult("select count(DISTINCT id) from orders where is_opportunity=0");
    $todayFreshLeads = (int)getSingleresult("select count(DISTINCT id) from orders where is_opportunity=0 and DATE(created_at)=CURDATE()");
    $totalLeads = (int)getSingleresult("select count(DISTINCT id) from orders");
} else {
    $r = db_query("select count(DISTINCT id) as cnt from orders where is_opportunity=0");
    if ($rr = db_fetch_array($r)) { $totalFreshLeads = (int)$rr['cnt']; }
    $r2 = db_query("select count(DISTINCT id) as cnt from orders where is_opportunity=0 and DATE(created_at)=CURDATE()");
    if ($rr2 = db_fetch_array($r2)) { $todayFreshLeads = (int)$rr2['cnt']; }
    $r3 = db_query("select count(DISTINCT id) as cnt from orders");
    if ($rr3 = db_fetch_array($r3)) { $totalLeads = (int)$rr3['cnt']; }
}
// opportunity counts
$totalOpportunities = 0;
$notOpportunities = 0;
if (function_exists('getSingleresult')) {
    $totalOpportunities = (int)getSingleresult("select count(DISTINCT id) from orders where is_opportunity=1");
    $notOpportunities = (int)getSingleresult("select count(DISTINCT id) from orders where is_opportunity!=1");
} else {
    $ro = db_query("select count(DISTINCT id) as cnt from orders where is_opportunity=1");
    if ($rro = db_fetch_array($ro)) { $totalOpportunities = (int)$rro['cnt']; }
    $ro2 = db_query("select count(DISTINCT id) as cnt from orders where is_opportunity!=1");
    if ($rro2 = db_fetch_array($ro2)) { $notOpportunities = (int)$rro2['cnt']; }
}
// approval counts (is_approved = 1 => approved)
$totalApproved = 0;
$pendingApproval = 0;
if (function_exists('getSingleresult')) {
    $totalApproved = (int)getSingleresult("select count(DISTINCT id) from orders where is_approved=1");
    $pendingApproval = (int)getSingleresult("select count(DISTINCT id) from orders where is_approved!=1");
} else {
    $ra = db_query("select count(DISTINCT id) as cnt from orders where is_approved=1");
    if ($rra = db_fetch_array($ra)) { $totalApproved = (int)$rra['cnt']; }
    $ra2 = db_query("select count(DISTINCT id) as cnt from orders where is_approved!=1");
    if ($rra2 = db_fetch_array($ra2)) { $pendingApproval = (int)$rra2['cnt']; }
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">



                    <div class="dashboard-header" style="padding: 10px;border-radius: 8px;margin-bottom: 16px;background: linear-gradient(90deg, #4f97a0, #1f2937);color: #fff;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                            <div>
                                <h1 style="margin:0;font-size:15px;font-weight:700;letter-spacing:0.2px">Hello <?php echo htmlspecialchars($_SESSION['name']); ?>, Welcome to Nitro Dashboard</h1>
                                <!-- <div style="margin-top:6px;color:rgba(255,255,255,0.9)">Session: <strong><?php echo htmlspecialchars($username); ?></strong></div> -->
                            </div>
                            <div style="font-size:13px;color:rgba(255,255,255,0.85)"><?= $_SESSION['user_type'] ?> PANEL
                                 <br>
                                <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] == "ADMIN"): ?>
                                        <small>Internal</small>
                                    <?php endif; ?>
                                 
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <style>
            .dashboard-cards{display:flex;flex-wrap:wrap;gap:16px;margin-bottom:20px}
            .dash-card{flex:1 1 calc(25% - 12px);border-radius:8px;padding:18px;box-shadow:0 1px 2px rgba(0,0,0,0.03);min-width:200px;display:flex;flex-direction:column;justify-content:center}
            .dash-card .title{font-size:13px;color:rgba(255,255,255,0.9);margin-bottom:8px}
            .dash-card .value{font-size:28px;font-weight:600;color:#fff}
            .dash-card .muted{font-size:12px;color:rgba(255,255,255,0.85);margin-top:6px}

            /* Color variants */
            .card-blue{background:linear-gradient(135deg,#2563eb,#60a5fa);}
            .card-green{background:linear-gradient(135deg,#059669,#34d399);}
            .card-orange{background:linear-gradient(135deg,#f97316,#fb923c);}
            .card-red{background:linear-gradient(135deg,#dc2626,#f87171);}

            /* Fallback neutral card (for non-colored themes) */
            .card-neutral{background:#fff;border:1px solid #e6e6e6;color:#111}
            .card-neutral .title{color:#666}
            .card-neutral .value{color:#111}
            .card-neutral .muted{color:#999}

            .dashboard-list table{width:100%;border-collapse:collapse;background:#fff;border:1px solid #e6e6e6;border-radius:6px;overflow:hidden}
            .dashboard-list th,.dashboard-list td{padding:12px 14px;text-align:left;border-bottom:1px solid #f1f1f1}
            .dashboard-list thead th{background:#fafafa;font-weight:600;color:#333}
            @media(max-width:900px){.dash-card{flex:1 1 calc(50% - 12px)}}
            @media(max-width:520px){.dash-card{flex:1 1 100%}}
            </style>

            <div class="dashboard-cards">
                <div class="dash-card card-blue">
                    <div class="title">Total Users</div>
                    <div class="value"><?php echo number_format($totalUsers); ?></div>
                    <div class="muted">Active Users: <?php echo number_format($activeUsers); ?></div>
                </div>

                <div class="dash-card card-green">
                    <div class="title">Total Leads</div>
                    <div class="value"><?php echo number_format($totalLeads ?? 0); ?></div>
                    <div class="muted">Total Fresh Leads: <?php echo number_format($totalFreshLeads); ?></div>
                </div>

                <div class="dash-card card-orange">
                    <div class="title">Total Opportunity</div>
                    <div class="value"><?php echo number_format($totalOpportunities); ?></div>
                    <div class="muted">Not Opportunity: <?php echo number_format($notOpportunities); ?></div>
                </div>

                <div class="dash-card card-red">
                    <div class="title">Approved Leads</div>
                    <div class="value"><?php echo number_format($totalApproved); ?></div>
                    <div class="muted">Pending For Approval: <?php echo number_format($pendingApproval); ?></div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h4 style="margin:8px 0 12px;">Users</h4>
                    <div class="dashboard-list">
                        <table id="activities-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Partner</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Role</th>
                                    <th>User Type</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = db_query("select * from users order by id desc");
                            while($data = db_fetch_array($sql)){
                                $role_name = 'N/A';
                                if (!empty($data['role'])) {
                                    if ($data['role'] == 'Internal' || $data['role'] == 'Partner') {
                                        $role_name = $data['role'];
                                    } else {
                                        $role_name = getSingleresult("select role_name from role where role_code='".$data['role']."'");
                                        if (empty($role_name)) { $role_name = $data['role']; }
                                    }
                                }

                                $user_type_name = 'N/A';
                                if (!empty($data['user_type'])) {
                                    $userTypeMap = array(
                                        'ADMIN' => 'Administrator',
                                        'OPERATIONS' => 'Operation',
                                        'CLR' => 'Caller',
                                        'SALES MNGR' => 'Sales Manager',
                                        'MNGR' => 'Manager',
                                        'USR' => 'User'
                                    );
                                    if (isset($userTypeMap[$data['user_type']])) {
                                        $user_type_name = $userTypeMap[$data['user_type']];
                                    } else {
                                        $user_type_name = getSingleresult("select role_type from user_type_role where role_code='".$data['user_type']."'");
                                        if (empty($user_type_name)) { $user_type_name = $data['user_type']; }
                                    }
                                }

                                $created_user = 'N/A';
                                if (!empty($data['date_created'])) { $created_user = date('Y-m-d H:i:s', strtotime($data['date_created'])); }
                                else if (!empty($data['created_date'])) { $created_user = date('Y-m-d H:i:s', strtotime($data['created_date'])); }

                                $status_label = $data['status'];
                                if ($status_label == 'InActive') { $status_label = 'Inactive'; }

                                $teamId = (int)($data['team_id'] ?? 0);
                                $partnerName = 'N/A';
                                if ($teamId > 0) {
                                    $partnerName = getSingleresult("select name from partners where id=".$teamId);
                                    if (empty($partnerName)) { $partnerName = 'N/A'; }
                                }
                                $login_time = getSingleresult("select login_time from user_tracking where user_id=".$data['id']." ORDER BY id DESC LIMIT 1");
                            ?>
                            <tr class="<?= ($status_label == 'Active' ? '' : 'inactive-user-row') ?>">
                                <td><?= $data['id'] ?></td>
                                <td><?= htmlspecialchars($partnerName) ?></td>
                                <td><?= htmlspecialchars($data['name']) ?></td>
                                <td><?= htmlspecialchars($data['email']) ?></td>
                                <td><?= htmlspecialchars($data['mobile']) ?></td>
                                <td><?= htmlspecialchars($role_name) ?></td>
                                <td><?= htmlspecialchars($user_type_name) ?></td>
                                <td><?= htmlspecialchars($status_label) ?></td>
                                <td><?= (!empty($login_time)) ? date("Y-m-d H:i:s", $login_time) : 'NA' ?></td>
                                <td><?= $created_user ?></td>
                                <td><a href="edit_user.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-primary">Edit</a></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <script>
                        (function(){
                            function loadScript(src, cb){var s=document.createElement('script');s.src=src;s.onload=cb;document.head.appendChild(s);}
                            function loadCss(href){var l=document.createElement('link');l.rel='stylesheet';l.href=href;document.head.appendChild(l);}

                            function init(){
                                if(!$.fn.dataTable){
                                    loadCss('https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css');
                                    loadScript('https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', function(){
                                        $('#activities-table').DataTable({pageLength:10,lengthChange:false,order:[[0,'desc']]});
                                    });
                                } else {
                                    $('#activities-table').DataTable({pageLength:10,lengthChange:false,order:[[0,'desc']]});
                                }
                            }

                            if(typeof jQuery === 'undefined'){
                                loadScript('https://code.jquery.com/jquery-3.6.0.min.js', init);
                            } else { init(); }
                        })();
                        </script>
                    </div>
                </div>
            </div>

    

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
