<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: #f0f9fb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #2c3e50;
        }

        .container {
            text-align: center;
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 100%;
            box-shadow: 
                0px 1px 0px rgba(134, 201, 208, 0.6),
                inset 0px -2px 0px rgba(255, 255, 255, 0.5);
        }

        .emoji {
            font-size: 64px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 72px;
            color: #86c9d0;
            margin: 0;
        }

        h2 {
            font-size: 26px;
            margin: 10px 0;
            color: #34495e;
        }

        p {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        button {
            background-color: #86c9d0;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 
                0px 1px 0px rgba(134, 201, 208, 0.6),
                inset 0px -2px 0px rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #6fbac2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">🔍</div>
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you are looking for doesn't exist or may have been moved.</p>
        <button onclick="history.back()">⬅ Back to Previous Page</button>
    </div>
</body>
</html>
