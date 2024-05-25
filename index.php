<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Create a connection to an internal database (SQLite)
        $conn = new PDO("sqlite::memory:");

        // Enable error mode to display errors if they occur
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create a new table in the database
        $conn->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            username TEXT,
            password TEXT
        )");

        // Add some sample data to the table
        $conn->exec("INSERT INTO users (username, password) VALUES ('admin', 'admin')");
        $conn->exec("INSERT INTO users (username, password) VALUES ('user1', 'user1')");

        // Vulnerable query to check if the user exists with the provided username and password
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $stmt = $conn->query($query);

        // Fetch the user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Login Successful</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #000;
                        color: #fff;
                        text-align: center;
                    }
                    .container {
                        width: 300px;
                        margin: 0 auto;
                        margin-top: 100px;
                        background-color: rgba(0, 0, 0, 0.5);
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    }
                    h2 {
                        text-align: center;
                        margin-bottom: 30px;
                        color: #00ff00;
                    }
                    .success {
                        color: #00ff00;
                        margin-top: 10px;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
            <div class="container">
                <h2>Login Successful!</h2>
                <p class="success">Welcome, ' . htmlspecialchars($user['username']) . '!<br>Flag is CTF{welc0m_N00b}</p>
            </div>
            </body>
            </html>';
        } else {
            // Redirect back to login page with error
            header("Location: challenge1.php?error=true");
            exit();
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect back to login page if no POST data
    header("Location: challenge1.php");
    exit();
}
?>
