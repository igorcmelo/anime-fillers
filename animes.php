<?php 
	$conn = new mysqli("localhost", "site", "senha123", "anime");
	$sql = "SELECT * FROM animes ORDER BY (filler + mixed_canon) / total DESC";
	$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Fillers dos Animes</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
	<style type="text/css">

	* {
		font-family: roboto;
	}

	body {
		margin: 0;
		background-color: #CCC;
	}

	h1 {
		text-align: center;
		font-size: 40px;
		color: white;
	}

	h2 {
		font-size: 27px;
	}

	header {
		background-color: #05F;
		padding: 3px;
		margin-bottom: 10px;

	}

	div.anime {
		background-color: #FFF;
		margin: 10px;
		padding: 10px;
		box-shadow: 0 0 10px #0006;
	}

	.num {
		margin-right: 50px;
		font-weight: normal;
		font-size: 20px;
	}

	@media only screen and (max-width: 1000px) {
		.anime .imagem {
			width: 100%;
		}	
	}

	@media only screen and (min-width: 1001px) {

		div.anime {
			width: 635px;
			float: right;
			overflow: auto;
		}

		div.anime:nth-child(odd) {
			float: left;
		}

		.anime .imagem {
			float: left;
		}

		aside {
			float: right;
			margin-right: 20px;
			width: 60%;
		}


		.num {
			float: right;
		}

		h3 {
			font-size: 20px;
		}

	}

	</style>
</head>
<body>
	<header>
		<h1>Classificador de animes por quantidade de fillers</h1>
	</header>
	<?php 
	while($linha = $res->fetch_assoc()) {
		$nome = $linha["nome"];		
		$imagem = $linha["imagem"];		
		$filler = $linha["filler"];		
		$mixed = $linha["mixed_canon"];		
		$canon = $linha["manga_canon"];		
		$total = $linha["total"];		

		$perc_filler = $filler / $total * 100;
		$perc_mixed = $mixed / $total * 100;
		$perc_canon = $canon / $total * 100;
	?>
	<div class="anime">
		<img class="imagem" width="200" src="<?php echo "$imagem"; ?>">
		<aside>
			<h2><?php echo $nome; ?></h2>
			
			<h3>FILLER: <span class="num">
				<?php echo number_format($perc_filler, 0) . "% ($filler)" ?></span>
			</h3>

			<h3>MEIO FILLER: <span class="num">
				<?php echo number_format($perc_mixed, 0) . "% ($mixed)" ?></span>
			</h3>
		</aside>
	</div>
<?php } ?>
</body>
</html>