<?php

// Include your database configuration file if it's not already included
// include 'db_config.php';

// Replace these variables with your actual database connection details

include 'Connect.php';

// Add triggers for HistoryTable
$sqlTriggers = "
    DELIMITER //
    CREATE TRIGGER product_insert_trigger
    AFTER INSERT ON product
    FOR EACH ROW
    BEGIN
        INSERT INTO HistoryTable (content, action)
        VALUES (CONCAT('Product "', NEW.product_name, '" inserted.'), 'Insert');
    END;
    //
    DELIMITER ;

    DELIMITER //
    CREATE TRIGGER product_update_trigger
    AFTER UPDATE ON product
    FOR EACH ROW
    BEGIN
        INSERT INTO HistoryTable (content, action)
        VALUES (CONCAT('Product "', NEW.product_name, '" updated.'), 'Update');
    END;
    //
    DELIMITER ;

    DELIMITER //
    CREATE TRIGGER product_delete_trigger
    AFTER DELETE ON product
    FOR EACH ROW
    BEGIN
        INSERT INTO HistoryTable (content, action)
        VALUES (CONCAT('Product "', OLD.product_name, '" deleted.'), 'Delete');
    END;
    //
    DELIMITER ;
";

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

    // Execute the trigger creation SQL
    $pdo->exec($sqlTriggers);

    // Close the database connection
    $pdo = null;

    echo "Triggers created successfully!";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
