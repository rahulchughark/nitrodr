<?php
include('includes/header.php');

// Only Sales Manager access (adjust role value if different)
$userType = $_SESSION['user_type'] ?? '';
$sessionRole = $_SESSION['role'] ?? '';
// Allow Sales Manager, Operations and Caller roles to view this dashboard
if (!in_array($userType, ['SALES MNGR', 'OPERATIONS', 'CLR','MNGR','USR'], true)) {
    echo "<div class=\"container mt-4\"><div class=\"alert alert-danger\">Unauthorized: Sales Manager access required.</div></div>";
    include('includes/footer.php');
    exit;
}

// Total Leads
$totalLeads = (int)getSingleresult("SELECT COUNT(*) FROM orders");
$todayLeads = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE()");

// Determine approved stage id (try to find stage name containing 'approve')
$approvedStageId = getSingleresult("SELECT id FROM tbl_mst_stage WHERE LOWER(name) LIKE '%approve%' LIMIT 1");
$totalApproved = 0;
$todayApproved = 0;
if ($approvedStageId) {
    $totalApproved = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE stage_id='".$approvedStageId."'");
    $todayApproved = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE stage_id='".$approvedStageId."' AND DATE(created_at)=CURDATE()");
} else {
    // Fallback: try status='Approved'
    $totalApproved = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE status='Approved'");
    $todayApproved = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE status='Approved' AND DATE(created_at)=CURDATE()");
}

// Total by Partner / Internal
$hasCol = false;
$colRes = db_query("SHOW COLUMNS FROM orders LIKE 'created_by_category'");
if ($colRes && mysqli_num_rows($colRes) > 0) {
    $hasCol = true;
}

if ($hasCol) {
    $totalByPartner = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by_category='Partner'");
    $totalByInternal = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by_category='Internal'");
    $todayByPartner = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by_category='Partner' AND DATE(created_at)=CURDATE()");
    $todayByInternal = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by_category='Internal' AND DATE(created_at)=CURDATE()");
} else {
    // Join users table to infer creator role
    $totalByPartner = (int)getSingleresult("SELECT COUNT(*) FROM orders o JOIN users u ON o.created_by=u.id WHERE u.user_type='Partner'");
    $totalByInternal = (int)getSingleresult("SELECT COUNT(*) FROM orders o JOIN users u ON o.created_by=u.id WHERE (u.user_type IS NULL OR u.user_type<>'Partner')");
    $todayByPartner = (int)getSingleresult("SELECT COUNT(*) FROM orders o JOIN users u ON o.created_by=u.id WHERE u.user_type='Partner' AND DATE(o.created_at)=CURDATE()");
    $todayByInternal = (int)getSingleresult("SELECT COUNT(*) FROM orders o JOIN users u ON o.created_by=u.id WHERE (u.user_type IS NULL OR u.user_type<>'Partner') AND DATE(o.created_at)=CURDATE()");
}

// Role-specific: Caller (CLR) sees counts only for leads they created
if ($userType === 'CLR') {
    $uid = (int)($_SESSION['user_id'] ?? 0);
    $totalLeads = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."'");
    $todayLeads = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."' AND DATE(created_at)=CURDATE()");

    // Total Approved uses is_approved flag for callers
    $totalApproved = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."' AND is_approved=1");
    $todayApproved = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."' AND is_approved=1 AND DATE(created_at)=CURDATE()");

    // Opportunities
    $totalOpportunities = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."' AND is_opportunity=1");
    $todayOpportunities = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."' AND is_opportunity=1 AND DATE(created_at)=CURDATE()");

    // Expected closure date counts
    $expectedToday = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."' AND DATE(expected_closure_date)=CURDATE()");
    $expectedUpcoming = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE created_by='".$uid."' AND DATE(expected_closure_date)>CURDATE()");
}

// Partner Manager: show metrics for users in the same team_id
// Treat 'MNGR' and 'USR' the same for Partner team-level views
if ($sessionRole === 'Partner' && in_array($userType, ['MNGR','USR'], true)) {
    $teamId = (int)($_SESSION['team_id'] ?? 0);
    if ($teamId > 0) {
        $teamCondition = "created_by IN (SELECT id FROM users WHERE team_id='".$teamId."')";

        $totalLeadsTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition);
        $todayLeadsTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition . " AND DATE(created_at)=CURDATE()");

        // Approved (use is_approved flag for manager)
        $totalApprovedTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition . " AND is_approved=1");
        $todayApprovedTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition . " AND is_approved=1 AND DATE(created_at)=CURDATE()");

        // Opportunities
        $totalOpportunitiesTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition . " AND is_opportunity=1");
        $todayOpportunitiesTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition . " AND is_opportunity=1 AND DATE(created_at)=CURDATE()");

        // Expected closure dates: today and upcoming
        $expectedTodayTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition . " AND DATE(expected_closure_date)=CURDATE()");
        $expectedUpcomingTeam = (int)getSingleresult("SELECT COUNT(*) FROM orders WHERE " . $teamCondition . " AND DATE(expected_closure_date)>CURDATE()");
    } else {
        $totalLeadsTeam = $todayLeadsTeam = $totalApprovedTeam = $todayApprovedTeam = 0;
        $totalOpportunitiesTeam = $expectedTodayTeam = $expectedUpcomingTeam = 0;
        $todayOpportunitiesTeam = 0;
    }
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
                                <h1 style="margin:0;font-size:15px;font-weight:700;letter-spacing:0.2px">Hello <?php echo htmlspecialchars(ucwords($_SESSION['name'])); ?>, Welcome to Nitro Dashboard</h1>
                            </div>
                            <div style="font-size:13px;color:rgba(255,255,255,0.85)"><?= $_SESSION['user_type'] ?> PANEL</div>
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

            .card-blue{background:linear-gradient(135deg,#2563eb,#60a5fa);}
            .card-green{background:linear-gradient(135deg,#059669,#34d399);}
            .card-orange{background:linear-gradient(135deg,#f97316,#fb923c);}
            .card-red{background:linear-gradient(135deg,#dc2626,#f87171);}

            @media(max-width:900px){.dash-card{flex:1 1 calc(50% - 12px)}}
            @media(max-width:520px){.dash-card{flex:1 1 100%}}
            </style>

            <div class="dashboard-cards">
                <?php if ($sessionRole === 'Partner' && in_array($userType, ['MNGR','USR'])): ?>
                    <div class="dash-card card-blue">
                        <div class="title">Total Leads (Team)</div>
                        <div class="value"><?php echo number_format($totalLeadsTeam); ?></div>
                        <div class="muted">Today: <?php echo number_format($todayLeadsTeam); ?></div>
                    </div>

                    <div class="dash-card card-green">
                        <div class="title">Total Approved (Team)</div>
                        <div class="value"><?php echo number_format($totalApprovedTeam); ?></div>
                    </div>

                    <div class="dash-card card-orange">
                        <div class="title">Total Opportunity (Team)</div>
                        <div class="value"><?php echo number_format($totalOpportunitiesTeam); ?></div>
                    </div>

                    <div class="dash-card card-red">
                        <div class="title">Expected Closure Dates (Team)</div>
                        <div class="value"><?php echo number_format($expectedTodayTeam); ?></div>
                        <div class="muted">Upcoming Date: <?php echo number_format($expectedUpcomingTeam); ?></div>
                    </div>
                <?php elseif ($userType === 'CLR'): ?>
                    <div class="dash-card card-blue">
                        <div class="title">Total Leads</div>
                        <div class="value"><?php echo number_format($totalLeads); ?></div>
                        <div class="muted">Today: <?php echo number_format($todayLeads); ?></div>
                    </div>

                    <div class="dash-card card-green">
                        <div class="title">Total Approved</div>
                        <div class="value"><?php echo number_format($totalApproved); ?></div>
                    </div>

                    <div class="dash-card card-orange">
                        <div class="title">Total Opportunity</div>
                        <div class="value"><?php echo number_format($totalOpportunities); ?></div>
                    </div>

                    <div class="dash-card card-red">
                        <div class="title">Expected Closure Dates</div>
                        <div class="value"><?php echo number_format($expectedToday); ?></div>
                        <div class="muted">Upcoming Date: <?php echo number_format($expectedUpcoming); ?></div>
                    </div>
                <?php else: ?>
                    <div class="dash-card card-blue">
                        <div class="title">Total Leads</div>
                        <div class="value"><?php echo number_format($totalLeads); ?></div>
                        <div class="muted">Today: <?php echo number_format($todayLeads); ?></div>
                    </div>

                    <div class="dash-card card-green">
                        <div class="title">Total Approved</div>
                        <div class="value"><?php echo number_format($totalApproved); ?></div>
                    </div>

                    <div class="dash-card card-orange">
                        <div class="title">Total By Partner</div>
                        <div class="value"><?php echo number_format($totalByPartner); ?></div>
                        <div class="muted">Today: <?php echo number_format($todayByPartner); ?></div>
                    </div>

                    <div class="dash-card card-red">
                        <div class="title">Total By Internal</div>
                        <div class="value"><?php echo number_format($totalByInternal); ?></div>
                        <div class="muted">Today: <?php echo number_format($todayByInternal); ?></div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
