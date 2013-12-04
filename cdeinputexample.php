<html>

<body>
<form method="post">
<?php
//Example for $CDE->switchid
require_once ("cdesimple.php");

$CDE = new CDESimple ("database.db", "", "", "sqlite3");
echo $CDE->switchid("switchid");


//Actual switch in code, assuming we are posting to same place

switch ($_REQUEST["switchid"]) {
  //what to do when switch is 100
  case 100:
    echo "Ok we're done! ".$_REQUEST["edtName"]." your choice was Option ".$_REQUEST["edtChoose"];
  break;
  case 200:
    echo "Type in some values:<br>";
    echo "Name:".$CDE->input ("edtName", 200 )."<br>"; 
    echo "Choice:".$CDE->select ($name="edtChoose", $width=100, $alttext="Choose something", $selecttype="array", $lookup="1,Apples|2,Bananas");
    echo $CDE->input ("btnGo", 100, "Click here to see what happens", $compulsory="", "button", "Go", "onclick=\"setswitchid(100);\"")."<br>";
    
  break;
  default:
    echo "Hello"; 
    setswitchid (200, true); // true means post will be called
  break;

}

?>
</form>
</body>
</html>