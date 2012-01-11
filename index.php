<?php
include("auth.php");
global $mysqlUser;
global $mysqlPass;
global $mysqlTable;

function convertTime($time)
{
	if(preg_match("/(\d*):(\d*)/", $time, $matches))
	{
		return $matches[1]*60 + $matches[2];
	}
	elseif(preg_match("/(\d*)d/", $time, $matches))
	{
		return $matches[1]*24*60;
	}
	elseif(preg_match("/(\d+)/", $time, $matches))
	{
		return $matches[1];
	}
	else
	{
		return "0";
	}
}

function getJson($request_url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $request_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function ipMatch($needle, $haystackBase, $haystackMin=0, $haystackMax=255)
{
	//echo("needle: $needle, haystackBase: $haystackBase, min: $haystackMin, max: $haystackMax");
	foreach($haystackBase as $key => $value)
	{
		if($value != $needle[$key])
		{
			return false;
		}
	}
	if(count($haystackBase) < 4)
	{
		if(end($needle) < $haystackMin || end($needle) > $haystackMax)
		{
			return false;
		}
	}
	return true;
}

function printLatLong($latlong)
{
	echo("<a href='http://maps.google.com/maps?z=16&q=".$latlong[0].",".$latlong[1]."'>Maps link</a><br />\n");
}

function printSeat($seat)
{
	if(isset($seat['idle']))
	{
		echo("<td style='background-color: ".colorStyle($seat)."'>");
	}
	else
	{
		echo("<td style='background-color: rgb(0,255,0)'>");
	}
	foreach($seat as $user)
	{
		echo("<a href='user.php?user=".$user['username']."'>".$user['username']."</a><br />");
	}
	//echo($seat['name']."<br />");
	//echo($seat['idle']."<br />");
	echo("</td>\n");
}

function printTitle($title)
{
	echo("<h2>".$title."</h2>\n");
	echo($i++);
}

function printName($seat)
{
	echo("<a href='user.php?user=".$seat['username']."' ");
	if(isset($seat['idle']))
	{
		echo("style='color: ".colorStyle($seat)."'>");
	}
	else
	{
		echo("style='color: rgb(0,255,0)'>");
	}
	echo($seat['username']."<br />");
	//echo($seat['name']."<br />");
	//echo($seat['idle']."<br />");
	echo("</a>\n");
}

function printRoom($roomName)
{
	$json = json_decode(getJson("http://search.warwick.ac.uk/search/rooms.json?name=$roomName"));
	$floor = $json[0]->floor;
	$suffix = "";
	switch($floor)
	{
		case "Ground":
			$suffix = "";
			break;
		case "1":
			$suffix = "st";
			break;
		case "2":
			$suffix = "nd";
			break;
		case "3":
			$suffix = "rd";
			break;
		case "5":
		case "6":
		case "7":
		case "8":
			$suffix = "th";
		break;
	}
	echo("<a href='http://go.warwick.ac.uk/interactivemap?m=ShowRoom(".$json[0]->name.")'>");
	echo($json[0]->buildingName.": ".$floor.$suffix." floor");
	if($json[0]->section != "")
	{
		echo(", ".$json[0]->section);
	}
	echo("</a><br />\n");
}

function colorStyle($seat)
{
	$time = $seat['idle'];
	if((int)$time <= 0)
	{
		$color = 0;
	}
	else
	{
		$color = (int)(log($time)*70);
	}
	if($color < 256)
	{
		return("rgb(".$color.",255,0)");
	}
	elseif($color <512)
	{
		return("rgb(255,".(255-($color-256)).",0)");
	}
	else
	{
		return("rgb(255,0,0)");
	}
}

?>
<html>
	<head>
		<style type="text/css">
			body
			{
				background-color: black;
				color: white;
			}
			td
			{
				background-color: #444444;
				border: 1px solid black;
				color: black;
			}
			a
			{
				text-decoration: none;
				color: inherit;
			}
			.category
			{
			}
			.room
			{
				padding: 0 20px 0 20px;
				display: inline-block;
				vertical-align: top;
			}
		</style>
	</head>
	<body>
<?php
$users = array();

$results = array();
exec("finger -s", $results);

foreach($results as $result)
{
	$user = array();
	if(preg_match('/^(?P<user>\w+) *(?P<name>[\w ]*)\*?pts\/\d* *(?P<idle>[\d\*][^ ]*)? (?P<time>.*) \((?P<ip>[\d\.]*).*$/', $result, $matches))
	{
		$user['username'] = $matches['user'];
		$user['name'] = $matches['name'];
		$user['idle'] = convertTime($matches['idle']);
		$user['seenTime'] = $matches['time'];
		$user['lastIP'] = split('\.', $matches['ip'], 4);
	}
	if(!isset($users[$user['username']]))
	{
		$users[$user['username']] = $user;
	}
	else if($user['idle'] <= $users[$user['username']]['idle'])
	{
		$users[$user['username']] = $user;
	}
	//array_push($users, $user);
	//echo $result."<br />";
}

//print_r($users);

include("locations.php");
global $locations;
foreach($locations as &$location)
{
	if(isset($location['dns']))
	{
		$location['baseIP'] = explode(".",gethostbyname($location['dns']));
	}
	$location['seats'] = array();
	foreach($users as $key => $user)
	{
		if(isset($location['baseIP']))
		{
			if(ipMatch($user['lastIP'], $location['baseIP'], $location['minIP'], $location['maxIP']))
			{
				if(!isset($location['seats'][$user['lastIP'][3]-$location['minIP']]))
				{
					$location['seats'][$user['lastIP'][3]-$location['minIP']] = array($user);
				}
				else
				{
					array_push($location['seats'][$user['lastIP'][3]-$location['minIP']], $user);
				}
				unset($users[$key]);
			}
		}
	}
}

$categories = array();
foreach($locations as $loc)
{
	if(!empty($loc['seats'])||$loc['alwaysShow'])
	{
		$cat = $loc['category'];
		if(!$cat)
		{
			$cat = 'Other';
		}

		if(!$categories[$cat])
		{
			$categories[$cat] = array();
		}
		array_push($categories[$cat], $loc);
	}
}

foreach($categories as $key=>$cat)
{
	echo('<div class="category"><h1>'.$key.'</h1>');
	foreach($cat as $loc)
	{
		echo('<div class="room">');
		if(isset($loc['title']))
		{
			printTitle($loc['title'], $loc['room']);
		}
		elseif(isset($loc['room']))
		{
			printTitle($loc['room']);
		}
		if(isset($loc['latlong']))
		{
			printLatLong($loc['latlong']);
		}
		if(isset($loc['description']))
		{
			printDesc($loc['description']);
		}
		if(isset($loc['room']))
		{
			printRoom($loc['room']);
		}
		foreach($loc['seats'] as $users)
		{
			foreach($users as $user)
			{
				printName($user);
			}
		}
		if(isset($loc['seatingPlan']))
		{
			echo("<table>\n");
			foreach($loc['seatingPlan'] as $row)
			{
				echo("<tr>\n");
				foreach($row as $seat)
				{
					if(isset($loc['seats'][$seat]))
					{
						printSeat($loc['seats'][$seat]);
					}
					elseif($seat == null)
					{
						echo("<td style='background-color: black'>");
					}
					else
					{
						echo("<td>");
						echo($seat);
					}
					echo("</td>");
				}
				echo("</tr>\n");
			}
			echo("</table>\n");
		}
		echo('</div>');
		//echo("<br />");
	}
	echo('</div>');
}
?>
	</body>
</html>






