<?php
    function displayMessage(){
        return "Hello World!";
    }
    echo displayMessage();
    die("End of the function");
    echo "This will not be displayed";

?>