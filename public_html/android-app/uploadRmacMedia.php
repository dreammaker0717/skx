<?php
$servername = "127.0.0.1";
$username = "skx_farhad";
$password = "ferrari488P";
$dbname = "skx_ndc3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}    

$rmac_id = intval($_POST["id"]);
if( !empty( $_FILES ) ) {
    foreach( $_FILES[ 'image' ][ 'tmp_name' ] as $index => $tmpName ){
        if( !empty( $_FILES[ 'image' ][ 'error' ][ $index ] ) ){                
            return false;
        }
        $tmpName = $_FILES[ 'image' ][ 'tmp_name' ][ $index ];                            
        if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) ){
            $someDestinationPath = "/uploads/".time()."_".$_FILES[ 'image' ][ 'name' ][ $index ];
            $conn->query("update rmac_items set rmac_images=CONCAT(rmac_images, '" . $someDestinationPath . ",') where rmac_ID = " . $rmac_id);
            move_uploaded_file( $tmpName, $_SERVER['DOCUMENT_ROOT'] . $someDestinationPath );
        }
    }

    foreach( $_FILES[ 'video' ][ 'tmp_name' ] as $index => $tmpName ){
        if( !empty( $_FILES[ 'video' ][ 'error' ][ $index ] ) ){                
            return false;
        }
        $tmpName = $_FILES[ 'video' ][ 'tmp_name' ][ $index ];                            
        if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) ){
            $someDestinationPath = "/uploads/".time()."_".$_FILES[ 'video' ][ 'name' ][ $index ];
            $conn->query("update rmac_items set rmac_videos=CONCAT(rmac_videos, '" . $someDestinationPath . ",') where rmac_ID = " . $rmac_id);
            move_uploaded_file( $tmpName, $_SERVER['DOCUMENT_ROOT'] . $someDestinationPath );
        }
    }
}

echo json_encode('{ "success" : true}');

$conn->close();
?>
