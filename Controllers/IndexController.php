<?php

namespace Controllers;

use Models\TodoModel;

require_once("Models/TodoModel.php");
require_once("Models/TodoEntity.php");

class IndexController
{
    public $todoModel;
    public function __construct()
    {
        $this->todoModel = new TodoModel();
    }

    public function index()
    {
        return $this->todoModel->getList();
    }

}