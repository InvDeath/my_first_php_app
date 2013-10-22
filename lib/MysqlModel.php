<?php

/**
 * MysqlModel 
 * 
 * Conteins privatemethods connectins and query to batabase.
 * All methods with queries only in this class.
 *
 */
class MysqlModel
{
    /**
     * dbConnect 
     * 
     * @var mixed
     */
    private $dbConnect;

    /**
     * __construct 
     *
     * Opening connection with mysql when instance have creating.
     *
     * @return void
     */
    function __construct()
    {
        $this->dbConnect = $this->dbConnection();
    }

    /**
     * dbQuery 
     * 
     * Sends any queries to database.
     *
     * @param mixed $query 
     * @return void
     */
    private function dbQuery($query)
    {
        $res = mysql_query($query);
        
        if (!$res)
        {
            throw new Exception('Invalid query: ' . mysql_error());
        }
        
        return $res;

    }

    /**
     * dbConnection 
     * 
     * Opens connection with database.
     *
     * @return void
     */
    private function dbConnection()
    {
        $link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
                 
        if( !$link )
        {
            throw new Exception('Could not connect: ' . mysql_error());
        }
        
        $db_selected = mysql_select_db(DB_BASE, $link);
        
        if (!$db_selected)
        {
            throw new Exception ('Can\'t use '.DB_BASE.':' . mysql_error());
        }

        return $link;
    }

/**
 * Methods 
 */

    protected function pullUserByName($name)
    {
        $query = "SELECT `id`, `password` FROM `users` WHERE `username` = '$name' LIMIT 1";
        $res = $this->dbQuery($query);
        
        return  mysql_fetch_assoc($res);
    }

    protected function pullEmployees($count = 100)
    {
        $query = "SELECT id, name, email FROM employees LIMIT $count";
        $res = $this->dbQuery($query);
        $employees = array();
        while($row = mysql_fetch_assoc($res))
        {
            $employees[$row['id']]['name'] = $row['name'];
            $employees[$row['id']]['email'] = $row['email'];
        }

        return $employees;
    }

    protected function pullEmpByEmail($email, $id = FALSE)
    {
        $query = "SELECT `id` FROM `employees` WHERE `email` = '$email' AND `id` <> '$id' LIMIT 1";
        $res = $this->dbQuery($query);

        return mysql_fetch_assoc($res);
    }

    protected function pullEmpById($id)
    {
        $query = "SELECT id, name, email FROM employees WHERE id = $id";
        $res = $this->dbQuery($query);

        return mysql_fetch_assoc($res);
    }
    
    protected function pushEmployee($name, $email)
    {
        $query = "INSERT INTO `employees` (`id`, `name`, `email`) VALUES (NULL, '$name', '$email')";
        return $this->dbQuery($query);
    }

    protected function throwEmployee($id)
    {
        $query = "DELETE FROM `employees` WHERE `employees`.`id` = $id";
        return $this->dbQuery($query);
    }

    protected function turnEmployee($id, $name, $email)
    {
        $query = "UPDATE `employees` SET  `name` =  '$name', `email` =  '$email' WHERE  `employees`.`id` = $id";
        return $this->dbQuery($query);
    }

    protected function dbAddEvent($employee, $start, $finish, $room, $description, $reqId = 0)
    {
        $added = date('Y-m-d H:i:s');
        $query = "INSERT INTO `events` (`id` ,`employee` ,`start` ,`finish` ,`room` ,`desc` ,`req_id`, `added`) VALUES ( NULL ,  '$employee',  '$start',  '$finish',  '$room',  '$description',  '$reqId', '$added')";
        return $this->dbQuery($query);
    }

    protected function dbAddReccuring()
    {
        $query ="INSERT INTO `recursions` (`id`) VALUES (NULL)";
        $res = $this->dbQuery($query);

        return mysql_insert_id();
    }

    protected function dbSelectEventsForMonth($year, $month, $room)
    {
        $query = "SELECT id, start, finish FROM events WHERE YEAR(start) = $year AND MONTH(start) = $month AND room = $room ORDER BY start";
        $res = $this->dbQuery($query);

        $events = array();
        while($row = mysql_fetch_assoc($res))
        {
            $events[$row['id']] = $row;
        }

        return $events;
    }

    protected function dbAskIntersections($start, $finish, $room, $id = FALSE)
    {
        $query = "SELECT `id` FROM `events` WHERE (`start` < '$finish' AND `finish` > '$start') AND room = $room AND `id` <> '$id'"; 
        $res = $this->dbQuery($query);

        return mysql_fetch_assoc($res);
    }

    protected function dbSelectEventById($id)
    {
        $query = "SELECT `id`, `employee`, `start`, `finish`, `room`, `desc`, `added`, `req_id` FROM `events` WHERE `id` = '$id' LIMIT 1";
        $res = $this->dbQuery($query);
        return mysql_fetch_assoc($res);
    }

    protected function dbRemoveEventsReq($reqId)
    {
        if($reqId == 0)
        {
            return FALSE;
        }
        
        $query = "DELETE FROM `events` WHERE `req_id` = $reqId";
        return $this->dbQuery($query);
    }

    protected function dbRemoveRecursions($reqId)
    {
        $query = "DELETE FROM `recursions` WHERE `id` = '$reqId'";
        return $this->dbQuery($query);
    }

    protected function dbRemoveEventById($id)
    {
        $query = "DELETE FROM `events` WHERE `id` = '$id'";
        return $this->dbQuery($query);
    }

    protected function dbUpdateEvent($id, $start, $finish, $empl, $desc)
    {
        $query = "UPDATE `events` SET `start` = '$start', `finish` = '$finish', `employee` = '$empl', `desc` = '$desc' WHERE `id` = '$id'";
        return $this->dbQuery($query);
    }
    
    protected function dbSelectEventByReqId($id)
    {
        $query = "SELECT `id`, `employee`, `start`, `finish`, `desc` FROM `events` WHERE `req_id` = '$id'";
        $res = $this->dbQuery($query);

        while($row = mysql_fetch_assoc($res))
        {
            $ret[] = $row;
        }

        return $ret;
    }



}

?>
