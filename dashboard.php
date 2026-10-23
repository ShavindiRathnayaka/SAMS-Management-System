
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "db.php";

function getCount($conn, $table)
{
    $allowedTables = [
        "user",
        "exam_cycle",
        "examination",
        "moderator",
        "scrutiny_board",
        "examination_paper"
    ];

    if (!in_array($table, $allowedTables, true)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM `$table`";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return (int) $row["total"];
    }

    return 0;
}

$fullName = $_SESSION["full_name"] ?? "User";
$role = $_SESSION["role"] ?? "User";

$totalUsers = getCount($conn, "user");
$totalCycles = getCount($conn, "exam_cycle");
$totalExaminations = getCount($conn, "examination");
$totalModerators = getCount($conn, "moderator");
$totalBoards = getCount($conn, "scrutiny_board");
$totalPapers = getCount($conn, "examination_paper");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMS | Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        .navbar {
            background: #10243e;
            color: white;
            padding: 20px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .brand {
            font-size: 23px;
            font-weight: bold;
        }

        .brand span {
            color: #60a5fa;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border: 1px solid #64748b;
            border-radius: 7px;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .welcome p {
            color: #64748b;
            line-height: 1.6;
        }

        .role {
            display: inline-block;
            margin-top: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: bold;
        }

        .section-title {
            margin-bottom: 18px;
            font-size: 21px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 13px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
            border: 1px solid #e5eaf1;
        }

        .card h3 {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 16px;
        }

        .number {
            font-size: 34px;
            font-weight: bold;
            color: #10243e;
        }

        .quick-links {
            margin-top: 35px;
            background: white;
            padding: 25px;
            border-radius: 13px;
            border: 1px solid #e5eaf1;
        }

        .quick-links p {
            color: #64748b;
            margin-top: 10px;
            line-height: 1.6;
        }

        footer {
            text-align: center;
            padding: 30px 15px;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 750px) {
            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .navbar {
                padding: 18px 5%;
            }

            .welcome h1 {
                font-size: 25px;
            }
        }

        @media (max-width: 480px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="brand">SAMS<span>.</span></div>
        <a href="logout.php">Logout</a>
    </nav>

    <main class="container">

        <section class="welcome">
            <h1>Welcome, <?= htmlspecialchars($fullName) ?>!</h1>
            <p>
                Welcome to the Scrutiny Board Management System.
                Manage examination activities from one place.
            </p>
            <span class="role">
                <?= htmlspecialchars($role) ?>
            </span>
        </section>

        <h2 class="section-title">System Overview</h2>

        <section class="cards">

            <div class="card">
                <h3>Total Users</h3>
                <div class="number"><?= $totalUsers ?></div>
            </div>

            <div class="card">
                <h3>Examination Cycles</h3>
                <div class="number"><?= $totalCycles ?></div>
            </div>

            <div class="card">
                <h3>Examinations</h3>
                <div class="number"><?= $totalExaminations ?></div>
            </div>

            <div class="card">
                <h3>Moderators</h3>
                <div class="number"><?= $totalModerators ?></div>
            </div>

            <div class="card">
                <h3>Scrutiny Boards</h3>
                <div class="number"><?= $totalBoards ?></div>
            </div>

            <div class="card">
                <h3>Examination Papers</h3>
                <div class="number"><?= $totalPapers ?></div>
            </div>

        </section>

        <section class="quick-links">
            <h2>System Management</h2>
            <p>
                Dashboard overview is ready. Next, we will connect
                the examination cycles, examinations, moderators,
                scrutiny boards and reports to their management pages.
            </p>
            
<a href="exam_cycles.php"
   style="display:inline-block; margin-top:15px; padding:12px 18px;
          background:#2563eb; color:white; text-decoration:none;
          border-radius:7px;">
    Manage Examination Cycles
</a>

        </section>

    </main>

    <footer>
        &copy; 2026 SAMS - Scrutiny Board Management System
    </footer>

</body>
</html>
