<?
	# DB connection and query
	
	// change this
	$conn = new mysqli("localhost", "site", "", "anime2");
	$conn->set_charset("utf8");
	
	// get animes info sorted by percentage of filler
	$sql = "SELECT * FROM animes ORDER BY filler / total DESC";
	$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Anime Fillers</title>
</head>

<body>
	<header>
		<h1 id="headerText">List of animes, ordered by percentage of fillers</h1>
	</header>

	<p id="note">
		Note: info extracted from 
		<a href="https://www.animefillerlist.com/">
			Anime Filler List
		</a>
	</p>

	<?
	while($line = $res->fetch_assoc()) {
		$name = $line["nome"];		
		$image = $line["imagem"];		
		$filler = $line["filler"];		
		$mixed = $line["mixed_canon"];		
		$canon = $line["manga_canon"];		
		$total = $line["total"];		

		$perc_filler = $filler / $total * 100;
		$perc_mixed = $mixed / $total * 100;
		$perc_canon = $canon / $total * 100;


		# amount of red and green (in rgb) to the percentage
		
		// amount of red 
		$r = intval($perc_filler * 255 / 100);

		// amount of green
		$g = intval((100 - $perc_filler) * 255 / 100);
	?>

	<div class="anime">
		<figure>
			<img class="imagem" width="200" height="300" src="<?= $image; ?>">
		</figure>

		<aside class="infoAnime">
			<h2 class="titleAnime"><?= $name; ?></h2>
			
			<h3 class="filler">
				<span class="num" style="color:<?= "rgb($r, $g, 50)"; ?>">
					<?= number_format($perc_filler, 0) . "%"?>
				</span>
			</h3>
		</aside>
	</div>
	<? 
		} // end of while 
	?>


	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="css/index.css">


	<script type="text/javascript">
		window.onresize = () => {
			console.log(window.innerWidth);
		};
	</script>
</body>
</html>