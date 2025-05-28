<?php

// Database connection parameters
$host = 'localhost'; // Your PostgreSQL host
$dbname = 'customer_database'; // Database name
$user = 'postgres'; // Your PostgreSQL username
$password = 'your_postgres_password'; // <--- IMPORTANT: Change this to your actual PostgreSQL password

try {
    // DSN for PostgreSQL
    $dsn = "pgsql:host=$host;dbname=$dbname";

    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Prepare and execute the SQL query to fetch all clients
    $stmt = $pdo->query("SELECT id, full_name, cpf, email, birth_date FROM customers ORDER BY full_name");

    // Fetch all results
    $customers = $stmt->fetchAll(); // Changed variable name to $customers

} catch (PDOException $e) {
    die("Connection or query error: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Customers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Registered Customers</h1>

    <?php if (count($customers) > 0): // Using $customers variable ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): // Using $customer singular variable ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['id']); ?></td>
                        <td><?php echo htmlspecialchars($customer['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars(date('m/d/Y', strtotime($customer['birth_date']))); // Changed date format for English context ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No customers registered yet.</p>
    <?php endif; ?>

    <p><a href="index.html">Back to Registration Form</a></p>

</body>
</html>
<?php