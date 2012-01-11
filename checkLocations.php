#!/usr/bin/php
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
	elseif(preg_match("/(\d*)/", $time, $matches))
	{
		return $matches[1];
	}
	else
	{
		return "0";
	}
}

$results = array();
exec("finger -s", $results);
//print_r($results);
$users = array();
foreach($results as $result)
{
	$user = array();
	if(preg_match('/^(?P<user>\w+) *(?P<firstname>\w*) (?P<lastname>\w*) *pts\/\d* *(?P<idle>\d[^ ]*)? (?P<time>.*) \((?P<ip>[\d\.]*).*$/', $result, $matches))
	{
		$user['user'] = $matches['user'];
		$user['firstname'] = $matches['firstname'];
		$user['lastname'] = $matches['lastname'];
		$user['idle'] = convertTime($matches['idle']);
		$user['time'] = convertTime($matches['time']);
		$user['ip'] = $matches['ip'];
	}
	array_push($users, $user);
	mysql_connect("localhost", $mysqlUser, $mysqlPass) or die(mysql_error());
	mysql_select_db($mysqlDB) or die(mysql_error());
}
foreach($users as $user)
{
	if($user['firstname'])
	{
		print_r($user);
		$result = mysql_query('INSERT INTO '.$mysqlTable.' (firstname, lastname, username, lastIP, seenTime) VALUES("'.$user[firstname].'", "'.$user[lastname].'", "'.$user[user].'", "'.$user[ip].'", '.(date("U")-$user[idle]).')') or die(mysql_error());
	}
}
