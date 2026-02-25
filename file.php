<?php 
  echo "The line number is: " . __LINE__ . "<br>";
  echo "The file name is: " . __FILE__ . "<br>";
  echo "The file directory is: " . __DIR__ . "<br>";

  function SENG412(){
    echo "The function name is: " . __FUNCTION__ . "<br>";
  }

  class CSA
  {
    public function getClassName(){
        return __CLASS__;
    } 
}
    $obj = new CSA();
    echo $obj -> getClassName();
    // SENG412();
?>