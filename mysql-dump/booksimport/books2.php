<?php
$today = date("d-m-y-Y-G-i");
$category = $_REQUEST['category'];
$filename = "books-${category}-${today}.json";
header( 'Pragma: public' ); // required
header( 'Expires: 0' );
header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
header( 'Cache-Control: private', false );
header( 'Content-Type: application/json' );
header('Content-Disposition: attachment; filename="'.$filename.'"');
header( 'Content-Transfer-Encoding: binary' );

$maxResults = $_REQUEST['max_res'];
$query = urlencode($_REQUEST['query']);
$url = 'https://www.googleapis.com/books/v1/volumes?q='.$query.'&maxResults='.$maxResults.'&printType=books&fields=items(id%2CvolumeInfo(authors%2Ccategories%2Cdescription%2CimageLinks%2CindustryIdentifiers%2CinfoLink%2Clanguage%2CpageCount%2CpreviewLink%2CpublishedDate%2Cpublisher%2Csubtitle%2Ctitle))&pp=1&key=AIzaSyCxL2Dcd5pntzQtDYz73MF2Z-b8Xfj_wL4';
$content = file_get_contents($url); 
$books = json_decode($content, true);
$output = array();
foreach ($books['items'] as $item) {
	$book = array();
	$book['guid'] = $item['id'];
	$book['title'] = $item['volumeInfo']['title'];
	$book['authors'] = implode(', ', $item['volumeInfo']['authors']);
	if (isset($item['volumeInfo']['publisher'])) {
		$book['publisher'] = $item['volumeInfo']['publisher'];
	} else {
		$book['publisher'] = '';
	}
	$book['published_date'] = date('d.m.Y', strtotime($item['volumeInfo']['publishedDate']));
	if (isset($item['volumeInfo']['description'])) {
		$book['description'] = $item['volumeInfo']['description'];
	} else {
		$book['description'] = '';
	}
	
	$book['isbn'] = $item['volumeInfo']['industryIdentifiers'][0]['identifier'];
	if (isset($item['volumeInfo']['pageCount'])) {
		$book['page_count'] = $item['volumeInfo']['pageCount'];
	} else {
		$book['page_count'] = '';
	}
	$book['categories'] = $category;//implode(', ', $item['volumeInfo']['categories']);
	$book['thumbnail'] =  decodeUrl($item['volumeInfo']['imageLinks']['thumbnail']);
	if (empty($book['thumbnail'])) continue;
	$book['language'] =  $item['volumeInfo']['language'];
	$book['preview_link'] = decodeUrl($item['volumeInfo']['previewLink']);
	$book['info_link'] = decodeUrl($item['volumeInfo']['infoLink']);
	//$book['cover_image'] = 'TOOD!';
	array_push($output, $book);
}
$json_output = json_encode(array('books' => $output));
echo getPrettyJson($json_output);

function decodeUrl($url) {
	return utf8_decode(urldecode($url));
}

function getPrettyJson($jsonString) {
	$pattern = array(',"', '{', '}');
	$replacement = array(",\n\t\"", "{\n\t", "\n}");
	return str_replace($pattern, $replacement, $jsonString);
}
