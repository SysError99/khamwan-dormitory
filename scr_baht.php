<?php
/*
    Code Thai BathText
    By Code-Father.com
    Fixed by SysError99 (SysError_)
*/
function baht_getstr($baht_input_number){
    $baht_digit=array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
    $baht_digit2=array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
    $baht_explode_number = explode(".",$baht_input_number);
    $baht_num0=$baht_explode_number[0]; // เลขจำนวนเต็ม
    $baht_len_number=strlen($baht_num0); // นับจำนวนตัวเลข ว่ามากว่า 7 หรือไม่
    $baht_num=substr($baht_num0,0,$baht_len_number-6); // ตัดเอาส่วนที่เกิน 7 หลัก พันล้าน ร้อยล้าน
    $baht_num0=substr($baht_num0,$baht_len_number-6,$baht_len_number); // ตัดเอา 7 หลัก
    if($baht_num!=''){
        // เลขจำนวนเต็ม ที่เกิน 7 หลัก (หลักพันล้าน ร้อยล้าน)
        $baht_didit2_chk=strlen($baht_num)-1;
        for($baht_i=0;$baht_i<=strlen($baht_num)-1;$baht_i++){
            $baht_cut_input_number=substr($baht_num,$baht_i,1);
            if($baht_cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
                //$baht_bahttext1.=''."".$baht_digit2[$baht_didit2_chk]; 
            }elseif($baht_cut_input_number==2 && $baht_didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
                $baht_bahttext1.='ยี่'."".$baht_digit2[$baht_didit2_chk]; 
            }elseif($baht_cut_input_number==1 && $baht_didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
                //$baht_bahttext1.= ''."".$baht_digit2[$baht_didit2_chk];    
            }elseif($baht_cut_input_number==1 && $baht_didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
                if(substr($baht_num0,$baht_i-1,1)==0){
                    $baht_bahttext1.= 'หนึ่ง'."".$baht_digit2[$baht_didit2_chk];
                }else{
                    $baht_bahttext1.= 'เอ็ด'."".$baht_digit2[$baht_didit2_chk];
                }    
                        
            }else{
                $baht_bahttext1.= $baht_digit[$baht_cut_input_number]."".$baht_digit2[$baht_didit2_chk];
            }
            $baht_didit2_chk=$baht_didit2_chk-1;
        }
        $baht_bahttext1.='ล้าน ';
    }
    // เลขจำนวนเต็ม 
    $baht_bahttext1 = "";//initialized by SysError99 (SysError_)
    $baht_didit2_chk=strlen($baht_num0)-1;
    for($baht_i=0;$baht_i<=strlen($baht_num0)-1;$baht_i++){
        $baht_cut_input_number=substr($baht_num0,$baht_i,1);
        if($baht_cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
            //$baht_bahttext1.=''."".$baht_digit2[$baht_didit2_chk]; 
        }elseif($baht_cut_input_number==2 && $baht_didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
            $baht_bahttext1.='ยี่'."".$baht_digit2[$baht_didit2_chk]; 
        }elseif($baht_cut_input_number==1 && $baht_didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
            //$baht_bahttext1.= ''."".$baht_digit2[$baht_didit2_chk];    
        }elseif($baht_cut_input_number==1 && $baht_didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
            if(substr($baht_num0,$baht_i-1,1)==0){
                $baht_bahttext1.= 'หนึ่ง'."".$baht_digit2[$baht_didit2_chk];
            }else{
                $baht_bahttext1.= 'เอ็ด'."".$baht_digit2[$baht_didit2_chk];
            }    
                    
        }else{
            $baht_bahttext1.= $baht_digit[$baht_cut_input_number]."".$baht_digit2[$baht_didit2_chk];
        }
            $baht_didit2_chk=$baht_didit2_chk-1;
    }
    $baht_bahttext1.='บาท';
    // เลขทศนิยม
    if(strpos($baht_input_number, ".")!==false){//is there any decimals
        $baht_num1=$baht_explode_number[1]; // หลักทศนิยม
        if(strpos($baht_input_number, ".00")===false){ //not a zero!
            $baht_didit2_chk=strlen($baht_num1)-1;
            for($baht_i=0;$baht_i<=strlen($baht_num1)-1;$baht_i++){
                $baht_cut_input_number=substr($baht_num1,$baht_i,1);
                
                if($baht_cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
                
                }elseif($baht_cut_input_number==2 && $baht_didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
                    $baht_bahttext1.='ยี่'."".$baht_digit2[$baht_didit2_chk]; 
                }elseif($baht_cut_input_number==1 && $baht_didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
                    $baht_bahttext1.= ''."".$baht_digit2[$baht_didit2_chk];
                }elseif($baht_cut_input_number==1 && $baht_didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
                    if(substr($baht_num1,$baht_i-1,1)==0){
                        $baht_bahttext1.= 'หนึ่ง'."".$baht_digit2[$baht_didit2_chk];
                    }else{
                        $baht_bahttext1.= 'เอ็ด'."".$baht_digit2[$baht_didit2_chk];
                    }    
                }else{
                    $baht_bahttext1.= $baht_digit[$baht_cut_input_number]."".$baht_digit2[$baht_didit2_chk];
                }
                $baht_didit2_chk=$baht_didit2_chk-1;
            }
            $baht_bahttext1.='สตางค์';
        }
        else{
            $baht_bahttext1.='ถ้วน';
        }
    }
    else{
        $baht_bahttext1.='ถ้วน';
    }
    return $baht_bahttext1;
}
?>
