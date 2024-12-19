<!DOCTYPE html>
<html>
<head>
    <title>New Request Assignment</title>
</head>
<body>
    <h1>Hello, {{ $user->fname }} {{ $user->lname }}</h1>
    <p>You have been assigned a new request</p>
    <p><strong>Request Reference:</strong> {{ $request->request_refference }}</p>
    <p>Please log in to your dashboard to review and handle the request.</p>
    <p>Thank you!</p>
</body>
</html>
