<?php



class User{
	public $firstname = "";
        public $lastname = "";
        public $birthdate = "";
        public $func;
	public $notUsed;
        protected $pro = "hidden";
        private $priv = "also hidden";

	public function _construct(){
		$this->func = function(){
			return "Foo";
		};
	}
}

$user = new User();
$user->firstname = "foo";
$user->lastname = "bar";

$result = json_encode($user);
var_dump($result);

echo "<p>Hello, ";
echo $result->firstname;

echo "<p> Your birthday is ";
echo $result->birthdate;
echo "</p>";
echo $result->func;

$user->birthdate = new DateTime();
$user->notUsed = "It is used now";

$result2 = json_encode($user);

var_dump($result2);


echo "<p>";
$decoded = json_decode($result2);

$count = 0;
foreach ($decoded as $key => $value){
	$count++;
	echo $count;
	echo ") $key = $value";
	echo "\n";
}

echo "<p>Hello, ";
echo $decoded->firstname;
echo "<p> Your birthday is ";
echo $decoded->birthdate;

echo "<p>This variable is hidden: ";
echo $decoded->priv;
echo "<p>This variable is protected: ";
echo $decoded->pro;













?>
