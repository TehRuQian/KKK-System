<?php

function dnd($data){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die;
}

function sanitize($dirty){
    $clean= htmlentities($dirty, ENT_QUOTES, "UTF-8");
    return trim($clean);
}

function cleanPost($post){
    $clean = [];
    foreach ($post as $key => $value){
        $clean[$key]= sanitize($value);
    }
    return $clean;
}

function getValue($key) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    } elseif (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    }
    return ''; 
}

function isChecked($key, $value) {
    if (isset($_POST[$key]) && $_POST[$key] == $value){
        return 'checked';
    }
    elseif (isset($_SESSION[$key]) && $_SESSION[$key] == $value) {
        return 'checked';
    }
    return '';
}

function isSelected($key, $value) {
    if (isset($_POST[$key]) && $_POST[$key] == $value){
        return 'selected';
    }
    elseif (isset($_SESSION[$key]) && $_SESSION[$key] == $value) {
        return 'selected';
    }
    return '';
}

function query($sql, $binds = [], $executeOnly = false) {
    // Database connection
    $db = mysqli_connect("127.0.0.1", "root", "", "db_kkk");

    if (!$db) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prepare the statement
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL preparation failed: " . mysqli_error($db));
    }

    // Bind parameters if provided
    if (!empty($binds)) {
        $typeCast = '';
        foreach ($binds as $bind) {
            // Determine type: 's' for string, 'i' for integer, 'd' for double, 'b' for blob
            $typeCast .= is_int($bind) ? 'i' : (is_double($bind) ? 'd' : 's');
        }
        mysqli_stmt_bind_param($stmt, $typeCast, ...$binds);
    }

    // Execute the statement
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die("Execution failed: " . mysqli_stmt_error($stmt));
    }

    // Return the result
    if ($executeOnly) {
        $output = $result;
    } else {
        $output = mysqli_stmt_get_result($stmt);
    }

    // Clean up
    mysqli_stmt_close($stmt);
    mysqli_close($db);

    return $output;
}



?>

