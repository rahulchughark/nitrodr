let url = "";

function GetdataByTaskStatus(mstID, columnName, forpage) {
    if (forpage === "trainer") {
        url = "../reports/trainer-wise-popup-data";
    } else if (forpage === "no-action-report") {
        url = "../../reports/trainer-wise-popup-data";
    }
    
    $.ajax({
        url: url,
        cache: true,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            mstID: mstID,
            status: columnName,
            forpage: forpage
        },
        beforeSend: function() {
            $('#exampleModalCenterSchool').modal('hide');
            $("#headName").html('');
            $('#modalContent').html('');
            $("#spinner").show();
        },
        success: function(response) {
            if (response) {
                // Clear and destroy DataTable if it exists
                if ($.fn.DataTable.isDataTable('#popuppaginate-1')) {
                    $('#popuppaginate-1').DataTable().clear().destroy();
                }

                if (forpage === "no-action-report" && columnName) {
                    $("#headName").html(columnName);
                } else {
                    $("#headName").html(columnName);
                }
                
                $('#modalContent').html(response);
                $('#exampleModalCenterSchool').modal('show');
                
                // Load DataTable script dynamically
                var script = document.createElement('script');
                script.src = popupPaginateUrl;
                document.head.appendChild(script);
            }
        },
        complete: function() {
            $('#spinner').hide();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        },
    });

    // Modal hide event to clear the DataTable and modal content
    $('#exampleModalCenterSchool').on('hidden.bs.modal', function() {
        // Clear the DataTable if it exists
        if ($.fn.DataTable.isDataTable('#popuppaginate-1')) {
            $('#popuppaginate-1').DataTable().clear().destroy();
        }
        
        // Clear modal content
        $('#modalContent').html('');
        $("#headName").html('');
    });
}
