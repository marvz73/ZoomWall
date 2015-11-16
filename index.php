
<?php
 

/*
* Desc: This will fetch the videos of the page and a limit of 100
* Params: fb_page_id, access_token, offset, limit
*/
function getVideos($fb_page_id, $access_token, $offset, $limit, $returnType = 'json'){

	$result = '';

	$fields="id,description,title,source,picture";

	$fb_page_id = "304229246363923";

	$json_link = "https://graph.facebook.com/v2.5/{$fb_page_id}/videos?".$access_token . "&fields=id,description,title,source,picture&offset={$offset}&limit={$limit}";


	$sjson = file_get_contents($json_link);

	$obj = json_decode($sjson, true, 512, JSON_BIGINT_AS_STRING);

	if($returnType != 'json')
	{

		foreach($obj['data'] as $video){
			// $result .= "<img src='".$video['picture']."' />";

$result .= '<video width="400" controls>';
$result .= '<source src="'.$video['source'].'" type="video/mp4">';
$result .= 'Your browser does not support HTML5 video.';
$result .= '</video>';

		}

	}else{
		$result = $obj['data'];
	}

	return $result;

}


/*
* Desc: This will fetch the photos of the page and a limit of 100
* Params: fb_page_id, access_token, offset, limit
*/
function getPhotos($fb_page_id, $access_token, $offset, $limit, $returnType = 'json'){

	$result = '';

	$jsonData = file_get_contents("https://graph.facebook.com/v2.5/{$fb_page_id}/albums?fields=id,name&".$access_token);

	$jsonObject = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
 
	$album_count = count($jsonObject['data']);

	for($x=0; $x<$album_count; $x++){
	 
	    $id = isset($jsonObject['data'][$x]['id']) ? $jsonObject['data'][$x]['id'] : "";
	    $name = isset($jsonObject['data'][$x]['name']) ? $jsonObject['data'][$x]['name'] : "";
	    // if you want to exclude an album, just add the name on the if statement
	    if(
	        $name!="Profile Pictures" &&
	        $name!="Cover Photos" 
	        // $name!="Timeline Photos"
	    ){
			$json_links = "https://graph.facebook.com/v2.5/{$id}/photos?offset={$offset}&limit={$limit}&fields=id,source,name&".$access_token;

			$json1 = file_get_contents($json_links);
		

			$obj1 = json_decode($json1, true, 512, JSON_BIGINT_AS_STRING);
			 
			$photo_count = count($obj1['data']);

			if($returnType != 'json'){
				for($y=0; $y<$photo_count; $y++){				 
				    $source = isset($obj1['data'][$y]['source']) ? $obj1['data'][$y]['source'] : "";
				    $name = isset($obj1['data'][$y]['name']) ? $obj1['data'][$y]['name'] : "";
				 
			       	$result .= "<img src='".$source."'>";
				}
			}else{
				$result = $obj1['data'];
			}

		}
	}
	return $result;
}


$get_access_token=file_get_contents('https://graph.facebook.com/oauth/access_token?client_id=169677393071841&client_secret=7fb529df333cb84be2eb0e545efc079a&grant_type=client_credentials');

$access_token = explode('|', $get_access_token)[1];

//fetch facebook page photos

$fb_page_id = "304229246363923";

$access_token = $get_access_token;
$limit = 25;
$offset = 0;





$photos = getPhotos($fb_page_id, $access_token, $offset, $limit, 'json');



$videos = getVideos($fb_page_id, $access_token, $offset, $limit, 'json');



// print_r($photos);



$collections = array_merge($photos, $videos);

shuffle($collections);





?>

<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Blueprint: Google Grid Gallery</title>
		<meta name="description" content="Blueprint: Blueprint: Google Grid Gallery" />
		<meta name="keywords" content="google getting started gallery, image gallery, image grid, template, masonry" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/zoomwall.css" />

		<script src="js/zoomwall.js"></script>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<link href="http://vjs.zencdn.net/5.0.2/video-js.css" rel="stylesheet">

<script src="http://vjs.zencdn.net/5.0.2/video.js"></script>


	</head>
	<body>




<div id="zoomwall" class="zoomwall">


<?php 

$length = count($collections);

for($x=0; $x<$length; $x++)
{

	if(isset($collections[$x]['picture']))
	{

		echo '<img data-video="'.$collections[$x]['source'].'" class="video" src="'. $collections[$x]['picture'] . '" data-highres="' . $collections[$x]['picture'] . '" />';

?>



<?php



	}else{

		echo '<img src="'. $collections[$x]['source'] . '" data-highres="' . $collections[$x]['source'] . '" />';
	}


}

?>

    
</div>



<script type="text/javascript">
	
window.onload = function() {
    zoomwall.create(document.getElementById('zoomwall'));
};


</script>

</body>
</html>