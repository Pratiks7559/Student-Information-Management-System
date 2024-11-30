<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            background: linear-gradient(135deg, #3a536b, #94003b);
            animation: gradientBG 8s infinite alternate;
        }

        @keyframes gradientBG {
            0% { background: linear-gradient(135deg, #3a536b, #94003b); }
            50% { background: linear-gradient(135deg, #94003b, #3a536b); }
            100% { background: linear-gradient(135deg, #3a536b, #94003b); }
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
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

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
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

        /* Adding a subtle fade-in animation for elements */
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
        <h2>Forgot Password</h2>
        <form action="forgot_password_process.php" method="POST">
            <div class="form-group">
                <label for="user_type">User Type:</label>
                <select id="user_type" name="user_type" required>
                    <option value="student">Student</option>
                    <option value="faculty">Faculty</option>
                </select>
            </div>

            <div class="form-group">
                <label for="user_id">PRN (for Students) / Employee ID (for Faculty):</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>

            <button type="submit" name="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
