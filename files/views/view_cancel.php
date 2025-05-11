<!-- Author     : Christopher Yap Jian Xing -->

<!DOCTYPE html>
<html>
<head>
    <title>Payment Canceled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff0f0;
            text-align: center;
            padding: 50px;
        }

        .cancel-box {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
        }

        h1 {
            color: #dc3545;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="cancel-box">
        <h1>Payment Canceled</h1>
        <p>Your transaction was canceled. No charges were made.</p>
        <a href="../userIndex&module=cart&action=view">Try Again</a>
    </div>

</body>
</html>
