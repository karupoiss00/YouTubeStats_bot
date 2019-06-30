<?php
	include('vendor/autoload.php'); //Подключаем библиотеку
	include('url_handler.php');
	use Telegram\Bot\Api;
	
	$telegram = new Api('885752742:AAF63rND57OidzVAJ3ReDp7qGkX7oVaunBY');
	$result = $telegram->getWebhookUpdates();
	$text = $result["message"]["text"];
	$chat_id = $result["message"]["chat"]["id"];
	$name = $result["message"]["from"]["first_name"];
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->autoReconnect = true;
	
	
	if($text) {
		if ($text == '/start') {
			if (strlen($name) == 0) {
				$reply = 'Добро пожаловать, Незнакомец!';
			}
			else {
				$reply = 'Добро пожаловать, '.$name.'!';
			}
			$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
		}
		elseif ($text == '/help') {
            $reply = HELP_REPLY;
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
		}
		elseif ($text == 'тест') {
			$db->where ("id", 31244234);
			$res = $db->getOne('user_request_history');
			$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $res['first_request']);
		}
        else {
			if (strpos($text, 'http') === FALSE) {
				$text = 'http://'.$text;
			}
			if (strpos($text, 'bit.ly') === FALSE) {
				$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => getShortUrl($text)]);
			}
			else {
				$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => getLongUrl($text)]);
			}
        }
    }
	else {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Отправьте текстовое сообщение.' ]);
	}