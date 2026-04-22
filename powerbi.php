<!DOCTYPE html>
<html>
<head>
    <title>Power BI Report</title>
    <script src="https://cdn.jsdelivr.net/npm/powerbi-client@2.19.1/dist/powerbi.min.js"></script>
</head>
<body>

<div id="reportContainer" style="height:600px;"></div>

<script>

fetch('powerbi_api.php')
.then(res => res.json())
.then(data => {

    var models = window['powerbi-client'].models;

    var config = {
        type: 'report',
        tokenType: models.TokenType.Embed,
        accessToken: data.embedToken,
        embedUrl: data.embedUrl,
        id: data.reportId,
        settings: {
            filterPaneEnabled: false,
            navContentPaneEnabled: true
        }
    };

    var container = document.getElementById('reportContainer');
    powerbi.embed(container, config);

});

</script>

</body>
</html>