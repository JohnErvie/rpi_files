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
    $rpi_id = $DecodedData['rpi_id'];
    $Message = "";

    $usage_query = "SELECT $time(datetime),sensor,sum(power_consumption),sname from pc_table where rpi_id LIKE '$rpi_id' and $time(date) LIKE $time(CURRENT_DATE()) group by '$time',sensor ORDER BY sum(power_consumption) DESC;";

    $usage_R = mysqli_query($CN, $usage_query);
    $usage_row = mysqli_fetch_all($usage_R, MYSQLI_ASSOC);

    $sum_query = "SELECT $time(datetime),sum(power_consumption) from pc_table where rpi_id LIKE '$rpi_id' and $time(date) LIKE $time(CURRENT_DATE()) group by '$time';";

    $sum_R = mysqli_query($CN, $sum_query);
    $sum_row = mysqli_fetch_all($sum_R, MYSQLI_ASSOC);

    #$Message = "PC DATA";
    
    $Response[]=array("Message"=>$Message,"Usage"=>$usage_row,"Summary"=>$sum_row );
    echo json_encode($Response);
    #echo($PC_row);

    mysqli_close($CN);
?>
