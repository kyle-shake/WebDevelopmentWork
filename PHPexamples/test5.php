<?php


$array = array(
    "wild" => "cats",
    "cats" => "wild",
);

$array2 = [
     "foo" => "bar",
     "bar" => "foo",
];

$array3 = array("Hi", "World", "Hello", "Universe");

/*
var_dump($array);
var_dump($array2);
var_dump($array3);
*/
echo $array['wild']."<br>";

echo $array2['bar']."<br>";

$avar = $array3[3];

echo "The value is ".$avar."<br>";

$what = "cats";

$answer = $array[$what];

echo "The $what in array is $answer<br>";


?>




