<?php
    $CN=mysqli_connect("localhost", "pduser", "password");
    $DB=mysqli_select_db($CN, "pd_database");

    // Check connection
    if (mysqli_connect_errno()){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $EncodedData = file_get_contents('php://input');
    $DecodedData = json_decode($EncodedData, true);

    $ip_address = $DecodedData['ip_address'];

    $search_query = "SELECT * FROM raspberrypi WHERE ip_address LIKE '$ip_address'";

    $R = mysqli_query($CN, $search_query);
    $row = mysqli_fetch_array($R);

    if(is_null($row)){
        $Message = "No $ip_address IP address registered";
        $Data = $row;
        $Response[]=array("Message"=>$Message, "Data"=>$Data);
        echo json_encode($Response);
    }
    else{
        $Data = $row;
        $Message = "$ip_address IP address registered";
        $Response[]=array("Message"=>$Message, "Data"=>$Data);
        echo json_encode($Response);
    }

    mysqli_close($CN);
?>