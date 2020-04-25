<?php
    session_start();
    $message="<script>";
    
    //logout
    if(isset($_GET['logout'])){
        session_destroy();
        $message.= "alert('ออกจากระบบแล้ว');window.open('login.html','_self');";
    }
    //admin check
    else if(isset($_GET['admin'])){
        if(!isset($_SESSION['mode'])){
            $message.= "alert('คุณยังไม่ได้ล็อกอิน โปรดล็อกอินก่อนเข้าใช้งาน');window.name='invalid';";
        }
        //user login
        else if($_SESSION['mode'] < 2){
            $message.= "alert('คุณไม่ได้รับอนุญาตให้เข้าถึงส่วนนี้');window.name='invalid';";
        }
    }
    else if(isset($_GET['room'])){
        if(!isset($_SESSION['mode'])){
            $message.= "alert('คุณยังไม่ได้ล็อกอิน โปรดล็อกอินก่อนเข้าใช้งาน');window.name='invalid';";
        }
        //user login
        else if($_SESSION['mode'] < 1){
            $message.= "alert('คุณไม่ได้รับอนุญาตให้เข้าถึงส่วนนี้');window.name='invalid';";
        }
    }
    //redirect
    else if(!isset($_SESSION['mode'])){
        $message .= "window.open('login.html','_self')";
    }
    else if($_SESSION['mode']===1){
        $message .= "window.open('room_ui.php','_self')";
    }
    else if($_SESSION['mode']===2){
        $message .= "window.open('admin_ui.html','_self')";
    }
    else{
        $message .= "window.open('login.html','_self')";
    }
    
    $message .= "</script>";
    echo $message;
?>
