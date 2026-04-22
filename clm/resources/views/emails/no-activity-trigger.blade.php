<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <p>Hi Sir,</p>
  <p>Please find below the overall task status report of ICT CLM.</p>           
  <table border="1" class="x_table x_table-hover" style="border-collapse: collapse; border-color: #ddd;">
    <thead>
      <tr style="background: #cbe3e5;">
        <th style="padding: 5px;">SR NO</th>
        <th style="padding: 5px;">Assigned Spoc</th>
        <th style="padding: 5px;">Task Assigned</th>
        <th style="padding: 5px;">Completed Task</th>
        <th style="padding: 5px;">Task Activity</th>
        <th style="padding: 5px;">No Activity</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($emailData as $index => $item)
        <tr>
            <td style="padding: 2px 5px;">{{ $index + 1 }}</td>
            <td style="padding: 2px 5px;">{{ $item['assign_name'] }}</td>
            <td style="padding: 2px 5px;">{{ $item['total_task_assign'] }}</td>
            <td style="padding: 2px 5px;">{{ $item['completed'] }}</td>
            <td style="padding: 2px 5px;">{{ $item['total_task_activity'] }}</td>
            <td style="padding: 2px 5px;">{{ $item['no_activity'] }}</td>
          </tr>
        @endforeach
    </tbody>
  </table>
  <a href="{{$emailData[0]['link']}}" data-auth="NotApplicable" rel="noopener noreferrer" target="_blank" data-linkindex="0" style="color: #0E1426;font-size: 16px;font-weight: 600;background-color: #86C9D0;border-radius: 4px;border: none;padding: 10px 15px;display: inline-block;text-decoration: none;margin-top: 15px;">Click here to access the CLM Portal.</a>
</div>

</body>
</html>
