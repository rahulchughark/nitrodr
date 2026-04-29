
    $(document).ready(function(){
        var leadId = 0;
        var isApproved = 0;
        var isOpportunity = 0;
        var canPerformActions = 0;
        var isUsrRole = 0;
        var isAdmin = 0;
        var currentStageId = 0;
        var isApprovalUpdating = false;
        var minLoaderDurationMs = 2500;

        function setGlobalAjaxLoading(isLoading, message) {
            if (isLoading) {
                $('#globalAjaxLoaderText').text(message || 'Please wait...');
                $('#globalAjaxLoader').css('display', 'flex');
            } else {
                $('#globalAjaxLoader').hide();
            }
        }

        // Reusable global helpers for any AJAX operation in this page.
        window.showAjaxLoader = function(message) {
            setGlobalAjaxLoading(true, message);
        };
        window.hideAjaxLoader = function() {
            setGlobalAjaxLoading(false);
        };

        function finishAfterMinLoader(startTime, done) {
            var elapsed = Date.now() - startTime;
            var wait = Math.max(0, minLoaderDurationMs - elapsed);
            setTimeout(done, wait);
        }

        function setApprovalLoading(isLoading) {
            isApprovalUpdating = isLoading;
            if (isLoading) {
                showAjaxLoader('Updating approval, please wait...');
            } else {
                hideAjaxLoader();
            }
            $('input[name="approvalRadio"]').prop('disabled', isLoading || (parseInt(isApproved, 10) === 1));
        }

        setTimeout(function() {
            $('#leadLogsUsrHint').fadeOut();
        }, 10000);

        var callLogStatus = '0';
        if (callLogStatus === 'success') {
            swal('Success', 'Call log added successfully.', 'success');
        } else if (callLogStatus === 'updated') {
            swal('Success', 'Call log updated successfully.', 'success');
        } else if (callLogStatus === 'error') {
            swal('Error', 'Failed to add call log.', 'error');
        } else if (callLogStatus === 'invalid') {
            swal('Warning', 'Please fill required fields.', 'warning');
        }

        if (callLogStatus !== '') {
            var cleanUrl = new URL(window.location.href);
            cleanUrl.searchParams.delete('calllog');
            window.history.replaceState({}, document.title, cleanUrl.toString());
        }

        $('.call-log-read-more').on('click', function() {
            var $link = $(this);
            var $container = $link.closest('td');
            var $shortText = $container.find('.call-log-desc-short');
            var $fullText = $container.find('.call-log-desc-full');
            var isExpanded = $fullText.is(':visible');

            if (isExpanded) {
                $fullText.hide();
                $shortText.show();
                $link.text('Read more');
            } else {
                $shortText.hide();
                $fullText.show();
                $link.text('Read less');
            }
        });

        $('.lead-comment-toggle').on('click', function() {
            var $link = $(this);
            var $container = $link.closest('.view-value');
            var $shortText = $container.find('.lead-comment-short');
            var $fullText = $container.find('.lead-comment-full');
            var isExpanded = $fullText.is(':visible');

            if (isExpanded) {
                $fullText.hide();
                $shortText.show();
                $link.text('Read more');
            } else {
                $shortText.hide();
                $fullText.show();
                $link.text('Read less');
            }
        });

        $('.edit-call-log-btn').on('click', function() {
            var callLogId = $(this).data('id') || 0;
            var callSubject = $(this).data('subject') || '';
            var callDescription = $(this).data('description') || '';

            $('#call_log_id').val(callLogId);
            $('#call_subject').val(callSubject);
            $('#call_description').val(callDescription);
            $('#callLogModalTitle').text('Edit Call Log');
            $('#callLogSubmitBtn').text('Update');
            $('#callLogModal').modal('show');
        });

        $('#callLogModal').on('hidden.bs.modal', function() {
            $('#call_log_id').val(0);
            $('#call_subject').val('');
            $('#call_description').val('');
            $('#callLogModalTitle').text('Add Call Log');
            $('#callLogSubmitBtn').text('Submit');
        });

        function setApprovalUI() {
            var isLocked = (parseInt(isApproved, 10) === 1);
            // Map numeric states to labels and badge classes
            var state = parseInt(isApproved, 10) || 0;
            var label = 'Pending';
            var badgeClass = 'status-pending';
            if (state === 1) { label = 'Approved'; badgeClass = 'status-approved'; }
            else if (state === 2) { label = 'Rejected'; badgeClass = 'status-rejected'; }
            else if (state === 3) { label = 'Onhold'; badgeClass = 'status-onboard'; }
            else { label = 'Pending'; badgeClass = 'status-pending'; }

            // top badge
            $('#approvalBadge').removeClass('status-pending status-approved status-rejected status-onboard').addClass(badgeClass).text(label);

            // inline action badge (near control) if present
            var $actionBadge = $('#approvalActionBadge');
            if ($actionBadge.length) {
                $actionBadge.removeClass('status-pending status-approved status-rejected status-onboard').addClass(badgeClass).text(label);
            }

            // update segmented control active state and accessibility
            var $labels = $('#approvalSegment .segmented label');
            $labels.removeClass('active').attr('aria-pressed', 'false');
            $labels.each(function(){
                var $lab = $(this);
                var val = parseInt($lab.data('value'), 10);
                if (val === state) {
                    $lab.addClass('active').attr('aria-pressed', 'true');
                    $lab.find('input[name="approvalRadio"]').prop('checked', true).prop('disabled', isLocked);
                } else {
                    $lab.find('input[name="approvalRadio"]').prop('checked', false).prop('disabled', isLocked);
                }
            });

            // toggle container highlight
            var $approvalWrap = $('#approvalSegment');
            if (isLocked) {
                $approvalWrap.addClass('disabled');
                $approvalWrap.attr('title', 'Now you cannot change the approval status because it is Approved now');
            } else {
                $approvalWrap.removeClass('disabled');
                $approvalWrap.attr('title', '');
            }
            if ($approvalWrap.find('.segmented label.active').length) {
                $approvalWrap.addClass('active-container');
            } else {
                $approvalWrap.removeClass('active-container');
            }
        }

        function setOpportunityUI() {
            var opportunitySwitch = $('#toggleOpportunitySwitch');
            if (isOpportunity) {
                opportunitySwitch.prop('checked', true);
                $('#opportunityToggleText').text('Yes');
                $('#opportunityBadge').removeClass('opportunity-no').addClass('opportunity-yes').text('Opportunity: Yes');
            } else {
                opportunitySwitch.prop('checked', false);
                $('#opportunityToggleText').text('No');
                $('#opportunityBadge').removeClass('opportunity-yes').addClass('opportunity-no').text('Opportunity: No');
            }
        }

        setApprovalUI();
        if (canPerformActions && isAdmin) {
            setOpportunityUI();
        }

        $('.upload-preview-thumb').on('click', function(){
            var src = $(this).attr('src');
            if (src) {
                $('#uploadPreviewImage').attr('src', src);
                $('#uploadPreviewModal').modal('show');
            }
        });

        $('#openStageModalBtn').on('click', function(){
            $('#stageSelectModal').val(String(currentStageId));
            $('#stageUpdateModal').modal('show');
        });

            // Handle segmented radio change for approval (values 0..3)
            $(document).on('change', 'input[name="approvalRadio"]', function(){
                if (isApprovalUpdating) {
                    return;
                }

                var $input = $(this);
                var newStatus = parseInt($input.val(), 10);
                var previousState = parseInt(isApproved, 10) || 0;

                if (newStatus === 2 || newStatus === 3) {
                    $('#modal_approval_lead_id').val(leadId);
                    $('#modal_approval_status').val(newStatus);
                    $('#modal_reason_id').val('');
                    $('#modal_custom_reason').val('');
                    $('#modal_custom_reason_wrapper').hide();
                    $('#approvalReasonModal').modal('show');
                    return;
                }

                if (newStatus === 1) {
                    $('#modal_price_lead_id').val(leadId);
                    $('#modal_price_status').val(newStatus);
                    $('#modal_approval_price').val('');
                    $('#approvalPriceModal').modal('show');
                    return;
                }

                var confirmText = 'Are you sure you want to set this lead to "Pending"?';

                swal({
                    title: 'Change Approval Status?',
                    text: confirmText,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: 'Yes, Continue',
                    cancelButtonText: 'Cancel',
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if(isConfirm){
                        if (typeof swal.close === 'function') {
                            swal.close();
                        }
                        setApprovalLoading(true);
                        var approvalLoaderStart = Date.now();
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_update.php',
                            data: {
                                action: 'update_approval',
                                lead_id: leadId,
                                is_approved: newStatus
                            },
                            dataType: 'json',
                            success: function(response){
                                finishAfterMinLoader(approvalLoaderStart, function() {
                                    setApprovalLoading(false);
                                    if(response.status === 'success'){
                                        isApproved = newStatus;
                                        setApprovalUI();
                                        swal('Success', response.message, 'success');
                                        setTimeout(function(){ window.location.reload(); }, 1500);
                                    } else {
                                        // revert selection
                                        setTimeout(function(){
                                            isApproved = previousState;
                                            setApprovalUI();
                                        }, 10);
                                        swal('Error', response.message, 'error');
                                    }
                                });
                            },
                            error: function(){
                                finishAfterMinLoader(approvalLoaderStart, function() {
                                    setApprovalLoading(false);
                                    isApproved = previousState;
                                    setApprovalUI();
                                    swal('Error', 'Something went wrong. Please try again.', 'error');
                                });
                            }
                        });
                    } else {
                        // revert selection
                        setTimeout(function(){
                            isApproved = previousState;
                            setApprovalUI();
                        }, 10);
                    }
                });
            });

            $('#approvalPriceModal').on('hidden.bs.modal', function () {
                isApproved = parseInt(isApproved, 10) || 0;
                setApprovalUI();
            });

            $('#btn_save_approval_price').on('click', function() {
                var id = $('#modal_price_lead_id').val();
                var status = $('#modal_price_status').val();
                var price = $('#modal_approval_price').val();

                if (!price || parseFloat(price) < 0) {
                    swal("Error!", "Please enter a valid price.", "error");
                    return;
                }

                swal({
                    title: "Confirm Status Update?",
                    text: "Are you sure you want to submit this price and approve the lead?\nNote: After approve status you cannot change it again.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#667eea",
                    confirmButtonText: "Yes, Proceed",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true
                }, function(isConfirm) {
                    if (!isConfirm) return;

                    $('#modal_price_lead_id').val('');
                    $('#approvalPriceModal').modal('hide');

                    var loaderStart = Date.now();
                    showAjaxLoader('Updating approval status, please wait...');

                    $.ajax({
                        url: "ajax_update.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            action: 'update_approval',
                            lead_id: id,
                            is_approved: status,
                            price: price
                        },
                        success: function(response) {
                            finishAfterMinLoader(loaderStart, function() {
                                hideAjaxLoader();
                                if (response.status === "success") {
                                    swal("Success!", response.message, "success");
                                    isApproved = parseInt(status, 10);
                                    setApprovalUI();
                                    setTimeout(function(){ window.location.reload(); }, 1500);
                                } else {
                                    swal("Error!", response.message || "Update failed", "error");
                                    setApprovalUI();
                                }
                            });
                        },
                        error: function() {
                            finishAfterMinLoader(loaderStart, function() {
                                hideAjaxLoader();
                                swal("Error!", "Server error occurred.", "error");
                                setApprovalUI();
                            });
                        }
                    });
                });
            });

            $('#approvalReasonModal').on('hidden.bs.modal', function () {
                isApproved = parseInt(isApproved, 10) || 0;
                setApprovalUI();
            });

            $('#modal_reason_id').on('change', function() {
                var isOther = $(this).find('option:selected').data('is-other');
                if (isOther == '1') {
                    $('#modal_custom_reason_wrapper').show();
                } else {
                    $('#modal_custom_reason_wrapper').hide();
                }
            });

            $('#btn_save_approval_reason').on('click', function() {
                var id = $('#modal_approval_lead_id').val();
                var status = $('#modal_approval_status').val();
                var reasonId = $('#modal_reason_id').val();
                var isOther = $('#modal_reason_id option:selected').data('is-other');
                var customReason = $('#modal_custom_reason').val();

                if (!reasonId) {
                    swal("Error!", "Please select a reason.", "error");
                    return;
                }

                if (isOther == '1' && !customReason.trim()) {
                    swal("Error!", "Please enter a custom reason.", "error");
                    return;
                }

                swal({
                    title: "Confirm Status Update?",
                    text: "Are you sure you want to submit this reason and update the approval status?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#667eea",
                    confirmButtonText: "Yes, Proceed",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true
                }, function(isConfirm) {
                    if (!isConfirm) return;

                    $('#modal_approval_lead_id').val('');
                    $('#approvalReasonModal').modal('hide');

                    var loaderStart = Date.now();
                    showAjaxLoader('Updating approval status, please wait...');

                    $.ajax({
                        url: "ajax_update.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            action: 'update_approval',
                            lead_id: id,
                            is_approved: status,
                            reason_id: reasonId,
                            custom_reason: customReason
                        },
                        success: function(response) {
                            finishAfterMinLoader(loaderStart, function() {
                                hideAjaxLoader();
                                if (response.status === "success") {
                                    swal("Success!", response.message, "success");
                                    isApproved = parseInt(status, 10);
                                    setApprovalUI();
                                    setTimeout(function(){ window.location.reload(); }, 1500);
                                } else {
                                    swal("Error!", response.message || "Update failed", "error");
                                    setApprovalUI();
                                }
                            });
                        },
                        error: function() {
                            finishAfterMinLoader(loaderStart, function() {
                                hideAjaxLoader();
                                swal("Error!", "Server error occurred.", "error");
                                setApprovalUI();
                            });
                        }
                    });
                });
            });

        $('#toggleOpportunitySwitch').on('change', function(){
            var newStatus = $(this).is(':checked') ? 1 : 0;

            // Prevent changing opportunity unless lead is Approved (1)
            if (parseInt(isApproved, 10) !== 1) {
                swal('Warning', 'Only approved leads can be marked as opportunity.', 'warning');
                // revert to previous state
                $(this).prop('checked', !!isOpportunity);
                return;
            }

            var actionText = newStatus ? 'save as opportunity' : 'remove from opportunity';

            swal({
                title: 'Change Opportunity Status?',
                text: 'Are you sure you want to ' + actionText + ' this lead?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if(isConfirm){
                    showAjaxLoader('Updating opportunity, please wait...');
                    var opportunityLoaderStart = Date.now();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_update.php',
                        data: {
                            lead_id: leadId,
                            action: 'update_opportunity',
                            is_opportunity: newStatus
                        },
                        dataType: 'json',
                        success: function(response){
                            finishAfterMinLoader(opportunityLoaderStart, function() {
                                hideAjaxLoader();
                                if(response.status === 'success'){
                                    isOpportunity = newStatus;
                                    setOpportunityUI();
                                    swal('Success', response.message, 'success');
                                    setTimeout(function(){ window.location.reload(); }, 1500);
                                } else {
                                    swal('Error', response.message, 'error');
                                }
                            });
                        },
                        error: function(){
                            finishAfterMinLoader(opportunityLoaderStart, function() {
                                hideAjaxLoader();
                                $('#toggleOpportunitySwitch').prop('checked', !!isOpportunity);
                                swal('Error', 'Something went wrong. Please try again.', 'error');
                            });
                        }
                    });
                } else {
                    $('#toggleOpportunitySwitch').prop('checked', !!isOpportunity);
                }
            });
        });

        $('#saveStageBtn').on('click', function(){
            var stageId = parseInt($('#stageSelectModal').val(), 10) || 0;

            if (stageId <= 0) {
                swal('Warning', 'Please select a stage.', 'warning');
                return;
            }

            if (stageId === currentStageId) {
                swal('Info', 'Selected stage is already set.', 'info');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'ajax_update.php',
                data: {
                    action: 'update_stage',
                    lead_id: leadId,
                    stage_id: stageId
                },
                dataType: 'json',
                beforeSend: function() {
                    showAjaxLoader('Updating stage, please wait...');
                    $('#saveStageBtn').data('loader-start', Date.now());
                },
                success: function(response){
                    var stageLoaderStart = parseInt($('#saveStageBtn').data('loader-start'), 10) || Date.now();
                    finishAfterMinLoader(stageLoaderStart, function() {
                        hideAjaxLoader();
                        if(response.status === 'success'){
                            currentStageId = stageId;
                            $('#stageNameText').text($('#stageSelectModal option:selected').text());
                            $('#stageUpdateModal').modal('hide');
                            swal('Success', response.message, 'success');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            swal('Error', response.message, 'error');
                        }
                    });
                },
                error: function(){
                    var stageLoaderStart = parseInt($('#saveStageBtn').data('loader-start'), 10) || Date.now();
                    finishAfterMinLoader(stageLoaderStart, function() {
                        hideAjaxLoader();
                        swal('Error', 'Something went wrong. Please try again.', 'error');
                    });
                }
            });
        });
    });
