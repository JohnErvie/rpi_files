<?php
    $CN=mysqli_connect("localhost", "pduser", "password");
    $DB=mysqli_select_db($CN, "pd_database");

    // Check connection
    if (mysqli_connect_errno()){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $EncodedData = file_get_contents('php://input');
    $DecodedData = json_decode($EncodedData, true);

    $time = $DecodedData['time'];
    $sensor = $DecodedData['sensor'];
    $Message = "";

    if($time == "day"){
      $date = "hour(datetime)";
    }
    elseif($time == "week"){
      $date = "day(datetime)";
    }
    elseif($time == "month"){
      $date = "week(datetime)";
    }

    $usage_query = "SELECT $time(datetime),sum(power_consumption),$date, datetime, sum(power_consumption_anomaly_score) from pc_table where sensor LIKE '$sensor' and $time(date) LIKE $time(CURRENT_DATE()) group by $date ORDER BY datetime DESC;";

    $usage_R = mysqli_query($CN, $usage_query);
    $usage_row = mysqli_fetch_all($usage_R, MYSQLI_ASSOC);

    #$Message = "PC DATA";
    
    $Response[]=array("Message"=>$Message,"Usage"=>$usage_row);
    echo json_encode($Response);
    #echo($PC_row);

    mysqli_close($CN);
?>
