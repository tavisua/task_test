<?php


class Tasks {

	/*Возвращает перечень задач
	 * @param int pageNumber
	 * @param int perPage
	 * @param string columnName
	 * @param string order
	 * @result array*/
	function getTasksList($page = 1,  $columnSort='', $order = '', $per_page = 3){
		global $db;
		$sql = "select * from tasks where 1 ";
		if($columnSort)
			$sql .= "order by ".$columnSort;
		if($order)
			$sql .= " ".$order;

		$res = $db->query($sql);
		//Создаю html табуляции страниц
		$pageCount = ceil($res->num_rows/$per_page);
		$tabPage = '<div class="dataTables_paginate paging_simple_numbers" id="tenderTable_paginate"><ul class="pagination">';
		$tabPage.='<li class="paginate_button previous '.($page > 1?'':'disabled').'" tabindex="0"><a href="/page/'.($page>1?($page-1):1).'">Пред.</a></li>';

		for($i=1; $i<=$pageCount; $i++){
			$tabPage.='<li class="paginate_button '.($page == $i?'active':'').'" tabindex="0"><a href="/page/'.$i.'">'.$i.'</a></li>';
		}

		$tabPage.='<li class="paginate_button next '.($page<$pageCount?'':'disabled').' " tabindex="0"><a href="/page/'.($i<$pageCount?$i:$pageCount).'">След.</a></li>';
		$tabPage.='</ul></div>';

		$table_content = '';
		$rowIndex = 0;//Порядковый номер результата запроса
		while ($row = $res->fetch_object()){
			++$rowIndex;
			if(ceil($rowIndex/$per_page) == $page) {
				$table_content .= "<tr><td>$row->username</td><td>$row->email</td><td>".($row->state?'<img src="/img/done.png">':'')."</td>";
				if($_SESSION['autorization'])
					$table_content .= '<td><a href="/edit/'.$row->id.'"><img src="/img/edit.png"></a></td>';
				$table_content .="</tr>";
			}elseif (floor($rowIndex/$per_page) > $page)
				break;
		}



		return ['table_content'=>$table_content, 'tabPage'=>$tabPage ];
	}

	/*Сохраниет новые или редактирует существующие задачи
	 *@param array информация о задаче
	 * @return bit*/
	function saveTask($parameters){
		global $db;

		if($parameters['id'])
			$sql="update tasks set `task` = '".$parameters['task']."', state=".($parameters['state']?1:0)." where id=".$parameters['id'];
		else
			$sql="insert into tasks(`username`,`email`,`task`) values('".$parameters['username']."', '".$parameters['email']."', '".$parameters['task']."')";
		$db->query($sql);
		if($parameters['id'])
			$id = $parameters['id'];
		else
			$id = $db->insert_id;
		return $id;
	}

	/*Загружает информацию о задаче
	 * @param int
	 * @return array*/
	function getTask($id){
		global $db;
		$sql = "select * from tasks where id = $id";
		$res = $db->query($sql);
		return $res->fetch_array();
	}
}