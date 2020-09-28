<?php
	// error_reporting(E_ALL);
	ini_set("display_errors", 0);

	require 'vendor/autoload.php';

	$scrapResult = null;
	$baseUrl = null;

	if(isset($_POST['submit'])) {
		$baseUrl = $_POST['domain_url'];


		if($baseUrl != '') {
			$baseUrl = rtrim($baseUrl,'/');
			$url = $baseUrl.'/collections/all/products.json?sort-by=best-selling';
			// $url = $baseUrl;

			try {
			    $client = new GuzzleHttp\Client();

				$res = $client->request('GET', $url);
				// echo $res->getStatusCode();
				// echo $res->getHeader('content-type');
				$scrapResult = $res->getBody();

			} catch(Exception $e) {

			    trigger_error(sprintf(
			        'Client failed with error #%d: %s',
			        $e->getCode(), $e->getMessage()),
			        E_USER_ERROR);
			}
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Scrap Demo</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<form action="index.php" method="POST">
					<h3>Enter Shopify Website:</h3>
					<input type="text" name="domain_url" value="<?php echo $baseUrl ? $baseUrl : ''; ?>" class="form-control" required placeholder="Enter shopify domain URL"><br>
					<button type="submit" name="submit" class="btn btn-info">Scrap best sellers</button>
				</form>
			</div>
		</div>
		<hr>

		<?php if($scrapResult) { ?>
			
			<center>
				<h2>Best selling products Result</h2>
				<p>Website : <?php echo $baseUrl; ?></p>
			</center>
			<hr>
	
			<div class="row">
				<?php
				$scrapResult = json_decode($scrapResult);
				foreach ($scrapResult->products as $key => $value) {
				?>

					<div class="col-md-3 col-sm-4 col-xs-6" style="margin: 20px 0; border:1px;">
						<div style="border: 1px solid #eee; box-shadow: 0px 2px 1px #ccc;">
							<img src="<?php echo $value->images[0]->src ?>" class="img-responsive">
							<p class="text-center"><b><?php echo $value->title; ?></b></p>
							<p class="text-center"><?php echo $value->product_type; ?></p>
							<p class="text-center">$ <?php echo $value->variants[0]->price; ?></p>
						</div>
					</div>
				
				<?php } ?>
			</div>

		<?php } ?>

		<!-- This script is for scrapping country data from https://restcountries.eu/rest/v2/all -->
		<!--
		<?php if($scrapResult) { ?>
			<?php
			var_dump($scrapResult);die();
			foreach ($scrapResult as $key => $value) {
				echo "[ \"code\" => \"". strtolower($value->alpha2Code) . "\", \"name\" => \"". $value->name . "\", \"alpha3_code\" => \"". $value->alpha3Code . "\", \"capital\" => \"". $value->capital . "\"],";
				echo "<br>";
			} 
			?>
		<?php } ?>
		-->
	</div>
</body>
</html>