<?php

// Database connection parameters
$host = 'localhost'; // Your PostgreSQL host
$dbname = 'customer_database'; // Database name
$user = 'postgres'; // Your PostgreSQL username
$password = 'your_postgres_password'; // <--- IMPORTANT: Change this to your actual PostgreSQL password

try {
    // DSN (Data Source Name) for PostgreSQL
    $dsn = "pgsql:host=$host;dbname=$dbname";

    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
    ]);

    // Check if the form was submitted via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and validate input
        // Note: The input names still match the HTML form's 'name' attributes (e.g., 'nome_completo')
        $full_name = filter_input(INPUT_POST, 'nome_completo', FILTER_SANITIZE_STRING);
        $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $birth_date = filter_input(INPUT_POST, 'data_nascimento', FILTER_SANITIZE_STRING); // Date format 'YYYY-MM-DD'

        // Basic validation (you can add more robust validation)
        if (empty($full_name) || empty($cpf) || empty($email) || empty($birth_date)) {
            die("Error: All fields are mandatory.");
        }

        // Validate CPF length (ensure it's 11 digits after sanitization)
        if (strlen($cpf) !== 11) {
            die("Error: CPF must contain 11 digits.");
        }

        // Prepare the SQL statement using placeholders to prevent SQL injection
        // Column names match the 'customers' table in the database
        $stmt = $pdo->prepare("INSERT INTO customers (full_name, cpf, email, birth_date) VALUES (:full_name, :cpf, :email, :birth_date)");

        // Bind parameters to the prepared statement
        // The placeholders correspond to the actual database columns
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birth_date', $birth_date);

        // Execute the statement
        $stmt->execute();

        echo "<h2>Customer registered successfully!</h2>";
        echo "<p><a href='index.html'>Back to form</a></p>";
        echo "<p><a href='client_list.php'>View Registered Customers</a></p>";

    } else {
        echo "Invalid request method.";
    }

} catch (PDOException $e) {
    die("Connection or query error: " . $e->getMessage());
}

?>