<?php
    require("scr_document.php");
    $data = document_server_data_read();
    $detail = $data->name . "<br>" . $data->address . "<br>โทร. " . $data->phone;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดต่อเรา</title>
</head>
<body>
    <link rel="stylesheet" href="body.css">
    <link rel="stylesheet" href="bodyhome.css">
    <!--navbar-->
    <script src="include-html.js"></script>
    <script src="navbar.js""></script>
    <p include-html="navbar.html"></p>
    <script>includeHTML();</script>
    <!--navbar-->
    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">
        <div style="padding:16px">
    <!--END-->
    
    <!--header-->
    <img src="header_contact.png" style="width:100%">   
    <!--END-->

    <!--ส่วนของรายละเอียด-->
    <div class="container" align ="center">
        <div class="contentbox">
            <div class="pricing-block-title">
                    <h3>รายละเอียดการติดต่อ</h3>
                    <p>
                        <?php echo $detail;?>
                    </p>
            </div>
        </div>
    </div>
    <!--END-->
        
    <!-- ย้อนกลับ Button -->
    <div class="pricing-block-button">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
            <div class="w3-container">
                <p><button class="w3-button w3-green w3-round" onclick="link('index.html')">ย้อนกลับ</button></p><!--ใส่ลิ้งตรงนี้-->
            </div> 
    </div>
    <!--END-->

    <!-- footer -->
    <footer>
        <p align ="center" style="font-size: medium" >copyright ©2020 Keng Kajorn</p>     
    </footer>
    <!--END-->
<script>
function link(url){
    window.open(url,'_self')
}
</script>
</body>
</html>
