<?php

namespace Models;

class TodoModel
{
    const STATUS_PLANNING = 'planning';
    const STATUS_DOING = 'doing';
    const STATUS_COMPLETE = 'complete';

    public static $dataStatus = [
        self::STATUS_PLANNING => 'Planning',
        self::STATUS_DOING => 'Doing',
        self::STATUS_COMPLETE => 'Complete',
    ];

    public function getList()
    {
        $conn = $this->connectDB();
        $sql = "SELECT * FROM todo";
        $result = $conn->query($sql);
        $dataTodo = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $todo = new TodoEntity($row['id'], $row['task_name'], $row['start_date'], $row['end_date'], $row['status'], $row['created_at'], $row['updated_at']);
                array_push($dataTodo, $todo);
            }
        }
        $conn->close();
        return $dataTodo;
    }

    public function view($date){
        $conn = $this->connectDB();
        $sql = "SELECT * FROM todo WHERE start_date <= '".$date."' AND end_date >= '".$date."'";
        $result = $conn->query($sql);
        $dataTodo = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $todo = new \Models\TodoEntity($row['id'], $row['task_name'], $row['start_date'], $row['end_date'], $row['status'], $row['created_at'], $row['updated_at']);
                array_push($dataTodo, $todo);
            }
        }
        $conn->close();
        return $dataTodo;
    }

    public function add($todo)
    {
        $conn = $this->connectDB();
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO todo (task_name, start_date, end_date, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $todo->taskName, $todo->startDate, $todo->endDate, $todo->status, $created_at, $updated_at);
        $check = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $check;
    }

    public function edit($todo)
    {
        $conn = $this->connectDB();
        $sql = "UPDATE todo SET task_name = '".$todo->taskName."', start_date = '".$todo->startDate."', end_date = '".$todo->endDate."', status = '".$todo->status."', created_at = '".$todo->createdAt."' WHERE id = ".$todo->id;
        $check = $conn->query($sql);
        $conn->close();
        return $check;
    }

    public function delete($id){
        $conn = $this->connectDB();
        $sql = "DELETE FROM todo WHERE id = ".$id;
        $check = $conn->query($sql);
        $conn->close();
        return $check;
    }

    private function connectDB(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "todolist";

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$conn) {
            echo ("Connection failed!");
        }
        return $conn;
    }

}