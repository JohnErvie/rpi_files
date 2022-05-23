<?php
    $CN=mysqli_connect("localhost", "pduser", "password");
    $DB=mysqli_select_db($CN, "pd_database");

    // Check connection
    if (mysqli_connect_errno()){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $EncodedData = file_get_contents('php://input');
    $DecodedData = json_decode($EncodedData, true);

    $sensor_no = $DecodedData['sensor_no'];
    $time = $DecodedData['time'];
    $rpi_id = $DecodedData['rpi_id'];
    $Message = "";

    if ($rpi_id != 0){
      if ($time == "minute(datetime)"){
        $power_consumption_query = "SELECT hour(datetime),minute(datetime),sum(power_consumption),sum(power_consumption_anomaly_score),datetime from pc_table where rpi_id LIKE '$rpi_id' and day(datetime) LIKE day(CURRENT_DATE()) group by hour(datetime),minute(datetime) ORDER BY time DESC LIMIT 10;";
      }
      elseif ($time == "hour(datetime)"){
        $power_consumption_query = "SELECT hour(datetime),sum(power_consumption),sum(power_consumption_anomaly_score),datetime from pc_table where rpi_id LIKE '$rpi_id' and day(datetime) LIKE day(CURRENT_DATE()) group by hour(datetime) ORDER BY time DESC LIMIT 10;";
      }
      elseif ($time == "month(datetime)"){
        $power_consumption_query = "SELECT month(datetime),sum(power_consumption),sum(power_consumption_anomaly_score),datetime from pc_table where rpi_id LIKE '$rpi_id' and month(datetime) LIKE month(CURRENT_DATE()) group by month(datetime) ORDER BY time DESC LIMIT 10;";
      }
    }
    else{
      if ($time == "minute(datetime)"){
        $power_consumption_query = "SELECT hour(datetime),minute(datetime),sum(power_consumption),sum(power_consumption_anomaly_score),datetime from pc_table where sensor LIKE '$sensor_no' and day(datetime) LIKE day(CURRENT_DATE()) group by hour(datetime),minute(datetime) ORDER BY time DESC LIMIT 10;";
        $Message = "PC DATA";
      }
      elseif ($time == "hour(datetime)"){
        $power_consumption_query = "SELECT hour(datetime),sum(power_consumption),sum(power_consumption_anomaly_score),datetime from pc_table where sensor LIKE '$sensor_no' and day(datetime) LIKE day(CURRENT_DATE()) group by hour(datetime) ORDER BY time DESC LIMIT 10;";
      }
      elseif ($time == "month(datetime)"){
        $power_consumption_query = "SELECT month(datetime),sum(power_consumption),sum(power_consumption_anomaly_score),datetime from pc_table where sensor LIKE '$sensor_no' and month(datetime) LIKE month(CURRENT_DATE()) group by month(datetime) ORDER BY time DESC LIMIT 10;";
      }
    }

    $PC_R = mysqli_query($CN, $power_consumption_query);
    $PC_row = mysqli_fetch_all($PC_R, MYSQLI_ASSOC);

    #$Message = "PC DATA";
    
    $Response[]=array("Message"=>$Message,"power_consumption"=>$PC_row);
    echo json_encode($Response);
    #echo($PC_row);

    mysqli_close($CN);
?>
