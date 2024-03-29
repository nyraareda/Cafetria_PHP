<?php
class db
{
    private $host = "localhost";
    private $dbname = "php";
    private $user = "root";
    private $pass = "1234";
    private $connection = null;

    function __construct()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
    }

    function get_connection()
    {
        return $this->connection;
    }

    function get_user_data($table, $conditions) {
        $query = "SELECT * FROM $table WHERE $conditions";
        return $this->connection->query($query);
    }

    function get_data($tablename)
    {
        $result = $this->connection->query("SELECT * FROM $tablename");
        if (!$result) {
            die ("Error: " . $this->connection->error);
        }
        return $result;
    }
    function get_categories()
    {
        return $this->connection->query("SELECT * FROM categories");
    }
    function del_data($tablename, $id)
    {
        return $this->connection->query("DELETE FROM $tablename WHERE id=$id");
    }

    function update_data($tablename, $id, $product_name, $product_price, $category_id, $product_image = null, $is_active = null)
    {
        $sql = "UPDATE $tablename SET product_name = '$product_name', product_price = '$product_price'";
        if ($category_id !== null) {
            $sql .= ", category_id = '$category_id'";
        }
        if ($product_image !== null) {
            $sql .= ", product_image = '$product_image'";
        }
        if ($is_active !== null) {
            $sql .= ", is_active = '$is_active'";
        }

        $sql .= " WHERE id = $id";

        return $this->connection->query($sql);
    }

    function update_data_without_image($tablename, $id, $product_name, $product_price, $is_active = null)
    {
        $sql = "UPDATE $tablename SET product_name = '$product_name', product_price = '$product_price'";

        if ($is_active !== null) {
            $sql .= ", is_active = '$is_active'";
        }

        $sql .= " WHERE id = $id";

        return $this->connection->query($sql);
    }


    function insert_data($tableName, $columns, $values)
    {
        $columnsStr = implode(', ', $columns);
        $valuesStr = implode(', ', $values);
        return $this->connection->query("INSERT INTO $tableName ($columnsStr) VALUES ($valuesStr)");
    }
    function insert_data_user($tableName, $columns, $values)
    {
        $columnsStr = implode(', ', $columns);
        $valuesStr = implode(', ', $values);

        $query = "INSERT INTO $tableName ($columnsStr) VALUES ($valuesStr)";
        $result = $this->connection->query($query);

        if (!$result) {
            // Output the error message and query for debugging
            echo "Error: " . $this->connection->error . "<br>";
            echo "Query: " . $query;
            die(); // Stop execution in case of an error
        }

        return $result;
    }
    function get_last_insert_id($connection)
    {
        $query = "SELECT LAST_INSERT_ID()";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_row($result);
        return $row[0];
    }


    function update_user_data($id, $user_name = null, $user_email = null, $room_number = null, $ext_number = null, $user_image = null)
    {
        $sql = "UPDATE users SET ";
        $updates = [];

        if ($user_name !== null) {
            $user_name = $this->connection->real_escape_string($user_name);
            $updates[] = "user_name = '$user_name'";
        }

        if ($user_email !== null) {
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $user_email = $this->connection->real_escape_string($user_email);
            $updates[] = "user_email = '$user_email'";
        }

        if ($room_number === null || $room_number === '') {
            $updates[] = "room_number = NULL";
        } else {
            if (!is_numeric($room_number) || $room_number <= 0) {
                return false;
            }
            $updates[] = "room_number = $room_number";
        }

        if ($ext_number === null || $ext_number === '') {
            $updates[] = "ext_number = NULL";
        } else {
            if (!is_numeric($ext_number) || $ext_number <= 0) {
                return false;
            }
            $updates[] = "ext_number = $ext_number";
        }

        if ($user_image !== null) {
            $updates[] = "user_image = '$user_image'";
        }

        $sql .= implode(", ", $updates);
        $sql .= " WHERE id = $id";

        $result = $this->connection->query($sql);
        if ($result) {
            if ($room_number === null || $room_number === '') {
                echo '<li class="list-group-item containerlist-group-item no-room" style="color: red;">Room Number: Not in Room</li>';
            }
            if ($ext_number === null || $ext_number === '') {
                echo '<li class="list-group-item containerlist-group-item no-ext" style="color: red;">Ext Number: Not have Ext Number</li>';
            }
            return true;
        } else {
            return false;
        }
    }
    function user_del_data($tablename, $conditions)
{
    return $this->connection->query("DELETE FROM $tablename WHERE $conditions");
}

}
?>