<?php 
 $str = "5";
 $str2 = "df";
 $bool = true;
 $num = 10;
 $result1 = $str + $num; // 15
 $result2 = $bool + $num; // 11
 echo $result1;
 echo "<br>";
 echo $result2;
 $site = 'SENG412';
 echo "welcome to \n" . $site . "\n"; 
 echo "welcome to $site <br>"; 
 echo 'You will learn PHP';

 $input = <<<testHereDoc
 This is a test here document.
 I am not enjoying this
 testHereDoc;
 echo $input;
 echo "<br>";
 echo decbin(10);
 echo "<br>";
 echo abs(-10);
 echo "<br>";
 echo abs(10);
 echo "<br>";
 echo abs(-10.3);
 echo "<br>";
 echo abs(10.3);
 echo "<br>";
 ?>
