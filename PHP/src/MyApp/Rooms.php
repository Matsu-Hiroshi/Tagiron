<?php
namespace MyApp;

class Room extends Chat{

	//コンストラクタ
	function __construct() {
		session_start();
		$_SESSION['count'] = 0;
		$_SESSION['rooms'] = array();
		$_SESSION['room'] = array();
	}

	//ソケットに繋がった順番でルームを作る
	public function add($conn) {

		$_SESSION['count']++;

		if($_SESSION['count'] % 2 == 0) {
			// $_SESSION['room'][] = $conn;
			// $_SESSION['rooms'] += array($_SESSION['ran'] => $_SESSION['room']);
			// $_SESSION['room'] = array();
			// var_dump($_SESSION['rooms'][$_SESSION['ran']]);
			$_SESSION['rooms'][$_SESSION['ran']][] = $conn;
		}else {
			$_SESSION['ran'] = uniqid(rand(), false);
			$_SESSION['room'][] = $conn;
			$_SESSION['rooms'] += array($_SESSION['ran'] => $_SESSION['room']);
			$_SESSION['room'] = array();
		}

		return $_SESSION['ran'];
	}

	//ルームIDと送信者を使い同じ部屋の相手を返す
	public function getOpponent($key) {

		$room = $_SESSION['rooms'][$key];
		$menberNum = count($room);
		$member = array();
		for($i = 0;$i < $menberNum;$i++) {
			$member[] = $room[$i];
		}

		if (isset($member)) {
			return $member;
		}
		
		return NULL;
	}

	//keyを返す
	public function getKey($conn){
		foreach ($_SESSION['rooms'] as $key => $value) {
			if(in_array($conn, $value)){
				return $key;
			}
		}

		return NULL;
	}

	//部屋の削除
	public function remove($key){

		unset($_SESSION['rooms'][$key]);

	}
}

?>