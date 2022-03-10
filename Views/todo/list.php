<?php
require_once("../../Controllers/TodoController.php");
$todoController = new Controllers\TodoController();
$todos = null;
$date = !empty($_REQUEST['date']) ? date_create($_REQUEST['date']) : null;
if(!empty($date))
    $date = date_format($date, 'Y-m-d');

// load default template todo task list
if($_SERVER['REQUEST_METHOD'] == "GET" && !empty($date)){
   // var_dump($_REQUEST['date']);
    $todos = $todoController->index($date);
}
else {
    $todos = $todoController->index(date('Y-m-d'));
}
// Add new task
if($_SERVER['REQUEST_METHOD'] == "POST" && $_REQUEST['type'] == "ADD"){
    $todoController->add();
    $todos = $todoController->index($todoController->date);
}
// Edit current task
if($_SERVER['REQUEST_METHOD'] == "POST" && $_REQUEST['type'] == "EDIT"){
    $todoController->edit();
    $todos = $todoController->index($todoController->date);
}
// Delete task by id
if($_SERVER['REQUEST_METHOD'] == "GET" && !empty($_REQUEST['delete'])){
    $todoController->delete();
    $todos = $todoController->index($date);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo-List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php if($todoController->date){ ?>
    <div class="container">
        <div class="col-md-12">
            <?php if($todoController->msOK){ ?>
                <div class="alert alert-success" role="alert">
                    <?= $todoController->msOK ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if($todoController->msERR){ ?>
                <div class="alert alert-danger" role="alert">
                    <?= $todoController->msERR ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-12 text-center">
            <h6><?= $todoController->date ?></h6>
        </div>
        <div class="col-md-12">
            <!--begin:: Widgets/User Progress -->
            <div class="m-portlet m-portlet--full-height ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                <a href="../../index.php" class="btn"><i class="fa fa-home"></i></a>
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                                    Add
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <table class="table">
                            <tbody>
                                <?php foreach ($todos as $todo) { ?>
                                    <?php
                                        $colorStatus = "";
                                        switch ($todo->status) {
                                            case \Models\TodoModel::STATUS_PLANNING:
                                                $colorStatus = "#17a2b8";
                                                break;
                                            case \Models\TodoModel::STATUS_DOING:
                                                $colorStatus = "#ffc107";
                                                break;
                                            case \Models\TodoModel::STATUS_COMPLETE:
                                                $colorStatus = "#28a745";
                                                break;
                                        }
                                        /*$createdAt = date_create($todo->createdAt);
                                        $createdAt = date_format($createdAt, 'Y-m-d');*/
                                    ?>
                                    <tr class="m-widget4__item">
                                        <td class="m-widget4__progress">
                                            <?= $todo->taskName; ?>
                                        </td>
                                        <td>
                                            <span class="label text-capitalize" style="background-color: <?= $colorStatus ?>;">
                                                <?= $todo->status ?>
                                            </span>
                                        </td>
                                        <td class="m-widget4__info">
                                             <span class="m-widget4__sub">
                                                Start : <?= $todo->startDate ?>
                                            </span>
                                            <br>
                                            <span class="m-widget4__sub">
                                                End : <?= $todo->endDate ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn-edit m-btn--hover-brand m-btn--pill btn btn-sm btn-primary" data-id="<?= $todo->id ?>">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="list.php?delete=<?= $todo->id ?>&date=<?= $date ?>" class="m-btn m-btn--hover-brand m-btn--pill btn btn-sm btn-danger">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/User Progress -->
        </div>
    </div>

    <!-- Modal Add-->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="POST" action="list.php">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="display: none;">
                        <input name="type" value="ADD">
                        <input name="createDate" value="<?= $todoController->date ?>">
                        <input name="date" value="<?= $todoController->date ?>">
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Task info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-2 col-form-label">Task name</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="" id="example-text-input" name="taskName" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-date-input" class="col-2 col-form-label">Start</label>
                            <div class="col-10">
                                <input class="form-control date-start-add" type="date" value="" id="example-date-input" name="startDate" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-date-input" class="col-2 col-form-label">End</label>
                            <div class="col-10">
                                <input class="form-control date-end-add" type="date" value="" id="example-date-input" name="endDate" required>
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="gridRadios1" value="<?= \Models\TodoModel::STATUS_PLANNING ?>" checked>
                                        <label class="form-check-label" for="gridRadios1">
                                            Planning
                                        </label>
                                        <button type="button" class="btn btn-info btn-sm"></button>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="gridRadios2" value="<?= \Models\TodoModel::STATUS_DOING ?>">
                                        <label class="form-check-label" for="gridRadios2">
                                            Doing
                                        </label>
                                        <button type="button" class="btn btn-warning btn-sm"></button>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="gridRadios3" value="<?= \Models\TodoModel::STATUS_COMPLETE ?>">
                                        <label class="form-check-label" for="gridRadios3">
                                            Complete
                                        </label>
                                        <button type="button" class="btn btn-success btn-sm"></button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-submit-form">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Edit-->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="POST" action="list.php" id="form-edit-todo">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="display: none;">
                        <input name="type" value="EDIT">
                        <input name="id" value="" id="id-edit">
                        <input name="createDate" value="<?= $todoController->date ?>">
                        <input name="date" value="<?= $todoController->date ?>">
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Task info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-2 col-form-label">Task name</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="" id="task-name-edit" name="taskName">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-date-input" class="col-2 col-form-label">Start</label>
                            <div class="col-10">
                                <input class="form-control date-start-edit" type="date" value="2011-08-19" id="start-date-edit" name="startDate">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-date-input" class="col-2 col-form-label">End</label>
                            <div class="col-10">
                                <input class="form-control date-end-edit" type="date" value="2011-08-19" id="end-date-edit" name="endDate">
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status-planning-edit" value="<?= \Models\TodoModel::STATUS_PLANNING ?>" checked>
                                        <label class="form-check-label" for="status-planning-edit">
                                            Planning
                                        </label>
                                        <button type="button" class="btn btn-info btn-sm"></button>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status-doing-edit" value="<?= \Models\TodoModel::STATUS_DOING ?>">
                                        <label class="form-check-label" for="status-doing-edit">
                                            Doing
                                        </label>
                                        <button type="button" class="btn btn-warning btn-sm"></button>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status-complete-edit" value="<?= \Models\TodoModel::STATUS_COMPLETE ?>">
                                        <label class="form-check-label" for="status-complete-edit">
                                            Complete
                                        </label>
                                        <button type="button" class="btn btn-success btn-sm"></button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-submit-form">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>

        $(".date-start-add, .date-end-add").change(function(){
            let start = $(".date-start-add").val();
            let end = $(".date-end-add").val();
            compareDate(start, end);
        });

        $(".date-start-edit, .date-end-edit").change(function(){
            let start = $(".date-start-edit").val();
            let end = $(".date-end-edit").val();
            compareDate(start, end);
        });

        function compareDate(start, end){
            if(new Date(start) > new Date(end))
            {
                alert("Start date is greater than the end date");
                $(".btn-submit-form").prop('disabled', true);
            }
            else{
                $(".btn-submit-form").prop('disabled', false);
            }
        }


        $(".m-widget4__progress").click(function(){
            editEvent($(this));
        });

        $(".btn-edit").click(function () {
            editEvent($(this));
        });

        function editEvent($el)
        {
            $('#form-edit-todo').trigger("reset");
            //$(".btn-submit-form").prop('disabled', true);

            let id = $el.attr("data-id");
            let todos = <?php echo json_encode($todos); ?>;
            let todoInfo = null;
            for (let [index, todo] of Object.entries(todos)) {
                for (let [key, value] of Object.entries(todo)) {
                    if(todo['id'] == id){
                        todoInfo = todo;
                        break;
                    }
                }
                if(todoInfo !== null){
                    break;
                }
            }
            // Set value for fomr edit from todoInfo
            let type = "-edit";
            $('#id'+type).val(todoInfo['id']);
            $('#task-name'+type).val(todoInfo['taskName']);
            $('#start-date'+type).val(todoInfo['startDate']);
            $('#end-date'+type).val(todoInfo['endDate']);
            switch(todoInfo['status']) {
                case "planning":
                    $("#status-planning-edit").prop("checked", true);
                    break;
                case "doing":
                    $("#status-doing-edit").prop("checked", true);
                    break;
                case "complete":
                    $("#status-complete-edit").prop("checked", true);
                    break;
            }

            $('#editModal').modal('show');
        }

    </script>
<?php }?>
</body>
</html>