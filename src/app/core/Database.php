<?php
trait Database {
    // private $host;
    // private $username;
    // private $password;
    // private $db_name;

    // public function __construct($host, $username, $password, $db_name) {
    //     $this->host = $host;
    //     $this->username = $username;
    //     $this->password = $password;
    //     $this->db_name = $db_name;
    // }

    private function connect() {
        require_once("../public/vendor/autoload.php");

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);
        if (!$conn) {
            die("Connect Failed: " . mysqli_connect_error());
        }
        return $conn;
    }

    public function query($query, $data = []) {
        $conn = $this->connect();
        $statement = $conn->prepare($query);

        if (!empty($data)) {
            $types = str_repeat('s', count($data)); // Assuming all parameters are strings
            $statement->bind_param($types, ...$data);
        }

        $check = $statement->execute();

        if ($check) {
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                $rows = [];
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                return [];
            }
        } else {
            return false;
        }
    }

    public function getRow($query, $data = []) {
        $conn = $this->connect();
        $statement = $conn->prepare($query);

        if (!empty($data)) {
            $types = str_repeat('s', count($data)); // Assuming all parameters are strings
            $statement->bind_param($types, ...$data);
        }

        $check = $statement->execute();

        if ($check) {
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                $rows = [];
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows[0];
            } else {
                return [];
            }
        } else {
            return false;
        }
    }
}

// $ban_time = 1; // หน่วยเป็นนาที
// $countLimit_login = 5;
// $update_unbaned = mysqli_query($conn, "UPDATE customer SET lock_account = 0, count_login = 0, ban_datetime = NULL WHERE ban_datetime <= NOW() AND count_login >= $countLimit_login");
