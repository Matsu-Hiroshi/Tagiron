<?php
	
	namespace MyApp;

	require_once("/Applications/MAMP/htdocs/project/PHP/src/MyApp/Tagiron.php");

	class RelayTagiron {

		public function __construct() {
			session_start();
		}

		//新しい場を作る
		public function add($key, $conns) {
			echo "roomId:".$key."\n";
			$_SESSION[$key] = new Tagiron($conns);
		}

		public function getNextPlayer($key){
			return $_SESSION[$key]->getNextPlayer();
		}

		public function declaration($key, $conn, $anstile){
			return $_SESSION[$key]->declaration($conn, $anstile);
		}


		//場の情報
		public function getField($key) {
			return $_SESSION[$key]->getField();
		}

		public function reloadField($key, $id) {
			return $_SESSION[$key]->reloadQuestion($id);
		}


		//質問関数
		public function question($key, $conn, $questionId, $psValue) {
			return $_SESSION[$key]->getAnswer($questionId, $conn, $psValue);
		}

		//場を消す
		public function remove($key) {
			unset($_SESSION[$key]);
			echo "unset:".$key."\n";
		}

	}

?>