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
    $sensor1 = $DecodedData['sensor1'];
    $sensor2 = $DecodedData['sensor2'];
    $sensor3 = $DecodedData['sensor3'];
    $sensor4 = $DecodedData['sensor4'];

    $update_query = "UPDATE raspberrypi SET sensor1='$sensor1', sensor2='$sensor2', sensor3='$sensor3', sensor4='$sensor4' WHERE rpi_id LIKE '$rpi_id'";
    mysqli_query($CN, $update_query);

    $search_query = "SELECT sensor1, sensor2, sensor3, sensor4 FROM raspberrypi WHERE rpi_id LIKE '$rpi_id'";

    $R = mysqli_query($CN, $search_query);
    $row = mysqli_fetch_array($R);

    $Data = $row;
    $Message = "Sensor names Added";
    $Response[]=array("Message"=>$Message, "Data"=>$Data);
    echo json_encode($Response);

    mysqli_close($CN);
?>
