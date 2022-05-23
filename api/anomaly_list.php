<?php
    $CN=mysqli_connect("localhost", "pduser", "password");
    $DB=mysqli_select_db($CN, "pd_database");

    // Check connection
    if (mysqli_connect_errno()){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $EncodedData = file_get_contents('php://input');
    $DecodedData = json_decode($EncodedData, true);
    
    $rpi_id = $DecodedData['rpi_id'];
    $Message = "";

    $anomaly_list_query = "SELECT * from pc_table where rpi_id LIKE '$rpi_id' AND status LIKE 'Anomaly' ORDER BY datetime DESC";

    $anomaly_R = mysqli_query($CN, $anomaly_list_query);
    $anomaly_row = mysqli_fetch_all($anomaly_R, MYSQLI_ASSOC);

    #$Message = "PC DATA";
    
    $Response[]=array("Message"=>$Message,"Anomaly"=>$anomaly_row);
    echo json_encode($Response);
    #echo($PC_row);

    mysqli_close($CN);
?>
