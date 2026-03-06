<?php 
// Create an array and map using index [0]
$array = ["dara@gmail.com","moyin@gmail.com","new@gmail.com"];
echo $array[0] . "<br>";
echo $array[1] . "<br>";
echo $array[2] . "<br><br>";

 $student = ["dara" => "dara@gmail.com","moyin" =>"moyin@gmail.com","new" =>"new@gmail.com"];
 echo $student["dara"] . "<br>";
 echo $student["moyin"] . "<br>";
 echo $student["new"] . "<br>";

// Create multidimensional array using key value
$student = [
    "dara" => [
        "email" => "dara@gmail.com",
        "phone" => "08100000000",
    ],
    "moyin" => [
        "email" => "moyin@gmail.com",
        "phone" => "08100000000",
    ],
    "new" => [
        "email" => "new@gmail.com",
        "phone" => "08100000000",
    ],
];
echo $student["dara"]["email"] . "<br>";
echo $student["moyin"]["email"] . "<br>";
echo $student["new"]["email"] . "<br>";
echo $student["dara"]["phone"] . "<br>";
echo $student["moyin"]["phone"] . "<br>";
echo $student["new"]["phone"] . "<br>";
?>