<!-- resources/views/emails/request_rejected.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Your Request Has Been Rejected</title>
</head>
<body>
    <h1>Hello, {{ $request->user->fname }} {{ $request->user->lname }}</h1>
    <p>We regret to inform you that your request (ID: {{ $request->request_refference }}) has been rejected.</p>
    <p><strong>Comment:</strong> {{ $request->comment }}</p>
    <p>You can review your request status at any time by visiting the <a href="{{ url('/requests.rejectedRequests') }}">Requests page</a>.</p>
    <p>Thank you for your understanding!</p>
</body>
</html>
