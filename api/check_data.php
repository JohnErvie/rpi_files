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
    $Message = array();
    $Active = "Active";
    $notActive = "NotActive";

    $s1_query = "SELECT COUNT(sensor) AS 'Sensor1' from pc_table where rpi_id LIKE '$rpi_id' and sensor LIKE 'Sensor 1'";

    $s1_R = mysqli_query($CN, $s1_query);
    $s1_row = mysqli_fetch_array($s1_R);

    if((int)$s1_row > 0){
        $Message["Sensor1"] = $Active;
        //array_push($Message,"Sensor1"=>"Active");
    }
    else{
        $Message["Sensor1"] = $Active;
        //array_push($Message,"Sensor1"=>"NotActive");
    }

    $s2_query = "SELECT COUNT(sensor) AS 'Sensor2' from pc_table where rpi_id LIKE '$rpi_id' and sensor LIKE 'Sensor 2'";

    $s2_R = mysqli_query($CN, $s2_query);
    $s2_row = mysqli_fetch_array($s2_R);

    if((int)$s2_row > 0){
        $Message["Sensor2"] = $Active;
        //array_push($Message,"Sensor2"=>"Active");
    }
    else{
        $Message["Sensor2"] = $notActive;
        //array_push($Message,"Sensor2"=>"NotActive");
    }

    $s3_query = "SELECT COUNT(sensor) AS 'Sensor3' from pc_table where rpi_id LIKE '$rpi_id' and sensor LIKE 'Sensor 3'";

    $s3_R = mysqli_query($CN, $s3_query);
    $s3_row = mysqli_fetch_array($s3_R);

    if((int)$s3_row > 0){
        $Message["Sensor3"] = $notActive;
        //array_push($Message,"Sensor3"=>"Active");
    }
    else{
        $Message["Sensor3"] = $Active;
        //array_push($Message,"Sensor3"=>"NotActive");
    }

    $s4_query = "SELECT COUNT(sensor) AS 'Sensor4' from pc_table where rpi_id LIKE '$rpi_id' and sensor LIKE 'Sensor 4'";

    $s4_R = mysqli_query($CN, $s4_query);
    $s4_row = mysqli_fetch_array($s4_R);

    if((int)$s4_row > 0){
        $Message["Sensor4"] = $Active;
        //array_push($Message,"Sensor4"=>"Active");
    }
    else{
        $Message["Sensor4"] = $notActive;
        //array_push($Message,"Sensor4"=>"NotActive");
    }

    //$Sensors = array("Sensor1"=>$s1_row, "Sensor2"=>$s2_row, "Sensor3"=>$s3_row, "Sensor4"=>$s4_row);

    $Response[]=array("Message"=>$Message,"Sensor1"=>$s1_row["Sensor1"], "Sensor2"=>$s2_row["Sensor2"], "Sensor3"=>$s3_row["Sensor3"], "Sensor4"=>$s4_row["Sensor4"]);
    echo json_encode($Response);
    #echo($PC_row);

    mysqli_close($CN);
?>
