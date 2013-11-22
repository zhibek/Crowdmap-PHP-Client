<?php
require('lib/Crowdmap.php');

include('config.php');

if (isset($_REQUEST['subdomain'])) {
    $mapSubDomain = $_REQUEST['subdomain'];
} else {
    throw new Exception('Querystring value "subdomain" required - i.e. ?subdomain=turkanaeclipse');
}

$Crowdmap = new Crowdmap($applicationPublicKey, $applicationPrivateKey);

$mapResult = $Crowdmap->call('GET', '/maps/' . $mapSubDomain);
if (!$mapResult->success || !count($mapResult->maps)) {
    throw new Exception(sprintf('Could not find map with subdomain "%s"', $mapSubDomain));
}
$mapItem = $mapResult->maps[0];

$mapPostsGeoJsonResult = $Crowdmap->call('GET', '/maps/' . $mapSubDomain . '/posts', array('format' => 'geojson'));
$mapPostsGeoJsonOutput = json_encode($mapPostsGeoJsonResult);

$mapPostsResult = $Crowdmap->call('GET', '/maps/' . $mapSubDomain . '/posts');
$mapPosts = $mapPostsResult->posts;

$mapTagsResult = $Crowdmap->call('GET', '/maps/' . $mapSubDomain . '/tags');
$mapTags = $mapTagsResult->maps_tags;

include('index.phtml');