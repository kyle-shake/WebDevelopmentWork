<?php

do_header();

do_body();

function do_header(){

echo "
<html>
<title> PHP Example 4 </title>
<body>
";
}

function do_body(){
echo "
<p> This is the first paragraph </p>
<br>
<br>
<p> You're using PHP functions </p>
";
get_date();
}

function get_date(){

require("test3.php");

}

?>
