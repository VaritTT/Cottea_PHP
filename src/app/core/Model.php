<?php

trait Model {
    use Database;
    protected $limit = 1000000; // จำนวน row ที่ต้องการ
    protected $offset = 0; // เริ่มที่ row ไหน
    // protected $order_type = "DESC";
    // protected $order_column = "customer_id";
    public $errors = [];
    public function findAll($order_by = '1 ASC') {
        $query = "SELECT * FROM $this->table";
        $query .= " ORDER BY $order_by LIMIT $this->limit OFFSET $this->offset";
        return $this->query($query);
    }
    public function customSelect($select = "*", $where_arr, $order_by = '1 ASC') {
        $where_keys = array_keys($where_arr);
        $where_values = array_values($where_arr);
        $query = "SELECT $select FROM $this->table WHERE ";
        // ใส่หลัง WHERE
        foreach ($where_keys as $key) { // =
            $query .= "$key AND ";
            // $query .= $key . " = :" . $key . " AND "; // PDO
        }
        $query = trim($query, " AND ");
        $query .= " ORDER BY $order_by LIMIT $this->limit OFFSET $this->offset";
        return $this->query($query, $where_values);
    }
    public function countWhere($data, $data_not = []) {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT COUNT(*) FROM $this->table WHERE ";

        // ใส่หลัง WHERE
        foreach ($keys as $key) { // =
            $query .= "$key = ? AND ";
            // $query .= $key . " = :" . $key . " AND "; // PDO
        }

        foreach ($keys_not as $key) { // !=
            $query .= "$key = ? AND ";
            // $query .= $key . " != :" . $key . " AND "; // PDO
        }

        $query = trim($query, " AND ");
        $query .= " LIMIT $this->limit OFFSET $this->offset";

        // return $this->query($query, ['customer_id' => 6]);
        // $params_array = array_merge($data, $data_not); // คำสั่ง =, != รวมกัน
        $params_array = array_merge(array_values($data), array_values($data_not)); // คำสั่ง =, != รวมกัน
        $result = $this->query($query, $params_array)[0];
        $count = (int) $result->{"COUNT(*)"};
        return $count;
    }

    public function where($data, $data_not = [], $order_by = 'ASC') {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE ";

        // ใส่หลัง WHERE
        foreach ($keys as $key) { // =
            $query .= "$key = ? AND ";
            // $query .= $key . " = :" . $key . " AND "; // PDO
        }

        foreach ($keys_not as $key) { // !=
            $query .= "$key = ? AND ";
            // $query .= $key . " != :" . $key . " AND "; // PDO
        }

        $query = trim($query, " AND ");
        $query .= " ORDER BY 1 $order_by LIMIT $this->limit OFFSET $this->offset";
        // return $this->query($query, ['customer_id' => 6]);
        // $params_array = array_merge($data, $data_not); // คำสั่ง =, != รวมกัน
        $params_array = array_merge(array_values($data), array_values($data_not)); // คำสั่ง =, != รวมกัน
        return $this->query($query, $params_array);
    }

    public function first($data, $data_not = []) {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE ";

        // ใส่หลัง WHERE
        foreach ($keys as $key) { // =
            $query .= "$key = ? AND ";
        }

        foreach ($keys_not as $key) { // !=
            $query .= "$key = ? AND ";
        }

        $query = trim($query, " AND ");
        $query .= " LIMIT $this->limit OFFSET $this->offset";

        // return $this->query($query, ['customer_id' => 6]);
        // $params_array = array_merge($data, $data_not); // คำสั่ง =, != รวมกัน
        $params_array = array_merge(array_values($data), array_values($data_not)); // คำสั่ง =, != รวมกัน
        $result = $this->query($query, $params_array);
        if ($result) {
            return $result[0];
        }
        return false;
    }

    public function insert($data) {
        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(",", $keys)  . ") values (" . rtrim(str_repeat("?,", count($keys)), ",") . ") ";
        $values = array_values($data);
        $result = $this->query($query, $values);
        if ($result == []) {
            return true;
        }
        return false;
    }

    public function update($id, $data, $id_column = 'id') {
        // remove unwanted data
        // if (!empty($this->allowedColumns)) {
        //     foreach ($data as $key => $value) {
        //         if (!in_array($key, $this->allowedColumns)) {
        //             unset($data[$key]);
        //         }
        //     }
        // }

        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";

        // SET
        foreach ($keys as $key) { // =
            $query .= "$key = ?, ";
        }

        $query = trim($query, ", ");

        $query .= " WHERE $id_column = ?";

        $data[$id_column] = $id;
        $values = array_values($data);
        $result = $this->query($query, $values);
        if ($result == []) {
            return true;
        }
        return false;
    }

    public function delete($id, $id_column = 'id') {
        $data['id_column'] = $id;
        $values = array_values($data);

        $query = "DELETE FROM $this->table WHERE $id_column = ?";
        $result = $this->query($query, $values);
        if ($result == []) {
            return true;
        }
        return false;
    }
}
