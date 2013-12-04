<?/*
  File Name     :   cdetestclass.php
  Purpose       :   implements the CDE Library
  Author        :   Andre van Zuydam
  Copyright     :   Spiceware Software CC 2007 - 2008
                    published under the terms of the GNU General Public License.
*/
require "cdesimple.php";

if (!isset($_REQUEST["edtDBTYPE"])) $_REQUEST["edtDBTYPE"] = "sqlite";

global $CDE_DEFAULT;

$CDE_DEFAULT["sqlite"]["hostname"] = "";
$CDE_DEFAULT["sqlite"]["dbpath"] = "sqlite.db";
$CDE_DEFAULT["sqlite"]["tmppath"] = "";
$CDE_DEFAULT["sqlite"]["username"] = "";
$CDE_DEFAULT["sqlite"]["password"] = "";

$CDE_DEFAULT["sqlite3"]["hostname"] = "";
$CDE_DEFAULT["sqlite3"]["dbpath"] = "sqlite.db";
$CDE_DEFAULT["sqlite3"]["tmppath"] = "";
$CDE_DEFAULT["sqlite3"]["username"] = "";
$CDE_DEFAULT["sqlite3"]["password"] = "";

$CDE_DEFAULT["postgres"]["hostname"] = "localhost";
$CDE_DEFAULT["postgres"]["dbpath"] = "testdb";
$CDE_DEFAULT["postgres"]["tmppath"] = "";
$CDE_DEFAULT["postgres"]["username"] = "postgres";
$CDE_DEFAULT["postgres"]["password"] = "postgres";

$CDE_DEFAULT["firebird"]["hostname"] = "127.0.0.1";
$CDE_DEFAULT["firebird"]["dbpath"] = "c:/websites/cdedb/CDETEST.FDB";
$CDE_DEFAULT["firebird"]["tmppath"] = "c:/windows/temp";
$CDE_DEFAULT["firebird"]["username"] = "SYSDBA";
$CDE_DEFAULT["firebird"]["password"] = "masterkey";

$CDE_DEFAULT["mssql"]["hostname"] = "Cobus-Notebook";
$CDE_DEFAULT["mssql"]["dbpath"] = "cdetest";
$CDE_DEFAULT["mssql"]["tmppath"] = "c:/windows/temp";
$CDE_DEFAULT["mssql"]["username"] = "sa";
$CDE_DEFAULT["mssql"]["password"] = "sa";

$CDE_DEFAULT["mysql"]["hostname"] = "127.0.0.1";
$CDE_DEFAULT["mysql"]["dbpath"] = "workflow";
$CDE_DEFAULT["mysql"]["tmppath"] = "";
$CDE_DEFAULT["mysql"]["username"] = "root";
$CDE_DEFAULT["mysql"]["password"] = "";

$CDE_DEFAULT["dbase"]["hostname"] = "";
$CDE_DEFAULT["dbase"]["dbpath"] = "c:/websites/cdedb/TBLUSERS.dbf";
$CDE_DEFAULT["dbase"]["tmppath"] = "c:/websites/";
$CDE_DEFAULT["dbase"]["username"] = "";
$CDE_DEFAULT["dbase"]["password"] = "";

$CDE_DEFAULT["CUBRID"]["hostname"] = "127.0.0.1";
$CDE_DEFAULT["CUBRID"]["dbpath"] = "demodb";
$CDE_DEFAULT["CUBRID"]["tmppath"] = "";
$CDE_DEFAULT["CUBRID"]["username"] = "dba";
$CDE_DEFAULT["CUBRID"]["password"] = "";


function iif($expression, $valuetrue, $valuefalse)
{
  if ($expression)
  {
    echo $valuetrue;
  }
    else
  {
    echo $valuefalse;
  }  
}

function drawselectform ($dbtype)
{
  global $CDE_DEFAULT;

  ?>
  <table id="form">
    <input type="hidden" name="dontrun" value="0">
    <tr><td><th colspan="2">Choose Database (Make sure it exists)</th></td></tr> 
    <tr><td><td><b>Database Type</b></td><td><select name="edtDBTYPE" width="140" onchange="document.forms[0].dontrun.value = 1; document.forms[0].submit();">
                                                <option <? iif ($dbtype == "sqlite", "selected", "") ?> >sqlite</option>
                                                <option <? iif ($dbtype == "sqlite3", "selected", "") ?> >sqlite3</option>
                                                <option <? iif ($dbtype == "firebird", "selected", "") ?> >firebird</option>
                                                <option <? iif ($dbtype == "mysql", "selected", "") ?> >mysql</option>
                                                <option <? iif ($dbtype == "mssql", "selected", "") ?> >mssql</option>
                                                <option <? iif ($dbtype == "oracle", "selected", "") ?> >oracle</option>
                                                <option <? iif ($dbtype == "dbase", "selected", "") ?> >dbase</option>
                                                <option <? iif ($dbtype == "postgres", "selected", "") ?> >postgres</option>
                                                <option <? iif ($dbtype == "mssqlnative", "selected", "") ?> >mssqlnative</option>
                                                <option <? iif ($dbtype == "CUBRID", "selected", "") ?> >CUBRID</option>
                                             </select>
        </td></tr>
    <tr><td><td><b>Hostname</b></td><td><input type="text" name="edtHOSTNAME" value="<?=$CDE_DEFAULT[$dbtype]["hostname"]?>"></td></tr>
    <tr><td><td><b>Database Name (Path)</b></td><td><input type="text" name="edtDBPATH" value="<?=$CDE_DEFAULT[$dbtype]["dbpath"]?>" size="40"></td></tr>
    <tr><td><td><b>Temporary Path</b></td><td><input type="text" name="edtTMPPATH" value="<?=$CDE_DEFAULT[$dbtype]["tmppath"]?>" size="40"></td></tr>
      <tr><td><td><b>Username</b></td><td><input type="text" name="edtUSERNAME" value="<?=$CDE_DEFAULT[$dbtype]["username"]?>"></td></tr>
    <tr><td><td><b>Password</b></td><td><input type="text" name="edtPASSWORD" value="<?=$CDE_DEFAULT[$dbtype]["password"]?>"></td></tr> 
    <tr><td colspan="2"><input id="button" type="submit" value="Run Tests"> </td></tr>   
  </table>  
  <?
}

function runtests ($dbtype, $hostname, $dbpath, $tmppath, $username, $password)
{
   echo "Testing $dbtype\n";
   
   if ($hostname != "")
   {
     $database = $hostname.":".$dbpath;  
   } 
     else
   {
     $database =  $dbpath;
   }  
   
   $CDE = new CDESimple ($database, $username, $password, $dbtype, $debug=true);

   //echo "<pre>";
   //print_r ($CDE->get_database());
   //echo "</pre>";
   //Create the table
   if ($dbtype != "dbase")
   {
      $sql = "drop table tblusers";
      
      $CDE->exec ($sql);
   
      $sql = 'create table tblusers (
                id integer default 0 not null,
                name varchar (100) default \'\',
                surname varchar (100) default \'\',
                image blob,
                birthdate date default null,
                email varchar (200) default \'\',
                primary key (id)     
              );'; 
              
      $CDE->exec ($sql);  
      if ($CDE->error) echo $CDE->error;      
   }
   
   //insert some values without params
   
   $sqlinsert = 'insert into tblusers (id, name, surname, image, birthdate, email)
                               values ( 1, \'Andre\', \'van Zuydam\', null, null, \'andre@spiceware.co.za\');';
   
     
   $CDE->exec ($sqlinsert);
   if ($CDE->error) echo $CDE->error;
   
   //insert some values with params
   
   $sqlinsert = 'insert into tblusers (id, name, surname, image, birthdate, email)
                               values (   ?,      ?,         ?, null,              null,       ?);';
   
     
   $CDE->exec ($sqlinsert, "2", "Cobus", "Stroebel", "cobus.s@cks.co.za");
   
   if ($CDE->error)
   { 
     echo $CDE->lastsql;
     echo $CDE->error;
   }  
   
   //update some values

   $CDE->set_blob ("tblusers","image",file_get_contents('logo.png'),$filter="id = 1");
   $CDE->set_blob ("tblusers","image",file_get_contents('logo.png'),$filter="id = 2");
   
   //read an array
   echo "<pre>";
   
   $people = $CDE->get_row ("select * from tblusers", CDE_ASSOC);
   
   if (!file_exists ('output'))
   {
     mkdir('output');
   }

   foreach ($people as $id => $person)
   {
     
     echo "Name : {$person["NAME"]} \n";
     echo "Surname : {$person["SURNAME"]} \n";
     echo "Email : {$person["EMAIL"]} \n";
     file_put_contents ("output/image{$person["NAME"]}.png", $person["IMAGE"]);
     echo "<img src=\"output/image{$person["NAME"]}.png\" />\n";
   } 
    
      
   //read an associated array
   $people = $CDE->get_row ("select * from tblusers", CDE_ARRAY);
   foreach ($people as $id => $person)
   {
    
     echo "Name : {$person[1]} \n";
     echo "Surname : {$person[2]} \n";
     echo "Email : {$person[5]} \n";
     file_put_contents ("output/image{$person[1]}.png", $person[3]);
     echo "<img src=\"output/image{$person[1]}.png\" />\n";
   }
   
   //read an object
   $people = $CDE->get_row ("select * from tblusers", CDE_OBJECT);
   foreach ($people as $id => $person)
   {
     echo "Name : {$person->NAME} \n";
     echo "Surname : {$person->SURNAME} \n";
     echo "Email : {$person->EMAIL} \n";
     file_put_contents ("output/image{$person->NAME}.png", $person->IMAGE);
     echo "<img src=\"output/image{$person->NAME}.png\" />\n";
   } 
   
   //get the database structure
   print_r($CDE->get_database());
   
   //
   echo "</pre>";

   //Make reports
   /*$report = $CDE->sql_report ($sql="select * from tblusers", 
													$groupby="", 
													$outputpath="output/", 
													$companyname="CDE", 
													$title="Printout of Users", 
													$extraheader="", 
													$orientation="P", 
													$pagesize="A4", 
													$totalcolumns=Array(),
													$compcolumns=Array(), 
													$createcsv=true, 
													$debug=false);

   print_r($report);*/
  
   $CDE->commit();

   $CDE->close();
}


?>

<html>

<head>
  <title> CDE engine tester </title>
</head>

<style>
  body {
    font-family : Verdana;
    font-size: 12px;  
  }
  
  
  #form {
    border : 1px dashed rgb(198,214,253); 
    width: 500px;   
  }
  
  #form input {
    font-family: Verdana;
    font-size : 12px;
  }
  
  #form th, td {
    font-family: Verdana;
    font-size : 12px;
    text-align: left;    
  }
  
  #button {
    cursor : pointer;
    cursor : hand;  
    width : 120px;
  }

</style>

<body>
  <form method="post" action="cdetestclass.php">
<?
  if ($_REQUEST["edtDBTYPE"] == "") $_REQUEST["edtDBTYPE"] = "sqlite";

  drawselectform($_REQUEST["edtDBTYPE"]);

  if ($_REQUEST["dontrun"] == 0)
  {
    runtests ($_REQUEST["edtDBTYPE"], $_REQUEST["edtHOSTNAME"], $_REQUEST["edtDBPATH"], $_REQUEST["edtTMPPATH"], $_REQUEST["edtUSERNAME"], $_REQUEST["edtPASSWORD"]);
  }

?>
  </form>
</body>
</html>
