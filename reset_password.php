<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #94003b, #3a536b);
            animation: gradientBG 6s infinite alternate;
        }

        @keyframes gradientBG {
            0% { background: linear-gradient(135deg, #94003b, #3a536b); }
            50% { background: linear-gradient(135deg, #3a536b, #94003b); }
            100% { background: linear-gradient(135deg, #94003b, #3a536b); }
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            0% { transform: translateY(-30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #3a536b;
            font-size: 1.8rem;
            position: relative;
        }

        h2:after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background-color: #94003b;
            margin: 8px auto 0;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 1rem;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #94003b;
            outline: none;
            box-shadow: 0px 0px 5px rgba(148, 0, 59, 0.5);
        }

        button {
            width: 100%;
            padding: 10px;
            background: #3a536b;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: #94003b;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(1px);
        }

        /* Fade-in Effect */
        .form-group, button {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <input type="hidden" name="user_type" value="<?php echo $_GET['user_type']; ?>">

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <button type="submit" name="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
