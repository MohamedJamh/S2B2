<?php
    //INCLUDE DATABASE FILE
    include('database.php');
    //SESSSION IS A WAY TO STORE DATA TO BE USED ACROSS MULTIPLE PAGES
    session_start();
    global $count_todo , $count_inprogress , $count_done;
    $count_todo = 0;
    $count_inprogress = 0;
    $count_done = 0;

    //ROUTING
    if(isset($_POST['save']))        saveTask();
    if(isset($_POST['update']))      updateTask();
    if(isset($_POST['delete']))      deleteTask();
    
    function countTasks($id_status){
        global $cnx;
        $req_sql = "SELECT count(*) FROM `tasks` WHERE id_status = $id_status";
            
        $response_sql = mysqli_query( $cnx , $req_sql);
        $row =  mysqli_fetch_array($response_sql);
        echo $row[0];
    }

    
    function getTasks($nom_status)
    {
        //CODE HERE
        global $cnx;
        //SQL SELECT
        $req_sql = "SELECT `id_task`, `title`, ty.name_type, ty.id_type, s.name_status, s.id_status, p.name_priority, p.id_priority, `task_datetime`, `description`
            FROM tasks AS t , priorities as p , types as ty , statuses as s
            WHERE t.id_type = ty.id_type
            and t.id_status = s.id_status
            and t.id_priority = p.id_priority";
            
        $response_sql = mysqli_query( $cnx , $req_sql);

        if(mysqli_num_rows($response_sql) > 0){
            while($task = mysqli_fetch_assoc($response_sql)){

                $id = $task['id_task'];
                $title = $task['title'];
                $id_type = $task['id_type'];
                $nom_type = $task['name_type'];
                $id_priority = $task['id_priority'];
                $nom_priority = $task['name_priority'];
                $task_datetime = date('Y-m-d',strtotime($task['task_datetime']));
                $id_status = $task['id_status'];
                $task_status = $task['name_status'];
                $description = $task['description'];
                $sub_description = substr($description,0,150);

                $icon_class = "";

                if($task['name_status'] == 'To Do'){
                    $icon_class = 'bi bi-question-circle-fill text-green';
                    $GLOBALS['count_todo']++;
                }else if($task['name_status'] == 'In Progress'){
                    $icon_class = 'spinner-border text-green';
                }else if($task['name_status'] == 'Done'){
                    $icon_class = 'bi bi-check-circle-fill text-green';
                }

                if($task['name_status'] == $nom_status){
                    echo "
                        <button id='$id' class='task d-flex p-3 w-100 border-0 border-bottom' data-bs-toggle='modal' data-bs-target='#modal-task' onclick='printDataOnModal(this.id)' >
                            <span class='me-2'>
                                <i class='$icon_class' style='font-size: 17px; width:20px; height:20px;' ></i>
                            </span>
                            <div class='task-body text-start pl-3'>
                                <h4 class='task-title' > $title </h4>
                                <div class='task-details'>
                                    <div class='task-date'>#$id created in $task_datetime</div>
                                    <div class='task-description pb-2 pt-2' title='$description'>$sub_description </div>
                                    <div class = 'task-status' style='display:none' data-id-status='$id_status' >$task_status</div>
                                </div>
                                <div class='task-features'>
                                    <span class='btn btn-danger task-priority p-2 rounded-3' data-id-priority='$id_priority'>$nom_priority</span>
                                    <span class='btn btn-info task-type p-2 rounded-3' data-id-type='$id_type'>$nom_type</span>
                                </div>
                            </div>
                            <input type='text' value='$title' style='display:none;'  />
                        </button>
                    ";
                    
                }
            }
        }
    }
    
    function inputValidation(){

        if( empty($_POST['t-title']) || empty($_POST['t-date'])  || empty($_POST['t-description'])  ||  empty($_POST['t-status'])  || empty($_POST['t-priority'])){
            $_SESSION['error_message'] = " Please Fill All The Fields !";
            header('location: index.php');
        }

    }
    function saveTask()
    {
        inputValidation();
        global $cnx;
        //CODE HERE
        $title = htmlspecialchars($_POST['t-title']) ;
        $date = htmlspecialchars($_POST['t-date']) ;
        $description = htmlspecialchars($_POST['t-description']) ;
        $type = htmlspecialchars($_POST['t-type']) ; 
        $status = htmlspecialchars($_POST['t-status']) ;
        $priority = htmlspecialchars($_POST['t-priority']) ;

        
        // SQL INSERT
        $req_sql = "INSERT INTO `tasks`(`title`, `id_type`, `id_status`, `id_priority`, `task_datetime`, `description`) 
                    VALUES ('$title',$type,$status,$priority,'$date','$description')";
        $response_sql = mysqli_query( $cnx , $req_sql);
        $_SESSION['message'] = "Task has been added successfully !";
        header('location: index.php');
    }

    function updateTask()
    {
        inputValidation();
        global $cnx;
        //CODE HERE
        $id = $_POST['t-id'];
        $title = htmlspecialchars($_POST['t-title']) ;
        $date = htmlspecialchars($_POST['t-date']) ;
        $description = htmlspecialchars($_POST['t-description']) ;
        $type = htmlspecialchars($_POST['t-type']) ; 
        $status = htmlspecialchars($_POST['t-status']) ;
        $priority = htmlspecialchars($_POST['t-priority']) ;

        
        // SQL INSERT
        $req_sql = "UPDATE `tasks` SET `title`='$title',`id_type`='$type',`id_status`='$status',
                    `id_priority`='$priority',`task_datetime`='$date',`description`='$description'
                    WHERE `id_task`= $id";
        $response_sql = mysqli_query( $cnx , $req_sql);
        $_SESSION['message'] = "Task has been updated successfully !";
		header('location: index.php');
    }

    function deleteTask()
    {
        global $cnx;
        //CODE HERE
        $id = $_POST['t-id'];
        //SQL DELETE
        $req_sql = "DELETE FROM `tasks` WHERE id_task = $id" ;
        $response_sql = mysqli_query( $cnx , $req_sql);
        $_SESSION['message'] = "Task has been deleted successfully !";
		header('location: index.php');
    }

    function generateInputs($name_table){
        //CODE HERE
        global $cnx;
        //SQL DELETE
        $req_sql = "SELECT * FROM `$name_table`";
        $response_sql = mysqli_query($cnx,$req_sql);
        if(mysqli_num_rows($response_sql) > 0){
            while($row = mysqli_fetch_array($response_sql)){
                $id = $row[0];
                $name = $row[1];
                if( $name_table == 'types'){
                    $name_lower_case = strtolower($name);
                    echo "
                    <div class='form-check mb-1'>
                        <input class='form-check-input' name='t-type' type='radio' id='task-type-$name_lower_case' value='$id'  />
                        <label class='form-check-label' for='task-type-$name_lower_case' >$name</label>
                    </div>
                    ";
                }else{
                    echo "<option value='$id'>$name</option>";
                }
            }
        }else{
            echo '<!-- no choices are found  -->';
        }
    }
?>