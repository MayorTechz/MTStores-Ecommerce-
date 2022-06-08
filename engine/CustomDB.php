<?php
/*This is the custom queries for fetching from db. You can edit or add yours*/

class CustomDB
{
    private $host;
    private $username;
    private $password;
    private $db_name;
    public $con;

    public function __construct()
    {
        $this->host = "localhost";
        $this->username = "your db username";
        $this->password = "your db password";
        $this->db_name = "your db name";
        $con = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->db_name
        );
        $this->con = $con;
        if ($con->connect_error) {
            die("Something went wrong");
        }
        return $this->con;
    }

    //This is used for selection with any condition
    function select($table, $cons, $params)
    {
        //to select all rows with conditions

        $this->table = $table;
        $this->cons = $cons;
        $this->params = $params;

        $sql = "SELECT * FROM " . $this->table . " " . $this->cons;

        $stmt = $this->con->prepare($sql);

        $stmt->bind_param($params[0], ...$params[1]);

        $stmt->execute();

        $res = $stmt->get_result();

        return $res;
    }

    //this is a custom select. Here you can add your custom query directly from function call
    function select_custom($sql, $params = "")
    {
        $this->params = $params;

        $this->sql = $sql;

        $stmt = $this->con->prepare($this->sql);

        if (isset($params)) {
            $stmt->bind_param($params[0], ...$params[1]);
        }

        $stmt->execute();

        $res = $stmt->get_result();

        $row = $res->fetch_assoc();
        $count = $res->fetch_row();

        return [
            "result" => $res,
            "execute" => $stmt,
            "row" => $row,
            "count" => $count,
        ];
    }

    //update rows query
    function update($table, $cons, $params)
    {
        //to select all rows with conditions

        $this->table = $table;
        $this->cons = $cons;
        $this->params = $params;

        $sql = "UPDATE " . $this->table . " " . $this->cons;

        $stmt = $this->con->prepare($sql);

        $stmt->bind_param($params[0], ...$params[1]);

        $stmt->execute();

        return $stmt;
    }

    //delete query
    function remove($table, $cons, $params)
    {
        //to select all rows with conditions

        $this->table = $table;
        $this->cons = $cons;
        $this->params = $params;

        $sql = "DELETE FROM " . $this->table . " " . $this->cons;

        $stmt = $this->con->prepare($sql);

        $stmt->bind_param($params[0], ...$params[1]);

        $stmt->execute();

        return $stmt;
    }

    //insert query

    function insert($table, $cols, $params)
    {
        $this->table = $table;
        $this->cols = $cols;
        $this->params = $params;

        $sql =
            "INSERT INTO " .
            $this->table .
            "(" .
            $this->cols .
            ") VALUES(" .
            $this->params[1] .
            ")";

        $stmt = $this->con->prepare($sql);

        $binding_var = $params[0]; //e.g (ss,issi)

        $stmt = $this->con->prepare($sql);

        $stmt->bind_param($binding_var, ...$params[2]);

        $stmt->execute();

        return $stmt;
    }

    function page_offset($action, $limit, $current_page)
    {
        $this->action = $action;

        $this->limit = $limit;

        $this->current_page = $current_page;

        $offset;

        if ($this->action == "next") {
            $offset = $this->limit * $this->current_page;
        } else {
            $offset = ($this->current_page - 2) * $this->limit;
        }

        return $offset;
    }
} //class end
?>