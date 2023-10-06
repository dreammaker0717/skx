<?php
include PATH_CONFIG . "/constants.php";

$db = M::db();

if ($action == "get_messages") {
    $messagesSQL = "SELECT * FROM skualerts_messages";
    $messagesResult = $db->query($messagesSQL);
    if ($messagesResult) {
        $messages = $messagesResult->fetchAll();
        if ($messages) {
            $index = 0;
            $result = array();
            foreach ($messages as $message) {
                $newArr = ['id' => $message['id'], 'message' => $message['message']];
                $result[$index] = $newArr;
                $index++;
            }
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode(utf8ize($result));
        } else {
            exit();
        }
    } else {
        exit();
    }
} else if ($action == "delete_message") {
    $deleteID = intval($_POST["messageID"]);
    $db->delete("skualerts_messages", ["id" => $deleteID]);
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
} else if ($action == "add_message" || $action == "update_message") {
    $message = $_POST["message"];

    $dataMessage = array(
        'message' => $message,
    );

    if ($action == "add_message") {
        $db->insert('skualerts_messages', $dataMessage);
    } else if ($action == "update_message") {
        $db->update('skualerts_messages', $dataMessage, ['id' => $_POST["messageID"]]);
    }

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
}

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}
