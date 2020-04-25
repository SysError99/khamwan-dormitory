<?php
    require("scr_sql.php");
    $message = "";
    //room listing
    $room = array();
    $room_count = 0;
    $query = mysqli_query($sql,"SELECT * FROM tb_room");
    if($query){
        $room_count = mysqli_num_rows($query);
        for($i=0;$i<$room_count;$i++){
            $result = mysqli_fetch_array($query);
            $room[$i] = $result['room_id'];
        }
    }
    else{
        $message = "เกิดข้อผิดพลาดระหว่างตรวจสอบข้อมูล โปรดลองอีกครั้ง";
    }
    //reserved rooms
    $reserved_room = array();
    $query = mysqli_query($sql, "SELECT * from tb_reserve");
    if($query){
        $reserved_room_count = mysqli_num_rows($query);
        for($i=0;$i<$reserved_room_count;$i++){
            $result = mysqli_fetch_array($query);
            $reserved_room[$i] = $result['room'];
        }
    }
    else{
        $message = "เกิดข้อผิดพลาดระหว่างตรวจสอบข้อมูลการจอง โปรดลองอีกครั้ง";
    }
    mysqli_close($sql);
    //message
    if(!empty($message)){
        echo "<script>alert('".$message->toString()."')</script>";
    }
    else{
        //echo implode("|",$room) . "<br>" . implode("|",$reserved_room) . "<br>";
        //search for reserved rooms
        for($i=0;$i<$room_count;$i++){
            $message.= "<tr>";
            if(array_search($room[$i],$reserved_room)===false){
                $message.= "<td><input type=\"radio\" name=\"room\" value=\"".$room[$i]."\">";
                $message.= "<span class=\"checkmark\"></span> ".$room[$i]."</td>";
                
            }
            else{
                $message.= "<td><span class=\"checkmark\"></span> ".$room[$i]." (จองแล้ว)</td>";
            }
            $message.= "</tr>";
        }
    }
?>
<html>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0"> 
<head>
<title>Reservation</title>
</head>
<body>
<link rel="stylesheet" href="body.css">
<!--link rel="stylesheet" href="body-reserve.css">
<!--navbar-->
<script src="include-html.js"></script>
<script src="navbar.js""></script>
<p include-html="navbar.html"></p>
<script>includeHTML()</script>
<!--navbar-->
<img src="header_reserve.png" style="width:100%">   
<form action="scr_reserve.php?add" name="formReserve" method="post" enctype="multipart/form-data">
    <!--เลือกห้อง-->
    <div class="container" >
    <h1 align="center">- เลือกห้องที่ต้องการจอง -</h1>
    <table align ="center">
        <tr>
            <th><span class="checkmark"></span>รายการห้องพักทั้งหมด</th>
        </tr>
        <?php echo $message;?>
    </table>
    </div>
    <!--รายเดือน รายเทอม-->
    <h1 align="center"> - เลือกประเภทห้อง - </h1>
    <div align="center">
        <tr>
            <td><input type="radio" name="type" value="รายเดือน">
                <span class="checkmark"></span>รายเดือน</td>
            <td><input type="radio" name="type" value="รายเทอม">
                <span class="checkmark"></span>รายเทอม</td>
        </tr>
    </div>
    <!--อื่นๆ-->
    <h1 align="center">- ข้อมูลที่จำเป็น -</h1>
    <table style="width:50%" align="center">
        <tr>
            <td>ชื่อผู้จอง:</td><td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>เบอร์โทรศัพท์:</td><td><input type="text" name="phone"><br></td>
        </tr>
        <tr>
            <td>ภาพสลิปการโอนเงิน:</td>
            <td><input style="width:100%" type="file" name="form_file" id="form_file"></td>
        </tr>
    </table>
    <input type="submit" value="ยืนยันการจอง">
</form>
</body>
</html>
