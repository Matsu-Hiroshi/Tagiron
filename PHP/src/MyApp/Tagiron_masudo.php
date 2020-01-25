<?php

$Question = new Question();

class Tagiron{
		//タギロンで使う数字と色を定数で宣言	
	const Tiles = ["1" => "b0", "3" => "b1", "5" =>"b2", "7" => "b3", "9" => "b4", "11" => "y5", "13" => "b6", "15" => "b7", "17" => "b8", "19" => "b9", "0" => "r0", "2" => "r1", "4" => "r2", "6" => "r3", "8" => "r4", "10" => "y5", "12" => "r6", "14" => "r7", "16" => "r8", "18" => "r9"];

		//タギロンで使う質問カードを定数で宣言		
	const Quesitons = ["0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19"];

	public $player1 = array();
	public $player2 = array();
	public $player3 = array();
	public $player4 = array();
	public $quesiton;

	function __construct(){
			// プレイヤーが使う数字を取り出す	
		$setTiles =array();
		$rand =array_rand(self::Tiles,10);
		for($i = 0; $i <10; $i++){
			$setTiles = $setTiles + array($rand[$i] => self::Tiles[$rand[$i]]);
		}

			//質問を６個取り出す
		$setQuestion = array_values(self::Quesitons);
		shuffle($setQuestion);
		$this ->quesiton = array_slice($setQuestion, 0,6);

			//各プレイヤーが使う数字を取り出す
		shuffle($rand);
		for($i=0; $i < 5; $i++){
			$this ->player1 = $this ->player1 + array($rand[$i] => $setTiles[$rand[$i]]);
			$this ->player2 = $this ->player2 + array($rand[$i+5] => $setTiles[$rand[$i+5]]);
		}	
		ksort($this ->player1);
		ksort($this ->player2);
		print_r($this ->player1);
		print_r($this ->player2);
	}


// function add($roomid){
// 	$_Session
// }
		// function __construct($conn1, $conn2) {

		// 	//10個のtileをランダムに取り出す
		// 	$getTailes = array_rand(shuffle(Tiles),10);
	
		// 	//取り出し6
		// 	$quesitons = array_rand($quesitons,6);
		// 	$gameTiles = array(
		// 		//5枚のタイルを取り出す
		// 		$conn1 => array();
		// 		$conn2 => array();
		// );
		// 	return Array((String)$tiles1, (String)$tiles2, (String)$quesitons) ;
		// 	function add(){

		// 	}
		// }

	function getAnswer($quesitons,$conn){
		//$connの$quesitonを返す
	}

		//質問の破棄、追加
	function setQuestion(){
		
	}

		//宣言
	function declaration($anstile){

	}
}


class Question extends Tagiron{

		//質問の答えを出す処理

		//青の合計を出す
	function getBlueSum() {

		$sum = 0;
		foreach ($this->player1 as $value) {
				# code...
			if(substr($value, 0, 1) == "b"){
				$sum += (int)substr($value, 1);
			}
		}
		echo "青の合計".$sum."\n";
	}

		//赤の合計を出す
	function getRedSum(){
		
		$sum = 0;
		foreach ($this->player1 as $value) {
				# code...
			if(substr($value, 0, 1) == "r"){
				$sum += (int)substr($value, 1);
			}
		}
		echo "赤の合計".$sum."\n";

	}
	
			//青の数字tileの枚数を出す
	function countBlueSum() {

		$count;
		foreach ($this->player1 as $value) {
				# code...
			if(substr($value, 0, 1) == "b"){
				$count++;
			}
		}
		echo "青の合計".$count."\n";
	}

		//赤の数字tileの枚数を出す
	function countRedSum(){
		
		$count;
		foreach ($this->player1 as $value) {
				# code...
			if(substr($value, 0, 1) == "r"){
				$count;
			}
		}
		echo "赤の合計".$count."\n";

	}
	
	//指定された数字の位置を返す
	function searchNumber($num){
		$checkNum = array();
		$count = 0;
		foreach ($this->player1 as  $value) {
			$count++;
			if(substr($value, 1) == (int)$num){
				array_push($checkNum,$count);
			}
		}

		switch (count($checkNum)) {
			case 1:
			echo $num."の位置は".$checkNum[0]."番目です\n";
			break;
			
			case 2:
			echo $num."の位置は".$checkNum[0]."番目と".$checkNum
			[1]."番目です\n";
			break;

			default:
			echo "このプレイヤーに".$num."はありません\n";
			break;
		}


	}

	//奇数の合計をカウント
	function searchOdd(){
		$checkNum = array();
		$count = 0;
		foreach ($this->player1 as  $value) {
			$count++;
			if(((int)substr($value, 1) % 2) == 1){
				array_push($checkNum,$count);
			}
			
		}
		echo "奇数の数は".count($checkNum)."です\n";
	}

	//偶数の合計をカウント
	function searchEven(){
		$checkNum = array();
		$count = 0;
		foreach ($this->player1 as  $value) {
			$count++;
			if(((int)substr($value, 1) % 2) == 0){
				array_push($checkNum,$count);
			}
			
		}
		echo "偶数の数は".count($checkNine)."です\n";
	}

	// 最大値から最小値引いた数を求める
	function comparisonNum(){
		$num=[array_slice($this->player1,0,1),array_slice($this->player1,-1,1)];
		$num = (int)substr($num[1][0],1) - (int)substr($num[0][0],1);
		echo "最大値から最小値引いた値は".$num."です\n";
	}

	//青カードの合計を求める
	function countBlue(){
		$count = 0;
		foreach ($this->player1 as $value) {
		# code...
			if(substr($value, 0, 1) == "b"){
				$count++;
			}
		}
		echo "青カードの合計は".$count."です\n";
	}

	//赤カードの合計を求める
	function countRed(){
		$count = 0;
		foreach ($this->player1 as $value) {
			if(substr($value, 0, 1) == "r"){
				$count++;
			}
		}
		echo "赤カードの合計は".$count."です\n";
	}

	//中央の数字が5以上か4以下かを返す
	function centerNumber(){
		$num = array_slice($this->player1,2,1);
		if(substr($num[0], 1, 1) >= 5){
			echo "中央の数字は5以上です\n";
		}else{
			echo "中央の数字は4以下です\n";
		}

	}

	//大きい方から3枚の数字の合計
	function large3rdNumber(){
		$num = array_slice($this->player1,2,3);
		for($i = 0; $i < count($num); $i++){
			$num[$i] = substr($num[$i], 1, 1);
		}
		echo "大きい方から3枚の数字の合計は".array_sum($num)."です\n";
	}

	//小さい方から3枚の数字の合計
	function small3rdNumber(){
		$num = array_slice($this->player1,0,3);
		for($i = 0; $i < count($num); $i++){
			$num[$i] = substr($num[$i], 1, 1);
		}
		echo "小さい方から3枚の数字の合計は".array_sum($num)."です\n";
	}

	//同じ数字のtileのペアが何組あるか返す
	function sameNumberPair(){
		$num = array_slice($this->player1,0,5);
		$count = array();
		for($i = 0; $i < count($num); $i++){
			$num[$i] = substr($num[$i], 1, 1);
		}
		for($i = 0; $i < count($num)-1; $i++){
			if($num[$i] == $num[$i + 1]){
				array_push($count, $i);
				array_push($count, $i + 1);
			}
		}

		switch (count($count)) {
			case '0':
			echo "同じ数値のtileのペアはありません\n";
			break;

			case '2':
			echo "同じ数値のtileのペアは1組あります\n";
			break;

			case '4':
			echo "同じ数値のtileのペアは2組あります\n";
			break;

			default:
			echo "想定外のエラー\n";
			break;
		}

	}

	//数が連続している数値tileの位置を返す
	function continuousNumber(){
		$num = array_slice($this->player1,0,5);
		$count = array();
		for($i = 0; $i < count($num); $i++){
			$num[$i] = substr($num[$i], 1, 1);
		}
		for($i = 0; $i < count($num)-1; $i++){
			if($num[$i] == $num[$i + 1]-1){
				array_push($count, $i);
			}
		}
		switch (count($count)) {
			case '0':
			echo "数が連続している数値tileはありません\n";
			break;

			case '1':
			echo "数が連続しているtileの位置は".$count[0]."番目と".($count[0] + 1)."番目です\n";
			break;

			case '2':
			if($count[0] == $count[1]-1){
				echo "数が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".($count[1] + 1)."番目です\n";
			}else{
				echo "数が連続しているtileの位置は".$count[0]."番目と".($count[0]+1)."番目。".$count[1]."番目と".($count[1] + 1)."番目です\n";
			}
			break;
			case'3':
			if($count[0] == $count[1]-1){
				if( $count[1] == $count[2]-1){
					echo "数が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目です\n";
				}else{
					echo "数が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".($count[1]+1)."番目。".$count[2]."番目と".($count[2]+1)."番目です\n";
				}
			}else{
				echo "数が連続しているtileの位置は".$count[0]."番目と".($count[0]+1)."番目。".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目です\n";
			}
			break;
			case'4':
			echo "数が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".$count[2]."番目と".$count[3]."番目と".($count[3]+1)."番目です\n";
			break;
			default:
			echo "想定外のエラー\n";
			break;
		}
	}

	//同じ色が隣り合っている数字tileを返す
	function continuousColor(){
		$num = array_slice($this->player1,0,5);
		$count = array();
		for($i = 0; $i < count($num); $i++){
			$num[$i] = substr($num[$i], 0, 1);
		}
		for($i = 0; $i < count($num)-1; $i++){
			if($num[$i] == $num[$i + 1]){
				array_push($count, $i);
			}
		}
		switch (count($count)) {
			case '0':
			echo "色が連続している数値tileはありません\n";
			break;

			case '1':
			echo "色が連続しているtileの位置は".$count[0]."番目と".($count[0] + 1)."番目です\n";
			break;

			case '2':
			if($count[0] == $count[1]-1){
				echo "色が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".($count[1] + 1)."番目です\n";
			}else{
				echo "色が連続しているtileの位置は".$count[0]."番目と".($count[0]+1)."番目。".$count[1]."番目と".($count[1] + 1)."番目です\n";
			}
			break;
			case'3':
			if($count[0] == $count[1]-1){
				if( $count[1] == $count[2]-1){
					echo "色が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目です\n";
				}else{
					echo "色が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".($count[1]+1)."番目。".$count[2]."番目と".($count[2]+1)."番目です\n";
				}
			}else{
				echo "色が連続しているtileの位置は".$count[0]."番目と".($count[0]+1)."番目。".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目です\n";
			}
			break;
			case'4':
			echo "数が連続しているtileの位置は".$count[0]."番目と".$count[1]."番目と".$count[2]."番目と".$count[3]."番目と".($count[3]+1)."番目です\n";
			break;
			default:
			echo "想定外のエラー\n";
			break;
		}
	}

	//中央の3枚の合計を返す	
	function center3rdNUmber(){
		$num = array_slice($this->player1,1,3);
		for($i = 0; $i < count($num); $i++){
			$num[$i]=substr($num[$i],1,1);
		}
		echo "中央の3枚の数字の合計は".array_sum($num)."です\n";
	}	


}

?>