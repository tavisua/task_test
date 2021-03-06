<?php

return array(
	'tasks'=>'tasks/index',
	'new'=>'tasks/newTask',
	'save'=>'tasks/saveTask',
	'save/[0-9]+'=>'tasks/saveTask',
	'page/[0-9]+'=>'tasks/viewTasks/$1',
	'sort/[a-z]+/[a-z]+'=>'tasks/viewSortTasks/$1/$2/$3',
	'auth'=>'autorization/index',
	'validation'=>'autorization/validate',
	'exit'=>'autorization/exitAdmin',
	'edit/[0-9]+'=>'tasks/editTask',
);
