<?php

namespace MyApp;

	class Tagiron{
		//タギロンで使う数字と色を定数で宣言	
		const Tiles = ["1" => "b0", "3" => "b1", "5" =>"b2", "7" => "b3", "9" => "b4", "11" => "y5", "13" => "b6", "15" => "b7", "17" => "b8", "19" => "b9", "0" => "r0", "2" => "r1", "4" => "r2", "6" => "r3", "8" => "r4", "10" => "y5", "12" => "r6", "14" => "r7", "16" => "r8", "18" => "r9"];

		//タギロンで使う質問カードを定数で宣言		w
		private $quesitons;

		private static $questionClass;

		public $players = array();

		public $question = array();

		public static function sort($tile){
			
			$tile = array_values($tile);
			$sortTile = [];
			for ($i=0; $i < count($tile); $i++) { 
				# code...
				$key = array_search($tile[$i], Tagiron::Tiles);
				$sortTile += array($key => $tile[$i]);
			}

			ksort($sortTile);
			return $sortTile;
		}

		public static function shareCard($qid){
			$cards = ['q9', 'q18', 'q21'];
			if(in_array($qid, $cards)){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		function __construct($conns){
			// $conns; // cliantが入っている配列

			$this->questions = ["q1","q2","q3","q4","q5","q6","q7","q8","q9","q10","q11","q12","q13","q14","q15","q16","q17","q18","q19", "q20", "q21"];

			require_once("Question.php");

			self::$questionClass = new Question();

			$playerNum = count($conns);//playerの人数
			$useTiles;//使うタイルの数
			$perTiles;//一人あたりが使うタイルの数
			if($playerNum < 4) {
				$perTiles = 5;
				$useTiles = $perTiles * $playerNum;
			}else {
				$perTiles = 4;
				$useTiles = $perTiles * $playerNum;
			}

			$setTiles = array();
			$rand = array_rand(self::Tiles, $useTiles);
			for($i = 0; $i < $useTiles; $i++) {
				$setTiles += [$rand[$i] => self::Tiles[$rand[$i]]];
			}

			shuffle($this->questions);

			for($i = 0;$i < 6;$i++) {
				$this->question[] = array_shift($this->questions);
			}

			for($i = 0;$i < $playerNum;$i++){
				$this->players += [ $i => array() ];
				$this->players[$i] += [ "tile" => array() ];
			}

			$tileCounter = 0;
			for($i = 0; $i < $playerNum; $i++) {
				for ($j = 0; $j < $perTiles; $j++) { 
					# code...
					$this->players[$i]["tile"] += [ $rand[$tileCounter] => $setTiles[$rand[$tileCounter]] ];
					$tileCounter++;
				}
			}
			
			//タイルの並べ替え
			for($i = 0; $i < $playerNum; $i++) {
				ksort($this->players[$i]["tile"]);
			}

			//player配列にcliant要素を追加
			for($i = 0; $i < $playerNum; $i++) {
				$this->players[$i] += ["cliant" => $conns[$i]];
			}
		}

		public $nowPlayer = 0;
		function getNextPlayer() {

			$playerNum = count($this->players);
			if($this->nowPlayer < $playerNum - 1){
				$this->nowPlayer += 1;
			}else{
				$this->nowPlayer = 0;
			}

			return $this->players[$this->nowPlayer]["cliant"];
		}

		function reloadQuestion($id) {
			$repraceArray = array(array_search($id, $this->question) => array_shift($this->questions));
			$this->question = array_replace($this->question, $repraceArray);

			return $this->question;
		}

		function getField() {

			$field = array();
			$field += [ "players" => array() ];
			$playerNum = count($this->players);
			for($i = 0;$i < $playerNum;$i++) {
				$field["players"][] = $this->players[$i];
			}
			$field += [ "question" => $this->question ];
			return $field;

		}

		function getTile($conn) {
			if($this->player1["cliant"] == $conn) {
				return $this->player1["tile"];
			} else {
				return $this->player2["tile"];
			}
		}

		function getAnswer($questionId, $conn, $psValue) {
		//$connの$quesitonを返す

			for($i = 0;$i < count($this->players); $i++) {
				if($this->players[$i]["cliant"] == $conn) {
					$ans = self::$questionClass->question($questionId, $this->players[$i]["tile"], $psValue);
					return $ans;
				}
			}
		}

		//質問の破棄、追加
		function setQuestion(){
			
		}

		//宣言
		function declaration($conn, $anstile){

			for ($i=0; $i < count($this->players); $i++) { 
				# code...

				if($this->players[$i]["cliant"] != $conn){

					$anstile = array_values($anstile);
					$playerTile = array_values($this->players[$i]["tile"]);

					for ($i=0; $i < count($anstile); $i++) { 
						# code...

						if($anstile[$i] != $playerTile[$i]){
							return FALSE;
						}
					}

					return TRUE;
				}
			}

		}
	}

?>