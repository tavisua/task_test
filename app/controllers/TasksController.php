<?php

include_once APP.'/model/Tasks.php';
class TasksController {
	protected $UpBtn = '<img src="/img/1uparrow.png" alt="">';
	protected $DownBtn = '<img src="/img/1downarrow.png" alt="">';
	/*Отображает список сохраненных задач
	 * */
	function index(){
		$this->showTasks();//Отображение списка задач
	}

	/*Отображение списка задач
	 * */
	function showTasks(){
		$page = 1;
		$html = Tasks::getTasksList();//Загружаю перечень задач
		$table_content = $html['table_content'];
		$tabPage = $html['tabPage'];
		include_once WWW.'/tasks/tasklist.html';
		return true;
	}

	/*Создаем новую или сохраняем изменения текущей задачи
	 * @params array массив параметров задачи
	 * */
	function saveTask($id, $username, $email, $task, $state = false, $picture=null){
		//массив для сохранения в БД
		$parameters = ['id'=>$id, 'username'=>$username, 'email'=>$email, 'task'=>$task, 'state'=>$state, 'picture'=>$picture];

		$taskID = Tasks::saveTask($parameters);

		if ($parameters['picture'] ){
			list($width, $height, $type, $attr) = getimagesize($parameters['picture']);

			if($height>240 || $width > 320) {
				if($height - 240 > $width - 320)
					$this->resize($parameters['picture'], 0, 240);
				else
					$this->resize($parameters['picture'], 320, 0);
			}
			$types = array("", "gif", "jpeg", "png");
			move_uploaded_file($parameters['picture'], WWW.'/task_picture/task'.$taskID.'.'.$types[$type]);

		}
		$html = Tasks::getTasksList();//Загружаю перечень задач
		$table_content = $html['table_content'];
		$tabPage = $html['tabPage'];
		include_once WWW.'/tasks/tasklist.html';
	}

	/*Изменяем пропорционально размеры изображения, если они выходят за рамки
	 *@param string название входящего файла
	 *@param int новая ширина
	 *@param int новая высота

	 **/
	function resize($image, $w_o = 0, $h_o = 0) {
		if (($w_o < 0) || ($h_o < 0)) {
			echo "Некорректные входные параметры";
			return false;
		}
		list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
		$types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
		$ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
		if ($ext) {
			$func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
			$img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
		} else {
			echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
			return false;
		}
		/* Если указать только 1 параметр, то второй подстроится пропорционально */
		if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
		if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
		$img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
		imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
		$func = 'image'.$ext; // Получаем функция для сохранения результата
		return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
	}


	/*Просмотр страницы без сортировки
	 *@param int ID задачи
	 *@return string HTML для отображения предварительного просмотре  */
	function viewTasks($id, $field='',$order = ''){
		$page = $id;
		$html = Tasks::getTasksList($id);//Загружаю перечень задач
		$table_content = $html['table_content'];
		$tabPage = $html['tabPage'];
		include_once WWW.'/tasks/tasklist.html';
		return true;
	}

	/*Просмотр страницы с указанной сортировкой и номером
	 *@param int ID задачи
	 *@return string HTML для отображения предварительного просмотре  */
	function viewSortTasks($field, $order, $page = 1){
		$html = Tasks::getTasksList($page, $field, $order);//Загружаю перечень задач
		$table_content = $html['table_content'];
		$tabPage = $html['tabPage'];
		include_once WWW.'/tasks/tasklist.html';
		return true;
	}

	/*Отображает форму создания новой задачи
	 * */
	function newTask(){
		include_once WWW.'/tasks/task_editor.html';
	}

	/*Отображает форму редактирования существующей задачи
	 * */
	function editTask($id){
		$array = Tasks::getTask($id);
		$username = $array['username'];
		$email = $array['email'];
		$task = $array['task'];
		$state = $array['state'];
		$types = array("gif", "jpeg", "png");

		foreach ($types as $type) {
			if ( file_exists( WWW.'/task_picture/task'.$id.'.'.$type)) {
				$picture =  '/task_picture/task'.$id.'.'.$type;
				break;
			}
		}

		include_once WWW.'/tasks/task_editor.html';
	}
}