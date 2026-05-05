<?php
include "includes/include.php";

// Get Lead ID from POST
$leadId = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
if ($leadId <= 0) {
    echo '<div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-body text-center text-danger p-5"><h4>Invalid Lead ID</h4></div></div></div>';
    exit;
}

// Fetch Lead Details using the correct column 'customer_company_name'
$leadQuery = db_query("SELECT id, customer_company_name, stage_id FROM orders WHERE id='".$leadId."'");
$leadData = db_fetch_array($leadQuery);

if (!$leadData) {
    echo '<div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-body text-center text-danger p-5"><h4>Lead not found</h4></div></div></div>';
    exit;
}

$companyName = $leadData['customer_company_name'] !== '' ? $leadData['customer_company_name'] : 'N/A';
$currentStageId = (int)$leadData['stage_id'];

// Fetch available stages from tbl_mst_stage
$stages = [];
$stageRes = db_query("SELECT id, name FROM tbl_mst_stage WHERE status=1 ORDER BY name ASC");
while ($sRow = db_fetch_array($stageRes)) {
    $stages[] = $sRow;
}
?>

<div class="modal-dialog modal-dialog-centered custom-stage-modal">
    <div class="modal-content overflow-hidden border-0">
        <!-- Close Button -->
        <button type="button" class="close custom-modal-close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <!-- Modern Header -->
        <div class="modal-header-premium">
            <div class="header-icon-wrapper">
                <i class="fa fa-sliders"></i>
            </div>
            <div class="header-content">
                <h5 class="modal-title">Update Stage</h5>
                <p class="modal-subtitle">Progress your opportunity to the next level</p>
            </div>
        </div>

        <div class="modal-body p-4">
            <!-- Info Card -->
            <div class="org-info-card mb-4">
                <div class="info-label">Company</div>
                <div class="info-value"><?= htmlspecialchars($companyName) ?></div>
            </div>
            
            <!-- Selection Area -->
            <div class="form-group mb-0">
                <label for="stageSelectModal" class="selection-label">
                    <span>Target Stage</span>
                    <span class="badge badge-soft-primary">Required</span>
                </label>
                <div class="select-wrapper">
                    <select id="stageSelectModal" class="form-control custom-select-premium">
                        <option value="">--- Select New Stage ---</option>
                        <?php foreach ($stages as $stage) { ?>
                            <option value="<?= $stage['id'] ?>" <?= ($stage['id'] == $currentStageId) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($stage['name']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Styled Footer -->
        <div class="modal-footer-premium px-4 py-3">
            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
            <button type="button" id="saveStageBtnModal" class="btn btn-update">
                <span class="btn-text">Update Now</span>
                <i class="fa fa-chevron-right ml-2"></i>
            </button>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    .custom-stage-modal {
        font-family: 'Outfit', sans-serif;
        max-width: 420px; /* Custom medium-small width */
    }

    .custom-stage-modal .modal-content {
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        background: #fff;
    }

    /* Header Styling */
    .modal-header-premium {
        background: linear-gradient(135deg, #1B274D 0%, #2D3E75 100%);
        padding: 20px 20px;
        display: flex;
        align-items: center;
        color: white;
        position: relative;
    }

    .header-icon-wrapper {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-right: 18px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .header-content .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        line-height: 1.2;
    }

    .header-content .modal-subtitle {
        font-size: 0.75rem;
        margin: 4px 0 0;
        opacity: 0.7;
        font-weight: 400;
    }

    /* Close Button */
    .custom-modal-close {
        position: absolute;
        right: 15px;
        top: 15px;
        z-index: 10;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 50%;
        opacity: 0.8 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        outline: none !important;
    }

    .custom-modal-close:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        opacity: 1 !important;
        transform: rotate(90deg);
    }

    /* Org Info Card */
    .org-info-card {
        background: #F8FAFC;
        border-radius: 16px;
        padding: 12px 16px;
        border: 1px solid #E2E8F0;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1E293B;
    }

    /* Form Styling */
    .selection-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #475569;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .badge-soft-primary {
        background: #E0E7FF;
        color: #4338CA;
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .select-wrapper {
        position: relative;
    }

    .custom-select-premium {
        height: 44px !important;
        border-radius: 12px !important;
        border: 2px solid #E2E8F0 !important;
        font-weight: 500 !important;
        color: #1E293B !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        padding: 0 16px !important;
        cursor: pointer;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 16px center !important;
        background-size: 18px !important;
        appearance: none !important;
    }

    .custom-select-premium:focus {
        border-color: #1B274D !important;
        box-shadow: 0 0 0 4px rgba(27, 39, 77, 0.1) !important;
        outline: none;
    }

    /* Footer & Buttons */
    .modal-footer-premium {
        background: #F8FAFC;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #EDF2F7;
        padding: 12px 20px !important;
    }

    .btn-cancel {
        color: #64748B;
        font-weight: 600;
        padding: 10px 20px;
        transition: color 0.2s;
        border: none;
        background: transparent;
    }

    .btn-cancel:hover {
        color: #1E293B;
        text-decoration: none;
    }

    .btn-update {
        background: #F05A28;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 8px 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(240, 90, 40, 0.25);
    }

    .btn-update:hover:not(:disabled) {
        background: #E04E1D;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(240, 90, 40, 0.35);
        color: white;
    }

    .btn-update:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-update:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Animations */
    .custom-stage-modal .modal-content {
        animation: modalScaleIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalScaleIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>

<script>
$(document).ready(function() {
    // Unbind previous clicks to prevent duplication
    $('#saveStageBtnModal').off('click').on('click', function() {
        var stageId = $('#stageSelectModal').val();
        var leadId = <?= $leadId ?>;
        var currentStageId = <?= $currentStageId ?>;
        
        if (!stageId) {
            swal({
                title: "Wait!",
                text: "Please select a target stage.",
                type: "warning",
                confirmButtonClass: "btn-warning"
            });
            return;
        }
        
        if (parseInt(stageId) === currentStageId) {
            swal({
                title: "No Change",
                text: "This lead is already at the selected stage.",
                type: "info",
                confirmButtonClass: "btn-info"
            });
            return;
        }

        var $btn = $(this);
        var originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i>Processing...');

        $.ajax({
            url: 'ajax_update.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'update_stage',
                lead_id: leadId,
                stage_id: stageId
            },
            success: function(response) {
                if (response.status === 'success') {
                    swal({
                        title: "Updated Successfully!",
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success"
                    }, function() {
                        // Close all potential modal IDs
                        $('#stage_modal, #myModal1, #stageUpdateModal').modal('hide');
                        
                        // Smart reload: refresh DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#leads')) {
                            $('#leads').DataTable().ajax.reload(null, false);
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    swal("Update Failed", response.message || "An error occurred", "error");
                    $btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                swal("Server Error", "Could not connect to update service. Please try again.", "error");
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>