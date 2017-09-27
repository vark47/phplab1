<?php
//get tasklist array from POST
$task_list = filter_input(INPUT_POST, 'tasklist', 
        FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
if ($task_list === NULL) {
    $task_list = array();
    
    // add some hard-coded starting values to make testing easier
    $task_list[] = 'Write chapter';
    $task_list[] = 'Edit chapter';
    $task_list[] = 'Proofread chapter';
}

//get action variable from POST
$action = filter_input(INPUT_POST, 'action');

//initialize error messages array
$errors = array();

//process
switch( $action ) {
    case 'Add Task':
        $new_task = filter_input(INPUT_POST, 'newtask');
        if (empty($new_task)) {
            $errors[] = 'The new task cannot be empty.';
        } else {
            $task_list[] = $new_task;
        }
        break;
    case 'Delete Task':
        $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_index === NULL || $task_index === FALSE) {
            $errors[] = 'The task cannot be deleted.';
        } else {
            unset($task_list[$task_index]);
            $task_list = array_values($task_list);
			//condenses the array to remove the empty index
        }
        break;

    case 'Modify Task':
	$task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
	if($task_index !== NULL && $task_index !== FALSE)
		$task_to_modify = $task_list[$task_index];

		break;

    case 'Save Changes':
	$task_index = filter_input(INPUT_POST, 'modifiedtaskid', FILTER_VALIDATE_INT);
	if($task_index !== NULL && $task_index !== FALSE){
		$modified_task = filter_input(INPUT_POST, 'modifiedtask');
		}
		if($modified_task == NULL)
		{
			$errors[] = 'Delete the task rather than modifying it please';
		}
		else{
	$task_list[$task_index] = $modified_task;
       }
	   break;
    case 'Cancel Changes':
	break;

   
    case 'Promote Task':
	  $promoted_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
	  if($promoted_index != NULL && $promoted_index !== FALSE && $promoted_index > 0)
	  {
       $temp = $task_list[$promoted_index - 1];
	   $task_list[$promoted_index - 1] = $task_list[$promoted_index];
	   $task_list[$promoted_index] = $temp;
	   }

	   break;
	     
    case 'Sort Tasks':
		sort($task_list, SORT_NATURAL | SORT_FLAG_CASE);
		break;
 

}

include('task_list.php');
?>