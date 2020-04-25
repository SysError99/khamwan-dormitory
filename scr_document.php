<?php
    require("scr_baht.php");
    
    function document_header(){
        return "<head>
    <meta charset=\"utf-8\" name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>เอกสารที่ออกโดยระบบ</title>
    <style>
    table.main {
        border-collapse: collapse;
        width: 100%;
    }

    table.main th, td {
        padding: 8px;
    }

    table.main tr:nth-child(even){background-color: #f2f2f2}

    table.main th {
        background-color: #4CAF50;
        color: white;
    }
    </style>
</head>
    <body>
        <p style=\"height:20px;\"></p>";
    }
    
    //Tax Invoice
    function document_title_tax_invoice(){
        return "<h1 align=\"center\"> ใบกำกับภาษี</h1>";
    }
    
    function document_footer_tax_invoice(){
        return "<p style=\"height:100px\"></p>
        <table class=\"main\">
            <tr>
                <td>
                    <p style=\"text-align:center\">ลงชื่อ............................................ผู้จ่าย</p>
                </td>
                <td>
                    <p style=\"text-align:center\">ลงชื่อ............................................ผู้รับเงิน</p>
                </td>
            </tr>
        </table>";
    }
    
    //Invoice
    function document_title_invoice(){
        return "<h1 align=\"center\"> ใบแจ้งหนี้</h1>";
    }
    
    function document_footer_invoice(){
        return "<p style=\"height:100px\"></p>
        <table class=\"main\">
            <tr>
                <td style=\"width:50%\">
                </td>
                <td style=\"width:50%\">
                    <p style=\"text-align:center\">ลงชื่อ............................................ผู้แจ้งหนี้</p>
                </td>
            </tr>
        </table>";
    }
    
    //Info
    function document_info($document_no, $document_type, $document_companyname,$document_companyaddress,$document_companyphone,$document_companytaxid,$document_customername,$document_customeraddress,$document_printdate){
        return "<table align=\"center\" class=\"\" style=\"width:100%;\">
            <tr>
                <th style=\"width:50%;\"></th>
                <th style=\"width:50%;\"><p align=\"center\" style=\"font-weight:bold;\">".$document_no."</p></th>
            </tr>
            <tr>
                <td style=\"width=100%;height:10px;\"></td>
            </tr>
            <tr>
                <td style=\"width=70%;\">".$document_companyname."<br/>".$document_companyaddress."<br/>โทร. ".$document_companyphone."<br/><br/></td>
                <td style=\"width=30%;\"><p align=\"center\"><br/><br/>".$document_type."<br/>เลขประจำตัวผู้เสียภาษี<br/>".$document_companytaxid."</p></td>
            </tr>
            <tr>
                <td style=\"width=100%;height:25px;\"></td>
            </tr>
            <tr>
                <td style=\"width:70%\"><t style=\"font-weight:bold;\">ชื่อผู้เช่า  </t>".$document_customername."<br/><t style=\"font-weight:bold;\">ที่อยู่  </t>".$document_customeraddress."</td>
                <td style=\"width=30%\"><p align=\"center\"><t style=\"font-weight:bold;\">วันที่  </t>".document_get_date($document_printdate)."</td>
            </tr>
        </table>";
    }
    
    //Table
    function document_table($document_room,$document_water,$document_waterp,$document_nrg,$document_nrgp,$document_price){
        //summarise
        $document_total_water = doubleval($document_water) * doubleval($document_waterp);
        $document_total_nrg = doubleval($document_nrg) * doubleval($document_nrgp);
        $document_total_price = doubleval($document_price) + $document_total_water + $document_total_nrg;
        $document_total_price_str = number_format($document_total_price, 2, '.', '');
        return "<p style=\"height:5px\"></p>
        <table class=\"main\">
            <tr style=\"text-align:center\">
                <th style=\"width:10%\">ลำดับ</th>
                <th style=\"width:30%\">รายการ</th>
                <th style=\"width:20%\">จำนวน</th>
                <th style=\"width:20%\">ราคาต่อหน่วย</th>
                <th style=\"width:20%\">จำนวนเงิน</th>
            </tr>
            <tr>
                <td style=\"width:10%;text-align:center;\">1</td>
                <td style=\"width:30%\">ค่าเช่าหอพัก ห้อง ".$document_room."</td>
                <td style=\"width:20%;text-align:center;\">1</td>
                <td style=\"width:20%;text-align:right;\">".$document_price."</td>
                <td style=\"width:20%;text-align:right;\">".$document_price."</td>
            </tr>
            <tr>
                <td style=\"width:10%;text-align:center;\">2</td>
                <td style=\"width:30%\">ค่าน้ำ</td>
                <td style=\"width:20%;text-align:center;\">".$document_water."</td>
                <td style=\"width:20%;text-align:right;\">".$document_waterp."</td>
                <td style=\"width:20%;text-align:right;\">".strval($document_total_water)."</td>
            </tr>
            <tr>
                <td style=\"width:10%;text-align:center;\">3</td>
                <td style=\"width:30%\">ค่าไฟ</td>
                <td style=\"width:20%;text-align:center;\">".$document_nrg."</td>
                <td style=\"width:20%;text-align:right;\">".$document_nrgp."</td>
                <td style=\"width:20%;text-align:right;\">".strval($document_total_nrg)."</td>
            </tr>

        </table>
        <table class=\"main\">
            <tr>
                <td style=\"width:50%;text-align:center;background-color:white;\">จำนวนเงินรวมทั้งสิ้น (ตัวอักษร)</td>
                <td style=\"width:30%;text-align:right;\">รวมภาษีมูลค่าเพิ่มแล้ว</td>
                <td style=\"width:20%;text-align:right;\">".$document_total_price_str."</td>
            </tr>
            <tr>
                <td style=\"width:50%;text-align:center;background-color:white;\">".baht_getstr($document_total_price_str)."</td>
                <td style=\"width:30%;text-align:right;\">จำนวนเงินรวมทั้งสิ้น</td>
                <td style=\"width:20%;text-align:right;\">".$document_total_price_str."</td>
            </tr>
        </table>";
    }
    
    //Footer (End of HTML)
    function document_footer(){
        return "</body></html>";
    }
    
    //Additional Functions
    function document_get_date($document_input){
        $document_result = substr($document_input,4,2);
        switch($document_result){
            case "01":
                $document_result="มกราคม";
                break;
            case "02":
                $document_result="กุมภาพันธ์";
                break;
            case "03":
                $document_result="มีนาคม";
                break;
            case "04":
                $document_result="เมษายน";
                break;
            case "05":
                $document_result="พฤษภาคม";
                break;
            case "06":
                $document_result="มิถุนายน";
                break;
            case "07":
                $document_result="กรกฎาคม";
                break;
            case "08":
                $document_result="สิงหาคม";
                break;
            case "09":
                $document_result="กันยายน";
                break;
            case "10":
                $document_result="ตุลาคม";
                break;
            case "11":
                $document_result="พฤศจิกายน";
                break;
            case "12":
                $document_result="ธันวาคม";
                break;
            default:
                $document_result="?";
                break;
        }
        $document_byear = strval(intval(substr($document_input,0,4))+543);
        $document_result = substr($document_input,6,2)."  ".$document_result."  ".$document_byear;
        if(substr($document_result,0,1)==="0"){
            $document_result = substr_replace($document_result, " ",0,1);
        }
        return $document_result;
    }
    
    function document_server_data_read(){
        $document_data = false;
        //get metadata
        $document_file_read = fopen("server.json","r");
        if(!$document_file_read){}
        else{
            $document_json = fread($document_file_read,filesize("server.json"));
            $document_data = json_decode($document_json);
        }
        return $document_data;
    }
    function document_server_data_write($document_data){
        $document_json = json_encode($document_data);
        $document_file_write = fopen(__DIR__."/server.json", "w");
        if(!$document_file_write){
            return false;
        }
        else{
            fwrite($document_file_write, $document_json);
            fclose($document_file_write);
            return true;
        }
    }
    
    function document_print(){
        return "<script>window.print()</script>";
    }
?>
