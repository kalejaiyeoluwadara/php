<?php
    const CTITLE = "Hi there";
    echo (CTITLE);
    echo PHP_VERSION . "<br>";

    define("SENG412","Internet Technology");
    function constant_function(){
        echo SENG412;        
    }
    echo SENG412 . "<br>";
    constant_function();
?>