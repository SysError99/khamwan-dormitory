<?php
    require("scr_document.php");
    $data = document_server_data_read();
    $message = "";
    if(isset($_GET['config'])){
        session_start();
        if(isset($_SESSION['mode'])){//logged in?
            if($_SESSION['mode']===2){//admin account?
                if($_SESSION['lvl']===1){
                    $data->taxid = $_POST['taxid'];
                    $data->name = $_POST['name'];
                    $data->address = $_POST['addr'];
                    $data->phone = $_POST['phone'];
                    document_server_data_write($data);
                    $message = "บันทึกการตั้งค่าแล้ว";
                }
            }
        }
    }
?>
<html>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<title>ระบบจัดการหอพัก</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="body.css">
<!--navbar-->
<script src="include-html.js"></script>
<script src="navbar.js""></script>
<p include-html="navbar.html"></p>
<script>includeHTML();</script>
<!--navbar-->
<div class="header"><h1>ข้อมูลหอพัก</h1></div>
<form action="server.php?config" name="formServer" method="post">
    <table align="center">
        <tr>
            <td>เลขที่ผู้เสียภาษี: </td><td><input type="text" name="taxid" value=<?php echo "\"".$data->taxid."\""; ?>><br></td>
        </tr>
        <tr>
            <td>ชื่อหอพัก: </td><td><input type="text" name="name" value=<?php echo "\"".$data->name."\""; ?>><br></td>
        </tr>
        <tr>
            <td>ที่อยู่ของหอพัก: </td><td><input type="text" name="addr" value=<?php echo "\"".$data->address."\""; ?>><br></td>
        </tr>
        <tr>
            <td>โทรศัพท์: </td><td><input type="text" name="phone" value=<?php echo "\"".$data->phone."\""; ?>><br></td>
        </tr>
        <tr><td> </td><td> </td></tr>
        <tr>
            <td></td><td><input type="submit" value="บันทึกข้อมูล"></td>
        </tr>
    </table>
</form>
<div style="text-align:center;">
    <button class="button button-red" onclick="gotolink('admin_ui.html')">ย้อนกลับ</button></p>
</div>
<script>
var message = <?php echo "'".$message."'\n";?>
if(message!=''){
    alert(message)
} 
function gotolink(link){
    window.open(link,'_self')
}
</script>
<!--auth.js-->
<p id="page" type="admin"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
</body>
</html>
