<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f9;
        }

        /* NAVBAR */
        .navbar {
            height: 60px;
            background: #9370DB;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .navbar h3 {
            margin: 0;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            height: 100vh;
            background: #6A5ACD;
            position: fixed;
            top: 60px;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #7B68EE;
        }

        /* MAIN */
        .main {
            margin-left: 220px;
            margin-top: 60px;
            padding: 20px;
        }

        /* CARDS */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin: 0;
            color: #6A5ACD;
        }

        .logout {
            color: white;
            text-decoration: none;
            background: #FF6B6B;
            padding: 8px 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>