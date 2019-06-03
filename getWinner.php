<?php

if(!$link) {
	connectDbError($link, 'Ошибка соединения с БД');
}

/*Получаем User ID победителей с привязкой к Лот ID */
$winnersArray = get_win_array($link);

if(!empty($winnersArray)) {
    foreach ($winnersArray as $winner) {

        $updateLotWin = "UPDATE lot SET uservictory_id = ? WHERE id = ?";
        $result = db_update_data($link, $updateLotWin, [$winner['user_id'], $winner['lot_id']]);

        if (!$result) {
        	$layout_content = select_db_error('Проблема при добавления победителя в БД');
        	print($layout_content);
        	exit;
        }

		$userWin = get_info_user_by_id($link, $winner['user_id']);
		$lotName =get_lot_by_id($link, $winner['lot_id']);
		
		$msg = include_template('email.php', [
			'userWin' => $userWin, 
			'lid' => $winner['lot_id'], 
			'lotName'=> $lotName]);	

      	$transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525, tls);
		$transport->setUsername("2980890073ea10");
		$transport->setPassword("0dec0bf44763e8");
		$message = new Swift_Message("Победитель");
		$message->setTo($userWin['email']);
		$message->setBody($msg);
		$message->setFrom("keks@phpdemo.ru", "Keks");
		$mailer = new Swift_Mailer($transport);
		$mailer->send($message); 
    }
}

?> 