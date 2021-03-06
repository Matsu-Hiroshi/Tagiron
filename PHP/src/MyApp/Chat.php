<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    private $room;

    private $tagironData;

    public function __construct() {
        $this->clients = new \SplObjectStorage;

        require_once ("Rooms.php");
        $this->room = new Room();

        require_once ("relayTagiron.php");
        $this->tagironData = new RelayTagiron();

    }

    //新しいクライアントの接続
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
        $roomId = $this->room->add($conn);
        $opponent = $this->room->getOpponent($roomId);

        if(count($opponent) >= 2){
            $this->tagironData->add($roomId, $opponent);
            $this->gameStart($this->tagironData->getField($roomId), $roomId);
        }

        $date = [
            "type" => "connect",
            "roomId" => (string)$roomId
        ];

        $msg = json_encode($date);
        $conn->send($msg);
    }

    //ゲームスタート
    private function gameStart($field, $roomId) {
        $playerNum = count($field["players"]);

        $nextPlayer = $this->tagironData->getNextPlayer($roomId);
        for ($i=0; $i < $playerNum; $i++) {

            $data = [
                "type" => "gameStart",
                "tile" => $field["players"][$i]["tile"],
                "question" => $field["question"]
            ];

            if($field["players"][$i]["cliant"] == $nextPlayer){
                $data += array("turn" => "true");
            }else{
                $data += array("turn" => "false");
            }

            $msg = json_encode($data);
            $this->send($field["players"][$i]["cliant"], $msg);
        }
    }

    //クライアントからのメッセージの受信
    public function onMessage(ConnectionInterface $from, $msg) {

        $data = json_decode($msg, true);
        $member = $this->room->getOpponent($data["roomId"]);
        $memberNum = count($member);

        switch ($data["type"]) {
            case 'question':
                # code
                $this->question($data, $member, $memberNum, $from);
                break;

            case 'answer':
                $nextPlayer = $this->tagironData->getNextPlayer($data['roomId']);

                $tile = Tagiron::sort($data['aArray']);
                $answer = $this->tagironData->declaration($data['roomId'], $from, $tile);
                
                for ($i=0; $i < $memberNum; $i++) { 
                    # code...

                    if($answer == TRUE){
                        $sendData = [
                            "type" => "answer",
                            "tof" => "true"
                        ];
                    }else{
                        $sendData = [
                            "type" => "answer",
                            "tof" => "false"
                        ];
                    }

                    if($member[$i] == $from){
                        $sendData += array("player" => "あなた");
                    }else{
                        $sendData += array("player" => "相手");
                    }

                    if($member[$i] == $nextPlayer){
                        $sendData += array("turn" => "true");
                    }else {
                        $sendData += array("turn" => "false");
                    }

                    $msg = json_encode($sendData);
                    $this->send($member[$i], $msg);
                }


            break;

            case 'message':
                for($i=0; $i < $memberNum; $i++) { 
                    # code...
                    if($member[$i] != $from){
                        $sendData = [
                            "type" => "chat",
                            "msg" =>  $data['msg']
                        ];

                        $msg = json_encode($sendData);
                        $this->send($member[$i], $msg);
                    }
                }
            break;

            default:
                # code...
            break;
        }
        
    }

    private function question($data, $member, $memberNum, $from){

        $nextPlayer = $this->tagironData->getNextPlayer($data['roomId']);
        $newField = $this->tagironData->reloadField($data['roomId'], $data['id']);

        for ($i=0; $i < $memberNum; $i++) { 
            # code...
            $sendData = [];
            if($member[$i] == $from){

                for ($j=0; $j < $memberNum; $j++) {
                    # code...
                    if($member[$i] != $member[$j]){
                        $anser = $this->tagironData->question($data['roomId'], $member[$j], $data['id'], $data["qsValue"]);

                        $sendData = [
                            "type" => "question",
                            "qID" => $data['id'],
                            "anser" => $anser,
                            "field" => $newField
                        ];
                        
                        if($member[$i] == $nextPlayer){
                            $sendData += array("turn" => "true");
                        }else{
                            $sendData += array("turn" => "false");
                        }

                        $msg = json_encode($sendData);
                        $this->send($member[$i], $msg);
                    }
                }

            }else{

                if(Tagiron::shareCard($data['id'])){

                    for ($j=0; $j < $memberNum; $j++) {
                        # code...
                        if($member[$i] != $member[$j]){
                            $anser = $this->tagironData->question($data['roomId'], $member[$j], $data['id'], $data["qsValue"]);

                            $sendData = [
                                "type" => "question",
                                "qID" => $data['id'],
                                "anser" => $anser,
                                "field" => $newField
                            ];
                            
                            if($member[$i] == $nextPlayer){
                                $sendData += array("turn" => "true");
                            }else{
                                $sendData += array("turn" => "false");
                            }

                            $msg = json_encode($sendData);
                            $this->send($member[$i], $msg);
                        }

                        
                    }

                }else{
                    $sendData = [
                        "type" => "newField",
                        "field" => $newField
                    ];

                    if($member[$i] == $nextPlayer){
                        $sendData += array("turn" => "true");
                    }else{
                        $sendData += array("turn" => "false");
                    }

                    $msg = json_encode($sendData);
                    $this->send($member[$i], $msg);
                }

            }
        }

    }

    //$oppに対してメッセージ送信
    private function send(ConnectionInterface $opp, $msg) {
        $opp->send($msg);
    }

    //接続が閉じられた
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";

        $key = $this->room->getKey($conn);
        if($key != NULL) {
            $member = $this->room->getOpponent($key);
            if($member != NULL){
                $sendData = [
                    "type" => "close",
                ];
                $sendjson = json_encode($sendData);
                for($i = 0; $i < count($member); $i++) {
                    $this->send($member[$i], $sendjson);
                }
            }
            $this->tagironData->remove($key);
            
        }
        $this->room->remove($key);
        
    }

    //接続エラー
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

?>