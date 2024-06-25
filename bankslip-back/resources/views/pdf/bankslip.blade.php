<!DOCTYPE html>
<html>
<head>
    <title>Bank Slip</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Bank Slip</h1>
    <p>Name: {{ $bankslip['name'] }}</p>
    <p>Government ID: {{ $bankslip['governmentId'] }}</p>
    <p>Email: {{ $bankslip['email'] }}</p>
    <p>Debt Amount: {{ $bankslip['debtAmount'] }}</p>
    <p>Debt Due Date: {{ $bankslip['debtDueDate'] }}</p>
    <p>Debt ID: {{ $bankslip['debtId'] }}</p>
</body>
</html>
