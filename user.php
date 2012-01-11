<?php
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

function drawHistogram($container)
{
	foreach($container as $key=>$value)
	{
		asort($value);
		$value = array_reverse($value);
		$onCamp = 0;
		$total = 0;
		foreach($value as $loc=>$count)
		{
			if(!preg_match('/^\d*\.\d*\.\d*\.\d*$/',$loc))
			{
				$onCamp += $count;
			}
			$total += $count;
		}
		if(count($value))
		{
			$frac = $onCamp/$total;
		}
		else
		{
			$frac = 0;
		}
		if($frac >= 0.5)
		{
			echo('<span class="histRow"><span class="histTitle" style="background-color: rgb('.round(255*((1-$frac)*2)).',255,0)">'.$key.'</span>');
		}
		else
		{
			echo('<span class="histRow"><span class="histTitle" style="background-color: rgb(255,'.round(255*$frac*2).',0)">'.$key.'</span>');
		}
		foreach($value as $loc=>$count)
		{
			echo('<span class="hist" title="'.$loc.', '.$count.' times" style="width:');
			if($loc == end(array_keys($value)))
			{
				echo(round(($count/$total*95)+1,3));
			}
			else
			{
				echo(round(($count/$total*95),3));
			}
			echo('%; background-color:');
			if(preg_match('/^\d*\.\d*\.\d*\.\d*$/',$loc))
			{
				echo('rgb(255,0,0)');
			}
			else
			{
				echo('rgb(0,255,0)');
			}
			echo('">'.$loc.'</span>');
		}
		echo("</span>\n");
	}
}

include("locations.php");
global $locations;
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
				color: white;
			}
			td
			{
				overflow: hidden;
			}
			.section
			{
			}
			.room
			{
				padding: 0 20px 0 20px;
				display: inline-block;
				vertical-align: top;
			}
			.histRow
			{
				padding: 0 0 0 0;
				margin: 0 0 4px 0;
				display: block;
				width: 95%;
				color: black;
				height: 20px;
				overflow: hidden;
				white-space: nowrap;
			}
			.hist
			{
				padding: 0 0 0 0;
				border-left: 4px solid black;
				margin: 0 -4px 0 0;
				vertical-align: top;
				display: inline-block;
				overflow: hidden;
				clear: both;
				height: 100%;
			}
			.histTitle
			{
				padding: 0 0 0 0;
				display: inline-block;
				clear: both;
				width: 5%;
				height: 100%;
			}
		</style>
	</head>
	<body>
<?php
	if(!isset($_GET['user']))
	{
		die('Error: No user specified.');
	}
	else
	{
		$user = $_GET['user'];
		if(!preg_match('/^\w*$/', $user))
		{
			die('Illegal characters in username');
		}
		else
		{
			echo('<h1>'.$user.'</h1>');
			echo('<span style="width: 30%; display: inline-block"><table style="table-layout: fixed; width:100%">');
			echo('<tr><th>In time</th><th>Out time</th><th>Location</th></tf>');
			$results = array();
			$week = array();
			$weekDays = array(
				'Mon' => 0,
				'Tue' => 1,
				'Wed' => 2,
				'Thu' => 3,
				'Fri' => 4,
				'Sat' => 5,
				'Sun' => 6
			);
			for($i=0;$i<7; ++$i)
			{
				$week[$i] = array();
			}
			$hours = array();
			for($i=0;$i<24; ++$i)
			{
				$hours[$i] = array();
			}
			exec('for i in /var/log/wtmp*; do last -i '.$user.' -f $i; done;', $results);
			foreach($results as $result)
			{
				if(preg_match('/^'.substr($user,0,8).'\s*pts\/\d*\s*(?P<ip>\d*\.\d*\.\d*\.\d*)\s*(?P<wday>\w*)\s*(?P<month>\w*)\s*(?P<day>\d*)\s*(?P<stime>\d\d:\d\d)\s*-?\s?(?P<etime>.*)$/', $result, $matches))
				{
					$loginTime = $matches['stime'];
					$logoutTime = $matches['etime'];
					$loginLocation = split('\.', $matches['ip'], 4);
					foreach($locations as &$location)
					{
						if(isset($location['dns'])&&!isset($location['baseIP']))
						{
							$location['baseIP'] = explode(".",gethostbyname($location['dns']));
						}
						if(isset($location['baseIP']))
						{
							if(ipMatch($loginLocation, $location['baseIP'], $location['minIP'], $location['maxIP']))
							{
								if(isset($location['title']))
								{
									$loginLocation = $location['title'];
								}
								elseif(isset($location['room']))
								{
									$loginLocation = $location['room'];
								}
							}
						}
					}
					if(is_array($loginLocation))
					{
						$loginLocation = implode('.',$loginLocation);
					}
					if(isset($week[$weekDays[$matches['wday']]][$loginLocation]))
					{
						$week[$weekDays[$matches['wday']]][$loginLocation]++;
					}
					else
					{
						$week[$weekDays[$matches['wday']]][$loginLocation] = 1;
					}
					$sHour = (int)substr($matches['stime'],0,2);
					$eHour = (int)substr($matches['etime'],0,2);
					$days = 0;
					if(preg_match('/(?P<etime>\d\d:\d\d)\s*\((?P<days>\d*)?\+?\d\d:\d\d\)/', $matches['etime'], $tMatches))
					{
						if(isset($tMatches['days']))
						{
							$days = $tMatches['days'];
						}
						$matches['etime'] = $tMatches['etime'];
					}
					$eHour = (int)substr($matches['etime'],0,2);
					if($days != 0)
					{
						foreach($hours as &$hour)
						{
							if(isset($hour[$loginLocation]))
							{
								$hour[$loginLocation] += $days;
							}
							else
							{
								$hour[$loginLocation] = $days;
							}
						}
						for($i=0; $i<$days; ++$i)
						{
							$week[($i+$matches['wday'])%7][$loginLocation]++;
						}
					}
					for($i=$sHour; $i!=($eHour+1)%24; $i=($i+1)%24)
					{
						if(isset($hours[$i][$loginLocation]))
						{
							$hours[$i][$loginLocation]++;
						}
						else
						{
							$hours[$i][$loginLocation] = 1;
						}
					}
					echo('<tr>');
					if(preg_match('/^\d*\.\d*\.\d*\.\d*$/',$loginLocation))
					{
						$color = "255,0,0";
					}
					else
					{
						$color = "0,255,0";
					}
					echo('<td style="background-color: rgb('.$color.')" class="in">'.$matches['day'].' '.$matches['month'].' '.$loginTime.'</td>');
					echo('<td style="background-color: rgb('.$color.')" class="out">'.$logoutTime.'</td>');
					echo('<td style="background-color: rgb('.$color.')" class="loc">'.$loginLocation.'</td>');
					echo('</tr>');
				}
			}
			echo('</table></span>');
			echo('<span style="vertical-align: top; width: 70%; display: inline-block">');
			echo('<h2>By day</h2>');
			foreach($weekDays as $index=>$day)
			{
				$week[$index] = $week[$day];
				unset($week[$day]);
			}
			drawHistogram($week);
			echo('<h2>By hour</h2>');
			drawHistogram($hours);
			echo('</span>');
		}
	}
?>
	</body>
</html>
