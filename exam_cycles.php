
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "db.php";

$message = "";
$messageType = "";

function validDate($date)
{
    if ($date === null || $date === "") {
        return null;
    }

    $parsed = DateTime::createFromFormat("!Y-m-d", $date);

    return $parsed && $parsed->format("Y-m-d") === $date
        ? $date
        : false;
}

function getCycle($conn, $id)
{
    $stmt = $conn->prepare(
        "SELECT * FROM exam_cycle WHERE cycle_id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $cycle = $result->fetch_assoc();

    $stmt->close();

    return $cycle;
}

/*
 * Add, update and delete actions
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    /*
     * ADD CYCLE
     */
    if ($action === "add") {
        $cycleName = trim($_POST["cycle_name"] ?? "");
        $academicYear = filter_var(
            $_POST["academic_year"] ?? "",
            FILTER_VALIDATE_INT
        );
        $startDate = validDate($_POST["start_date"] ?? "");
        $endDate = validDate($_POST["end_date"] ?? "");
        $status = $_POST["status"] ?? "Planned";
        $managedBy = (int) $_SESSION["user_id"];

        if (
            $cycleName === "" ||
            strlen($cycleName) > 100 ||
            $academicYear === false ||
            $academicYear < 2000 ||
            $academicYear > 2100
        ) {
            $message = "Enter a valid cycle name and academic year.";
            $messageType = "error";
        } elseif (
            $startDate === false ||
            $endDate === false ||
            !in_array($status, ["Planned", "Ongoing", "Completed"], true)
        ) {
            $message = "Please check the dates and status.";
            $messageType = "error";
        } elseif ($startDate && $endDate && $endDate < $startDate) {
            $message = "End date cannot be earlier than start date.";
            $messageType = "error";
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO exam_cycle
                (cycle_name, academic_year, start_date, end_date, status, managed_by)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sisssi",
                $cycleName,
                $academicYear,
                $startDate,
                $endDate,
                $status,
                $managedBy
            );

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: exam_cycles.php?added=1");
                exit();
            }

            $message = "Could not add the examination cycle.";
            $messageType = "error";
            $stmt->close();
        }
    }

    /*
     * UPDATE CYCLE
     */
    elseif ($action === "update") {
        $id = filter_var(
            $_POST["cycle_id"] ?? "",
            FILTER_VALIDATE_INT
        );

        $cycleName = trim($_POST["cycle_name"] ?? "");
        $academicYear = filter_var(
            $_POST["academic_year"] ?? "",
            FILTER_VALIDATE_INT
        );
        $startDate = validDate($_POST["start_date"] ?? "");
        $endDate = validDate($_POST["end_date"] ?? "");
        $status = $_POST["status"] ?? "";

        if (
            !$id ||
            $cycleName === "" ||
            strlen($cycleName) > 100 ||
            $academicYear === false ||
            $academicYear < 2000 ||
            $academicYear > 2100
        ) {
            $message = "Please enter valid cycle details.";
            $messageType = "error";
        } elseif (
            $startDate === false ||
            $endDate === false ||
            !in_array($status, ["Planned", "Ongoing", "Completed"], true)
        ) {
            $message = "Please check the dates and status.";
            $messageType = "error";
        } elseif ($startDate && $endDate && $endDate < $startDate) {
            $message = "End date cannot be earlier than start date.";
            $messageType = "error";
        } elseif (!getCycle($conn, $id)) {
            $message = "Examination cycle not found.";
            $messageType = "error";
        } else {
            $stmt = $conn->prepare(
                "UPDATE exam_cycle
                 SET cycle_name = ?, academic_year = ?,
                     start_date = ?, end_date = ?, status = ?
                 WHERE cycle_id = ?"
            );

            $stmt->bind_param(
                "sisssi",
                $cycleName,
                $academicYear,
                $startDate,
                $endDate,
                $status,
                $id
            );

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: exam_cycles.php?updated=1");
                exit();
            }

            $message = "Could not update the examination cycle.";
            $messageType = "error";
            $stmt->close();
        }
    }

    /*
     * SAFE DELETE
     */
    elseif ($action === "delete") {
        $id = filter_var(
            $_POST["cycle_id"] ?? "",
            FILTER_VALIDATE_INT
        );

        if (!$id || !getCycle($conn, $id)) {
            $message = "Examination cycle not found.";
            $messageType = "error";
        } else {
            /*
             * Check every declared foreign key that references
             * exam_cycle.cycle_id.
             */
            $check = $conn->prepare(
                "SELECT TABLE_NAME, COLUMN_NAME
                 FROM information_schema.KEY_COLUMN_USAGE
                 WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
                   AND REFERENCED_TABLE_NAME = 'exam_cycle'
                   AND REFERENCED_COLUMN_NAME = 'cycle_id'"
            );

            $check->execute();
            $references = $check->get_result();

            $hasReferences = false;

            while ($ref = $references->fetch_assoc()) {
                $table = str_replace("`", "``", $ref["TABLE_NAME"]);
                $column = str_replace("`", "``", $ref["COLUMN_NAME"]);

                $countSql = "SELECT COUNT(*) AS total
                             FROM `$table`
                             WHERE `$column` = ?";

                $countStmt = $conn->prepare($countSql);
                $countStmt->bind_param("i", $id);
                $countStmt->execute();

                $countResult = $countStmt->get_result();
                $countRow = $countResult->fetch_assoc();

                if ((int) $countRow["total"] > 0) {
                    $hasReferences = true;
                }

                $countStmt->close();

                if ($hasReferences) {
                    break;
                }
            }

            $check->close();

            if ($hasReferences) {
                $message =
                    "This cycle cannot be deleted because other records refer to it. " .
                    "Keep the cycle or review its related records first.";
                $messageType = "error";
            } else {
                try {
                    $stmt = $conn->prepare(
                        "DELETE FROM exam_cycle WHERE cycle_id = ?"
                    );
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->close();

                    header("Location: exam_cycles.php?deleted=1");
                    exit();
                } catch (mysqli_sql_exception $e) {
                    $message =
                        "The cycle could not be deleted because the database " .
                        "protects related records.";
                    $messageType = "error";
                }
            }
        }
    }
}

/*
 * Messages after redirects
 */
if (isset($_GET["added"])) {
    $message = "Examination cycle added successfully!";
    $messageType = "success";
} elseif (isset($_GET["updated"])) {
    $message = "Examination cycle updated successfully!";
    $messageType = "success";
} elseif (isset($_GET["deleted"])) {
    $message = "Examination cycle deleted successfully!";
    $messageType = "success";
}

/*
 * Load the cycle selected for editing
 */
$editCycle = null;

if (isset($_GET["edit"])) {
    $editId = filter_var($_GET["edit"], FILTER_VALIDATE_INT);

    if ($editId) {
        $editCycle = getCycle($conn, $editId);
    }

    if (!$editCycle) {
        $message = "The selected examination cycle was not found.";
        $messageType = "error";
    }
}

/*
 * Load all cycles
 */
$result = $conn->query(
    "SELECT cycle_id, cycle_name, academic_year,
            start_date, end_date, status
     FROM exam_cycle
     ORDER BY cycle_id DESC"
);

if (!$result) {
    die("Unable to load examination cycles.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMS | Examination Cycles</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        header {
            background: #10243e;
            color: white;
            padding: 20px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 22px;
        }

        .back {
            color: #2563eb;
            text-decoration: none;
        }

        .subtitle { color: #64748b; }

        .panel {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin: 25px 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
        }

        button, .button {
            display: inline-block;
            padding: 10px 15px;
            border: 0;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .primary { background: #2563eb; color: white; }
        .edit { background: #dbeafe; color: #1d4ed8; }
        .delete { background: #fee2e2; color: #b91c1c; }
        .cancel { background: #e2e8f0; color: #334155; }

        .message {
            padding: 13px;
            border-radius: 7px;
            margin: 20px 0;
        }

        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th, td {
            text-align: left;
            padding: 13px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        th { background: #f8fafc; }

        .actions {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .actions form { margin: 0; }

        .status {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .panel { padding: 17px; }
        }
    </style>
</head>

<body>

<header>
    <strong>SAMS | Examination Management</strong>
    <a href="dashboard.php">← Dashboard</a>
</header>

<main class="container">

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

    <h1>Examination Cycles</h1>
    <p class="subtitle">Create, view and manage examination cycles.</p>

    <?php if ($message !== ""): ?>
        <div class="message <?= htmlspecialchars($messageType) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <section class="panel">
        <h2>
            <?= $editCycle ? "Edit Examination Cycle" : "Add New Examination Cycle" ?>
        </h2>

        <form method="POST" action="exam_cycles.php">
            <input
                type="hidden"
                name="action"
                value="<?= $editCycle ? "update" : "add" ?>"
            >

            <?php if ($editCycle): ?>
                <input type="hidden" name="cycle_id"
                       value="<?= (int) $editCycle["cycle_id"] ?>">
            <?php endif; ?>

            <div class="form-grid">

                <div>
                    <label for="cycle_name">Cycle Name *</label>
                    <input type="text" id="cycle_name" name="cycle_name"
                           maxlength="100" required
                           value="<?= htmlspecialchars($editCycle["cycle_name"] ?? "") ?>">
                </div>

                <div>
                    <label for="academic_year">Academic Year *</label>
                    <input type="number" id="academic_year" name="academic_year"
                           min="2000" max="2100" required
                           value="<?= htmlspecialchars((string) ($editCycle["academic_year"] ?? "")) ?>">
                </div>

                <div>
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date"
                           value="<?= htmlspecialchars($editCycle["start_date"] ?? "") ?>">
                </div>

                <div>
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date"
                           value="<?= htmlspecialchars($editCycle["end_date"] ?? "") ?>">
                </div>

                <div>
                    <label for="status">Status</label>
                    <?php $currentStatus = $editCycle["status"] ?? "Planned"; ?>

                    <select id="status" name="status">
                        <?php foreach (["Planned", "Ongoing", "Completed"] as $option): ?>
                            <option value="<?= $option ?>"
                                <?= $currentStatus === $option ? "selected" : "" ?>>
                                <?= $option ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <button class="primary" type="submit">
                <?= $editCycle ? "Save Changes" : "Add Examination Cycle" ?>
            </button>

            <?php if ($editCycle): ?>
                <a class="button cancel" href="exam_cycles.php">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="panel">
        <h2>Existing Examination Cycles</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cycle Name</th>
                        <th>Academic Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($cycle = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= (int) $cycle["cycle_id"] ?></td>
                            <td><?= htmlspecialchars($cycle["cycle_name"]) ?></td>
                            <td><?= (int) $cycle["academic_year"] ?></td>
                            <td><?= htmlspecialchars($cycle["start_date"] ?? "—") ?></td>
                            <td><?= htmlspecialchars($cycle["end_date"] ?? "—") ?></td>
                            <td>
                                <span class="status">
                                    <?= htmlspecialchars($cycle["status"] ?? "Not set") ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="button edit"
                                       href="exam_cycles.php?edit=<?= (int) $cycle["cycle_id"] ?>">
                                        Edit
                                    </a>

                                    <form method="POST" action="exam_cycles.php"
                                          onsubmit="return confirm('Delete this examination cycle? If other records refer to it, deletion will be blocked.');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="cycle_id"
                                               value="<?= (int) $cycle["cycle_id"] ?>">
                                        <button class="delete" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            No examination cycles found. Add your first cycle above.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</main>

</body>
</html>
