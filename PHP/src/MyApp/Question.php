<?php

namespace MyApp;
	
	class Question {

	//質問の答えを出す処理

   function __construct(){

      echo "Question_in\n";

   }

   public function print() {
      echo "QuestionClass_in";
   }

   public function question($questionId, $tile, $psValue) {

      echo $questionId."\n";

      switch($questionId) {

         case "q1":
         // ０はどこ？
            return $this->getWhereNumber($tile, "0");
            break;
         case "q2":
         // １または２はどこ？
            return $this->getWhereNumber($tile, $psValue);
            break;
         case "q3":
         // ３または４はどこ？
            return $this->getWhereNumber($tile, $psValue);
            break;
         case "q4":
         // ５はどこ？
            return $this->getWhereNumber($tile, "5");
            break;
         case "q5":
         // ６または７はどこ？
            return $this->getWhereNumber($tile, $psValue);
            break;
         case "q6":
         // ８または９はどこ？
            return $this->getWhereNumber($tile, $psValue);
            break;
         case "q7":
         // 奇数は何枚ある？
            // echo $this->searchOdd($tile);
         	return $this->searchOdd($tile);
            break;
         case "q8":
         // 偶数は何枚ある？
         	return $this->searchEven($tile);
            break;
         case "q9":
         // 中央の数字タイルは５以上？４以下？
            return $this->centerNumber($tile);
            break;
         case "q10":
         // 数が連続いている数字タイルはどこ？
            return $this->continuousNumber($tile);
            break;
         case "q11":
         // 赤の数字タイルは何枚ある？
         	return $this->countRed($tile);
            break;
         case "q12":
         // 青の数字タイルは何枚ある？
         	return $this->countBlue($tile);
            break;
         case "q13":
         // 赤の数字タイルの合計は？
         	return $this->getRedSum($tile);
            break;
         case "q14":
         // 青の数字タイルの合計は？
            return $this->getBlueSum($tile);
            break;
         case "q15":
         // 小さい方から3枚の数の合計は？
            return $this->smallTherdNumber($tile);
            break;
         case "q16":
         // 中央3枚の数字の合計は？
            return $this->centerTherdNumber($tile);
            break;
         case "q17":
         // 大きい方から3枚の数の合計は？
            return $this->largeTherdNumber($tile);
            break;
         case "q18":
         // 数字タイル全ての合計は？
            return $this->allNumberSum($tile);
            break;
         case "q19":
         // 同じ数字タイルのペアは何組ある？
            return $this->sameNumberPair($tile);
            break;
         case "q20":
         // 同じ色が隣り合っている数字タイルはどこ？
            return $this->continuousColor($tile);
            break;
         case "q21":
         // 数字タイルの最大から最小を引いた数は？
            return $this->comparisonNum($tile);
            break;
         default:
            break;
      }



   }

   //0はどこ？ q1
   function getWhereNumber($tile, $number){

      $count = 1;
      foreach ($tile as $value) {
         if(substr($value, 1, 2) == $number){
            return $number."は".$count."番目にあり";
         }
         $count++;
      }

      return $number."はなし";
   }


	//奇数の合計をカウント q7
	function searchOdd($tile){
		$evenCounter = 0;

		foreach ($tile as $value) {
			# code...
			$valueNum = (int)substr($value, 1);
			if ($valueNum % 2 != 0) {
				# code...
				$evenCounter++;
			}
		}

		return $evenCounter;
	}

	//偶数の合計をカウント q8
	function searchEven($tile){
		$evenCounter = 0;

		foreach ($tile as $value) {
			# code...
			$valueNum = (int)substr($value, 1);
			if ($valueNum % 2 == 0) {
				# code...
				$evenCounter++;
			}
		}

		return $evenCounter;
	}

   //中央の数字が5以上か4以下かを返す q9
   function centerNumber($tile){
      $num = array_slice($tile,2,1);
      if(substr($num[0], 1, 1) >= 5){
         return "5以上\n";
      }else{
         return "4以下\n";
      }

   }

   //数が連続している数値tileの位置を返す q10
   function continuousNumber($tile){
      $num = array_slice($tile,0,5);
      $count = array();
      for($i = 0; $i < count($num); $i++){
         $num[$i] = substr($num[$i], 1, 1);
      }
      for($i = 0; $i < count($num)-1; $i++){
         if($num[$i] == $num[$i + 1]-1){
            array_push($count, $i + 1);
         }
      }

      switch (count($count)) {
         case '0':
         return "なし\n";
         break;

         case '1':
         return $count[0]."番目と".($count[0] + 1)."番目\n";
         break;

         case '2':
         if($count[0] == $count[1]-1){
            return $count[0]."番目と".$count[1]."番目と".($count[1] + 1)."番目\n";
         }else{
            return $count[0]."番目と".($count[0]+1)."番目、".$count[1]."番目と".($count[1] + 1)."番目\n";
         }
         break;
         case'3':
            if($count[0] == $count[1]-1){
               if( $count[1] == $count[2]-1){
                  return $count[0]."番目と".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目\n";
               }else{
                  return $count[0]."番目と".$count[1]."番目と".($count[1]+1)."番目、".$count[2]."番目と".($count[2]+1)."番目\n";
               }
            }else{
               return $count[0]."番目と".($count[0]+1)."番目。".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目\n";
            }
            break;
         case'4':
            return $count[0]."番目と".$count[1]."番目と".$count[2]."番目と".$count[3]."番目と".($count[3]+1)."番目\n";
            break;
         default:
            return "想定外のエラー\n";
            break;
      }
   }

// 	// 最大値から最小値引いた数を求める
// 	function comparisonNum(){
// 		$num=[array_slice($tile
// ,0,1),array_slice($tile
// ,-1,1)];
// 		$num = (int)substr($num[1][0],1) - (int)substr($num[0][0],1);
// 		echo "最大値から最小値引いた値は".$num."です\n";
// 	}

   //赤タイルの枚数を求める q11
   function countRed($tile){
      $count = 0;
      foreach ($tile as $value) {
      # code...
         if(substr($value, 0, 1) == "r"){
            $count++;
         }
      }
      return $count."枚";
   }

	//青タイルの枚数を求める q12
	function countBlue($tile){
		$count = 0;
		foreach ($tile as $value) {
		# code...
			if(substr($value, 0, 1) == "b"){
				$count++;
			}
		}
		// echo "青カードの合計は".$count."です\n";
		return $count;
	}

   //赤の合計を出す q13
   function getRedSum($tile){
         
      $sum = 0;
      foreach ($tile as $value) {
         # code...
         if(substr($value, 0, 1) == "r"){
            $sum += (int)substr($value, 1);
         }
      }
      return $sum;

   }

	//青の合計を出す q14
   function getBlueSum($tile) {

      $sum = 0;
      foreach ($tile as $value) {
         # code...
         if(substr($value, 0, 1) == "b"){
            $sum += (int)substr($value, 1);
         }
      }
      return $sum;
   }

   //小さい方から3枚の数字の合計 q15
   function smallTherdNumber($tile){
      $num = array_slice($tile,0,3);
      for($i = 0; $i < count($num); $i++){
         $num[$i] = substr($num[$i], 1, 1);
      }

      return array_sum($num);
   }

   //中央の3枚の合計を返す  q16
   function centerTherdNumber($tile){
      $num = array_slice($tile,1,3);
      for($i = 0; $i < count($num); $i++){
         $num[$i]=substr($num[$i],1,1);
      }

      return array_sum($num);
   }

   //大きい方から3枚の数字の合計 q17
   function largeTherdNumber($tile){
      $num = array_slice($tile,2,3);
      for($i = 0; $i < count($num); $i++){
         $num[$i] = substr($num[$i], 1, 1);
      }
      
      return array_sum($num);
   }

   // 数字タイル全ての合計は？ q18
   function allNumberSum($tile) {
      $num = array();
      foreach ($tile as $value) {
         $num[] = substr($value, 1, 1);
      }
      
      return array_sum($num);
   }

   //同じ数字のtileのペアが何組あるか返す q19
   function sameNumberPair($tile){
      $num = array_slice($tile, 0, 5);
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
         return  "なし\n";
         break;

         case '2':
         return "1組\n";
         break;

         case '4':
         return "2組\n";
         break;

         default:
         return "想定外のエラー\n";
         break;
      }

   }

   //同じ色が隣り合っている数字tileを返す q20
   function continuousColor($tile){
      $num = array_slice($tile,0,5);
      $count = array();
      for($i = 0; $i < count($num); $i++){
         $num[$i] = substr($num[$i], 0, 1);
      }
      for($i = 0; $i < count($num)-1; $i++){
         if($num[$i] == $num[$i + 1]){
            array_push($count, $i + 1);
         }
      }
      switch (count($count)) {
         case '0':
         return "なし\n";
         break;

         case '1':
         return $count[0]."番目と".($count[0] + 1)."番目\n";
         break;

         case '2':
         if($count[0] == $count[1]-1){
            return $count[0]."番目と".$count[1]."番目と".($count[1] + 1)."番目\n";
         }else{
            return $count[0]."番目と".($count[0]+1)."番目、".$count[1]."番目と".($count[1] + 1)."番目\n";
         }
         break;
         case'3':
         if($count[0] == $count[1]-1){
            if( $count[1] == $count[2]-1){
               return $count[0]."番目と".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目\n";
            }else{
               return $count[0]."番目と".$count[1]."番目と".($count[1]+1)."番目、".$count[2]."番目と".($count[2]+1)."番目\n";
            }
         }else{
            return $count[0]."番目と".($count[0]+1)."番目、".$count[1]."番目と".$count[2]."番目と".($count[2] + 1)."番目\n";
         }
         break;
         case'4':
         return $count[0]."番目と".$count[1]."番目と".$count[2]."番目と".$count[3]."番目と".($count[3]+1)."番目\n";
         break;
         default:
         return "想定外のエラー\n";
         break;
      }
   }

   // 最大値から最小値引いた数を求める
   function comparisonNum($tile){
      $num = [array_slice($tile, 0, 1),array_slice($tile, -1, 1)];
      $num = (int)substr($num[1][0],1) - (int)substr($num[0][0],1);
      return $num;
   }



}

?>