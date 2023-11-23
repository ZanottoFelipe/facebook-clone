<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Gantari:wght@400;600&family=Sora:wght@200;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo INCLUDE_PATH_STATIC ?>estilos/style.css">
</head>
<body>
	<div class="sidebar"></div>

	<div class="form-container-login">
		<div class="logo-chamada-login">

			<img src="<?php echo INCLUDE_PATH_STATIC ?>images/logo-curto-branco.jpeg">
			<p>Cadastre-se e receba descontos e promoções, fique por dentro de todas as novidades.</p>

		</div>
	
	
		<div class="form-login">
			
			<form method="post">
				<input type="text" name="email" placeholder="E-mail" required>
				<input type="password" name="senha" placeholder="Senha" required>
				<input type="submit" name="acao" value="Logar">
				<input type="hidden" name="login">
			</form>
			<p><a href="<?php echo INCLUDE_PATH ?>registrar">Criar conta</a></p>

		</div>
	</div>


	

</body>
</html>