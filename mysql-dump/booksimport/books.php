<?php
$today = date("d-m-y-Y-G-i");
$filename = "books-${today}.json";
header( 'Pragma: public' ); // required
header( 'Expires: 0' );
header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
header( 'Cache-Control: private', false );
header( 'Content-Type: application/json' );
header('Content-Disposition: attachment; filename="'.$filename.'"');
header( 'Content-Transfer-Encoding: binary' );

if ($_FILES["inputFile"]["error"] > 0) {
	die('Error');
	return;
}
$content = file_get_contents($_FILES['inputFile']['tmp_name']); 
$books = json_decode($content, true);
$category = $_REQUEST['category'];
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
