<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>New Request Created</h2>
    <p>A new request has been created with the following details:</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Request Reference</th>
                <th>Request Type</th>
                <th>Priority</th>
                <th>Resolution Time</th>
                <th>Account Type</th>
                <th>Requested By</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <a href="{{ $data['editLink'] }}" class="custom-link" title="Request Reference">{{ $data['requestReference'] }}</a>
                </td>
                <td>{{ $data['requestType'] }}</td>
                <td>{{ $data['priority'] }}</td>
                <td>{{ $data['resolutionTime'] }}</td>
                <td>{{ $data['accountType'] }}</td>
                <td>{{ $data['requestedBy'] }}</td>
                <td>{{ $data['description'] }}</td>
            </tr>
        </tbody>
    </table>
    <p>Thank you for using our system!</p>
</body>
</html>
