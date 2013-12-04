Cross Database Engine

Installation

Copy cdeclass.php & cdesimple.php into your development folder or includes folder.

Getting Started

PLEASE HAVE A LOOK UNDER DOC FOR THE NEW CDE GUIDE

I normally create a connection.php to be included where neccessary.  You can do the same in your projects, here is a sample of code:

require_once ("cdesimple.php");

global $DB;
$CDE = new CDESimple ($_CDE_DATABASE, $_CDE_USERNAME, $_CDE_PASSWORD, "firebird", $debug=false, "dd/mm/YYYY"); //date format can be YYYY-mm-dd or mm/dd/YYYY for example

Example of getting records

$cars = $CDE->get_row ("select * from tblcar");

//The above will be an object array of cars

foreach ($cars as $id => $record)
{
  echo "$record->NAME $record->MODEL";
}

Example of fetching one record

$cars = $CDE->get_value (0, "select * from tblcar");

And if you really want to see whats happening, pop the hood open on cdesimple, here is a short list of cool functions:

function last_error() - returns last error message

function connect ($dbpath="", $username="", $password="", $dbtype="sqlite") - connects to a database and creates a database handle

function close () - close the database connection - more needed for sqlite and mysql

function set_database () - probably going to be for mysql

function exec ($sql="", $params...) - execute sql and pass params - see the documentation for cdeclass it works the same

function commit() - commits data - more for transaction driven databases like oracle & firebird

function get_error() - works with last error

function get_row ($sql="", $rowtype=0) - get records into an array -  $rowtype = 0 - Object, 1 - Array, 2 - Array Indexed by Field Name 

function get_value ($id=0, $sql="", $rowtype=0) - gets a single record out of the array if possible

function get_field_info ($sql=""); - very cool if you want to do dynamic record iteration

function get_affected_rows ($sql="") - count of records in the result set

function get_next_id ($tablename="", $fieldname="") - if you dont have generators like in firebird, mysql or don't want to use generators then use this


Enjoy using the Cross Database Engine, it is not going to go away and we intend to keep updating it.  
Spiceware Software uses it as a production library so you can be sure it is tested every day.


Yours sincerely

Andre van Zuydam

Feel free to email me if you want to take part or want to ask questions:

andre at spiceware dot co dot za

