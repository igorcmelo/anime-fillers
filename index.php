<?
	# Conexão ao BD e query
	
	// altere esses dados caso necessário
	$conn = new mysqli("localhost", "site", "", "anime");
	$conn->set_charset("utf8");
	
	// pega os dados em order decrescente de % de fillers
	$sql = "SELECT * FROM animes ORDER BY filler / total DESC";
	$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fillers dos Animes</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
	<header>
		<h1 id="headerTexto">Lista de porcentagem de fillers dos animes</h1>
	</header>

	<?
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


		# cores que irão aparecer na porcentagem de filler (em rgb)

		// quantidade de vermelho 
		$r = intval($perc_filler * 255 / 100);

		// quantidade de verde 
		$g = intval((100 - $perc_filler) * 255 / 100);
	?>

	<div class="anime">
		<img class="imagem" width="200" height="300" src="<?= "$imagem"; ?>">
		<aside class="infoAnime">
			<h2 class="tituloAnime"><?= $nome; ?></h2>
			
			<h3 class="filler">
				<span class="num" style="color:<?= "rgb($r, $g, 50)"; ?>">
					<?= number_format($perc_filler, 0) . "%"?>
				</span>
			</h3>
		</aside>
	</div>
<? 
	} // fim do while 
?>
</body>
</html>