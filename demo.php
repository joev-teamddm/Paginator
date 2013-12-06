<?php
//In your controller:
$all_events = new Event();
$all_events->find();

$results_per_page = 2;
$paginator = new Paginator($all_events, $results_per_page);
$events = $paginator->getPage($page_number);

//In your view:
foreach($events as $event){
	echo $event;
}
//print pagination links
//pass a url template in the form of '/events/page/:page' where :page is the placeholder for the page #
//if you leave it empty, it will assume a GET parameter of 'page'
$paginator->printNavigation('/events/page/:page');
