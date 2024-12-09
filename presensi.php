<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi dan CRUD Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($role === 'guru'): ?>
            <h2>CRUD Siswa</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="student_name">Nama Siswa:</label>
                    <input type="text" id="student_name" name="student_name" required>
                </div>
                <button type="submit" name="action" value="add">Tambah Siswa</button>
            </form>

            <h3>Daftar Siswa</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM students");
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="student_id" value="<?php echo $row['id_student']; ?>">
                                    <input type="text" name="student_name" value="<?php echo $row['name']; ?>">
                                    <button type="submit" name="action" value="update">Update</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="student_id" value="<?php echo $row['id_student']; ?>">
                                    <button type="submit" name="action" value="delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($role === 'siswa'): ?>
            <h2>Presensi</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="status">Status Kehadiran:</label>
                    <select id="status" name="status">
                        <option value="Hadir">Hadir</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                </div>
                <button type="submit">Kirim Presensi</button>
            </form>
        <?php endif; ?>

        <!-- Tombol Kembali ke Home -->
        <form method="get" action="index.php">
            <button type="submit">Kembali ke Home</button>
        </form>
    </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($role === 'guru' && isset($_POST['action'])) {
        $action = $_POST['action'];
        $student_name = $_POST['student_name'] ?? '';

        if ($action === 'add') {
            $sql = "INSERT INTO students (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $student_name);
            $stmt->execute();
        } elseif ($action === 'delete' && isset($_POST['student_id'])) {
            $student_id = $_POST['student_id'];
            $sql = "DELETE FROM students WHERE id_student = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $student_id);
            $stmt->execute();
        } elseif ($action === 'update' && isset($_POST['student_id'])) {
            $student_id = $_POST['student_id'];
            $sql = "UPDATE students SET name = ? WHERE id_student = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $student_name, $student_id);
            $stmt->execute();
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif ($role === 'siswa') {
        $student_id = $_SESSION['user_id'];
        $date = date('Y-m-d');
        $status = $_POST['status'] ?? '';

        $sql = "INSERT INTO presensi (student_id, date, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $student_id, $date, $status);
        $stmt->execute();

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
