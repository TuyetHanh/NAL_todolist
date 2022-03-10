<?php

namespace Controllers;

use Models\TodoEntity;
use Models\TodoModel;

require_once $_SERVER['DOCUMENT_ROOT']."/Models/TodoModel.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Models/TodoEntity.php";

class TodoController
{
    public $model;
    public $date;
    public $msOK;
    public $msERR;

    public function __construct()
    {
        $this->model = new TodoModel();
        $this->date = "";
        $this->msOK = "";
        $this->msERR = "";
    }

    public function index($date)
    {
        $this->date = $date;
        return $this->model->view($this->date);
    }

    public function view()
    {
        echo "ok";
    }

    public function add()
    {
        $this->date = $_REQUEST['startDate'];

        $todo = new TodoEntity(null, $_REQUEST['taskName'], $_REQUEST['startDate'], $_REQUEST['endDate'], $_REQUEST['status'], $_REQUEST['createDate'], $_REQUEST['createDate']);

        if($this->model->add($todo)){
            $this->msOK = "Added data successfully!";
        }
        else{
            $this->msERR = "Failed to add data";
        }

        header("Location: " . "http://" . $_SERVER['SERVER_NAME'] . "/views/todo/list.php?date=" . $this->date);
    }

    public function edit()
    {
        $this->date = $_REQUEST['date'];

        $todo = new TodoEntity($_REQUEST['id'], $_REQUEST['taskName'], $_REQUEST['startDate'], $_REQUEST['endDate'], $_REQUEST['status'], $_REQUEST['createDate'], date('Y-m-d'));

        if($this->model->edit($todo)){
            $this->msOK = "Edited data successfully!";
        }
        else{
            $this->msERR = "Failed to edit data";
        }

        //header("Location: " . "http://" . $_SERVER['SERVER_NAME'] . "/views/todo/list.php?date=" . $this->date);
    }

    public function delete()
    {
        //if($this->model->delete($_REQUEST['delete'])){
        if(true){
            $this->msOK = "Deleted data successfully!";
        }
        else{
            $this->msERR = "Failed to delete data";
        }

        //header("Location: " . "http://" . $_SERVER['SERVER_NAME'] . "/views/todo/list.php?date=" . $this->date);
    }
}