<?php
    for($i = 40; $i <= 50; $i++){
        if($i % 7 == 0){
            echo "The number is divisible by 7" . "<br>";
            continue;
        }else{
            echo $i . "<br>";
        }
    }
?>