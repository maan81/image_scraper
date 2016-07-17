<?php
function retrive_urls($url){
	return [];
}
function scrape_urls($url){
	return [];
}
function error(){

}


$page_urls = [];
$img_urls = [];

if($_POST){

	$url = $_POST['url'];

	if (filter_var($url, FILTER_VALIDATE_URL) !== false){
		$urls = retrive_urls($url);
		foreach ($urls as $key => $value) {
			scrape_urls();
		}
	// }else{
	// 	error();
	}

}

?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>

	<div class="container">

		<br>

		<div class="row">


			<div class="alert alert-danger">
				Error retriving urls.
			</div>

 			<form class="form-inline" role="form">
 				<div class="form-group">
      				<label for="url">Url :</label>
    				<input type="text" class="form-control" id="url" name="url">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>

		<br>

		<div class="row">
			<div>
				<div>http://google.com</div>
				<div>http://google.com</div>
				<div>http://google.com</div>
				<div>http://google.com</div>
				<div>http://google.com</div>
				<div>http://google.com</div>
				<div>http://google.com</div>
				<div>http://google.com</div>
			</div>
		</div>

	</div>



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</body>
</html>