<?php

namespace Models;

class TodoEntity
{
    public $id;
    public $taskName;
    public $startDate;
    public $endDate;
    public $status;
    public $createdAt;
    public $updatedAt;

    public function __construct($id, $taskName, $startDate, $endDate, $status, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->taskName = $taskName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

}