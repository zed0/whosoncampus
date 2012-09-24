<?php
$locations = array();

//House of hair
array_push($locations, array(
	'category' => 'Houses',
	'title' => 'House of Hair',
	'dns' => 'yuno.dyndns-ip.com',
	'latlong' => array(52.40805,-1.542138)
));

//House of James
array_push($locations, array(
	'category' => 'Houses',
	'title' => 'House of James',
	'dns' => 'jgoode.co.uk',
	'latlong' => array(51.8333,-3.0167)
));

//zed0's house
array_push($locations, array(
	'category' => 'Houses',
	'title' => 'zed0\'s House',
	'dns' => 'zed0.homeip.net',
	'latlong' => array(52.3943, -1.5502)
));

//Fort Doherty
array_push($locations, array(
	'category' => 'Houses',
	'title' => 'Fort Doherty',
	'baseIP' => array(81,159,70,16),
	'latlong' => array(52.3915,  -1.56229)
));

//DCS
//CS001
array_push($locations, array(
	'category' => 'DCS',
	'baseIP' => array(137,205,112),
	'minIP' => '0',
	'maxIP' => '30',
	'seatingPlan' => array(
		array(30,29,28,27,26),
		array(21,23,22,24,25),
		array(20,19,18,17,16),
		array(11,12,13,14,15),
		array(10, 9, 8, 7, 6),
		array( 1, 2, 3, 4, 5)
	),
	'room' => 'CS0.01'
));

//CS003
array_push($locations, array(
	'category' => 'DCS',
	'baseIP' => array(137,205,113),
	'minIP' => '80',
	'maxIP' => '130',
	'seatingPlan' => array(
		array(20,19,18,17,16),
		array(11,12,13,14,15),
		array(10, 9, 8, 7, 6),
		array( 1, 2, 3, 4, 5)
	),
	'room' => 'CS0.03'
));

//CS006
array_push($locations, array(
	'category' => 'DCS',
	'baseIP' => array(137,205,113),
	'minIP' => '130',
	'maxIP' => '180',
	'seatingPlan' => array(
		array(null,null,null,null,null,null,46,47,48,50,49),
		array(null,null,null,null,null,null,41,42,43,44,45),
		array(  31  ,32,  33,  34,  35,null,36,37,38,39,40),
		array(  21,  22,  23,  24,  25,null,26,27,28,29,30),
		array(  11,  12,  13,  14,  15,null,18,16,17,19,20),
		array(   1,   2,   3,   4,   5,null, 6, 7, 8, 9,10)
	),
	'room' => 'CS0.06'
));

//MSc Lab - CS102
array_push($locations, array(
	'category' => 'DCS',
	'baseIP' => array(137,205,112),
	'minIP' => '130',
	'maxIP' => '154',
	'seatingPlan' => array(
		array(  21,  22,  23,  24),
		array(  20,  19,  18,  17),
		array(  13,  14,  15,  16),
		array(  12,  11,  10,   9),
		array(   5,   6,   7,   8),
		array(   4,   3,   2,   1)
	),
	'room' => 'CS1.02'
));


//DCS wireless
array_push($locations, array(
	'category' => 'DCS',
	'title' => 'DCSwireless',
	'baseIP' => array(137,205,114),
	'minIP' => 0,
	'maxIP' => 255
));

//Engineering
//F210
array_push($locations, array(
	'category' => 'Engineering',
	'baseIP' => array(137,205,148),
	'minIP' => '71',
	'maxIP' => '100',
	'alwaysShow' => true,
	'seatingPlan' => array(
		array(27,28,29,30),
		array(23,24,25,26),
		array(19,20,21,22),
		array(16,17,18),
		array(13,14,15),
		array(10,11,12),
		array( 7, 8, 9),
		array( 4, 5, 6),
		array( 1, 2, 3)
	),
	'room' => 'F2.10'
));

//F211
array_push($locations, array(
	'category' => 'Engineering',
	'baseIP' => array(137,205,148),
	'minIP' => '0',
	'maxIP' => '70',
	'alwaysShow' => true,
	'seatingPlan' => array(
		array(28,29,  30,  31,null,67,68,69,70),
		array(25,26,  27,null,null,63,64,65,66),
		array(22,23,  24,null,null,59,60,61,62),
		array(19,20,  21,null,null,55,56,57,58),
		array(16,17,  18,null,null,51,52,53,54),
		array(13,14,  15,null,null,47,48,49,50),
		array(10,11,  12,null,null,43,44,45,46),
		array( 7, 8,   9,null,null,39,40,41,42),
		array( 5, 6,null,null,null,35,36,37,38),
		array( 3, 4),
		array( 1, 2)
	),
	'room' => 'F2.11'
));

//F215
array_push($locations, array(
	'category' => 'Engineering',
	'baseIP' => array(137,205,148),
	'minIP' => '101',
	'maxIP' => '116',
	'alwaysShow' => true,
	'seatingPlan' => array(
		array(   5,   6,null,15,16),
		array(   3,   4,null,13,14),
		array(   1,   2,null,11,12),
		array(null,null,null, 9,10),
		array(null,null,null, 7, 8)
	),
	'room' => 'F2.15'
));

//Maths
//A001
array_push($locations, array(
	'category' => 'Maths',
	'baseIP' => array(137,205,233),
	'minIP' => '128',
	'maxIP' => '148',
	'seatingPlan' => array(
		array( 1, 2, 3, 4, 5),
		array(10, 9, 8, 7, 6),
		array(11,12,13,14,15),
		array(20,19,18,17,16)
	),
	'room' => 'MS.A0.01'
));

//A002
array_push($locations, array(
	'category' => 'Maths',
	'room' => 'MS.A0.02'
));

//A003
array_push($locations, array(
	'category' => 'Maths',
	'room' => 'MS.A0.03'
));

//B204
array_push($locations, array(
	'category' => 'Maths',
	'baseIP' => array(137,205,56),
	'minIP' => '1',
	'maxIP' => '255',
	'room' => 'MS.B2.04'
));

//Library
//LYFL01
array_push($locations, array(
	'category' => 'Library',
	'baseIP' => array(137,205),
	'minIP' => '16',
	'maxIP' => '17',
	'room' => 'L1.28'
));

//LYFL02
array_push($locations, array(
	'category' => 'Library',
	'room' => 'L2.23'
));

//Hotspot
array_push($locations, array(
	'title' => 'Hotspot',
	'baseIP' => array(172,31),
	'minIP' => '1',
	'maxIP' => '255',
));

//Jack Martin
array_push($locations, array(
	'title' => 'Jack Martin',
	'baseIP' => array(137,205,53),
	'minIP' => '1',
	'maxIP' => '255',
));

//Digital Lab
array_push($locations, array(
	'title' => 'Digital Lab',
	'baseIP' => array(137,205,175),
	'minIP' => '1',
	'maxIP' => '255',
));

//Azurit's office
array_push($locations, array(
	'title' => 'Azurit\'s Office',
	'baseIP' => array(137,205,23),
	'minIP' => '1',
	'maxIP' => '255',
	'room' => 'P5.42'
));

array_push($locations, array(
	'title' => 'Campus - Unknown',
	'baseIP' => array(137,205),
	'minIP' => '1',
	'maxIP' => '255'
));
