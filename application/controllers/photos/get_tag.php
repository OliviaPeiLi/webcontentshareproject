<?php
/* sample code that return sample data */


$data['tags'][] = array('label' => 'Monica Geller', 'x' => 112, 'y' => 80, 'h' => 50, 'w' => 50, 'id' => 1 );
$data['tags'][] = array('label' => 'Phoebe Buffay','x' => 404, 'y' => 72, 'h' => 50, 'w' => 50, 'id' => 2 );
$data['tags'][] = array('label' => 'Rachel Green', 'x' => 615, 'y' => 97, 'h' => 50, 'w' => 50, 'id' => 3 );
$data['tags'][] = array('label' => 'Joey Tribbiani', 'x' => 76, 'y' => 400, 'h' => 50, 'w' => 50, 'id' => 4 );
$data['tags'][] = array('label' => 'Chandler Bing', 'x' => 	373, 'y' => 398, 'h' => 50, 'w' => 50, 'id' => 5 );
$data['tags'][] = array('label' => 'Ross Geller', 'x' => 	691, 'y' => 402, 'h' => 50, 'w' => 50, 'id' => 6 );

$data['friends'][] = array('name' => 'Rachel Green',  'id' => 1 );
$data['friends'][] = array('name' => 'Monica Geller', 'id' => 2 );
$data['friends'][] = array('name' => 'Phoebe Buffay',  'id' => 3 );
$data['friends'][] = array('name' => 'Joey Tribbiani', 'id' => 4 );
$data['friends'][] = array('name' => 'Chandler Bing', 'id' => 5 );
$data['friends'][] = array('name' => 'Ross Geller', 'id' => 6 );

$data['tags'][] = array('label' => 'My%20favourite%20umbrella.', 'x' => 147, 'y' => 117, 'h' => 82 , 'w' => 107, 'id' => 0);
$data['tags'][] = array('label' => 'Image%20%3Ca%20href%3D%22http%3A//www.flickr.com/photos/lynhana/416152814/%23/%22%3Eauthor%3C/a%3E.', 'x' => 282.5, 'y' => 	268, 'h' => 119 , 'w' => 	159, 'id' => 0);
$data['tags'][] = array('label' => 'I%20%3Cstrong%3Elove%3C/strong%3E%20these%20days.', 'x' => 	392.5, 'y' => 0, 'h' => 349 , 'w' => 	223, 'id' => 0);
$data['tags'][] = array('label' => 'rainy%20days...', 'x' => 	417.5, 'y' => 	28.5, 'h' => 349 , 'w' => 159, 'id' => 0);

print(json_encode($data));
?>