<?php //service 
//http://blog.ijasoneverett.com/2013/02/rest-api-a-simple-php-tutorial/
 
// This is the API, 2 possibilities: show the app list or show a specific app by id.
// This would normally be pulled from a database but for demo purposes, I will be hardcoding the return values.

$config = array(
	"datafile"=>"/Library/WebServer/Documents/victimizer/data/digis.json"
	);

function get_data($filename){
	return array("data"=>json_decode(file_get_contents($filename)));
}

function update_data_by_id($filename, $data){

	if (!isset($data["id"]) || !isset($data["n"]) || !isset($data["v"]) ){
    	return array("msg" => "Missing argument:id or n or v");
	}
	
	$uid = $data['id'];
	$v = $data['v'];
	$n = $data['n'];

	$json = json_decode(file_get_contents($filename),true);
//	$found = $json[$uid];
//	$tmp = $found;
//	$tmp[$n] = $v ;
//	$json[$uid] = $tmp;
	$json[$uid][$n] = $v;

	if(!file_put_contents($filename, json_encode($json))){
	
		return array("msg" => "Error Putting");
	}
    	
    return array("msg" => array("id"=>$uid, "name"=>$n, "value"=>$v, "action"=> "Updated"), "data"=>json_decode(file_get_contents($filename),true));
	
}

function delete_data_by_id($filename, $data){
	if (!isset($data["id"]) ){
    	return array("msg" =>  "Missing argument:id ");;
	}
	
	$uid = $data['id'];
	$json = json_decode(file_get_contents($filename),true);
	unset($json[$uid]);// = null;

	if(!file_put_contents($filename, json_encode(array_values($json)))){
    	return array("msg" => "Error Putting");
	}
    return array("msg" => array("id"=>$uid, "action"=> "Deleted"), "data"=>json_decode(file_get_contents($filename),true));
	

}


function get_app_by_id($id)
{
  $app_info = array();

  // normally this info would be pulled from a database.
  // build JSON array.
  switch ($id){
    case 1:
      $app_info = array("app_name" => "Random Digis", "data" => get_data($GLOBALS['config']['datafile']), "app_version" => "1.0"); 
      break;
    case 2:
      $app_info = array("app_name" => "Audio Countdown", "app_price" => "Free", "app_version" => "1.1");
      break;
  }

  return $app_info;
}


function get_app_list()
{
  //normally this info would be pulled from a database.
  //build JSON array
  $app_list = array(array("id" => 1, "name" => "Web Demo"), array("id" => 2, "name" => "Audio Countdown"), 
  array("id" => 3, "name" => "The Tab Key"), array("id" => 4, "name" => "Music Sleep Timer")); 

  return $app_list;
}





$possible_url = array("get_app_list", "get_app","getdata","updatedata","deletedata");

$value = "APP: An error has occurred";

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  switch ($_GET["action"])
    {
      case "get_app_list":
        $value = get_app_list();
        break;
      case "get_app":
        if (isset($_GET["appid"]))
          $value = get_app_by_id($_GET["appid"]);
        else
          $value = "Missing argument";
        break;

      case "getdata":
        //if (isset($_GET["id"]))
          $value = get_data( $GLOBALS['config']['datafile'] );
        //else
        //  $value = "Missing argument";
        break;

      case "updatedata":
        if (isset($_GET["appid"]))
          $value = update_data_by_id($GLOBALS['config']['datafile'], $_GET);
        else
          $value = "Missing argument";
        break;

      case "deletedata":
        if (isset($_GET["appid"]))
          $value = delete_data_by_id($GLOBALS['config']['datafile'], $_GET);
        else
          $value = "Missing argument";
        break;

    }
}

//return JSON array
exit(json_encode($value));
?>