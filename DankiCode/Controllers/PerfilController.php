<?php 
namespace DankiCode\Controllers;
class PerfilController{



	public function index(){

		if (isset($_SESSION['login'])) {


			if (isset($_POST['atualizar'])) {
				$pdo = \DankiCode\MySql::connect();
				$nome = strip_tags($_POST['nome']);
				$senha = strip_tags($_POST['senha']);


				if ($nome ==  '' || strlen($nome) < 3) {
					\DankiCode\Utilidades::alerta('Preencha seu nome com mais de 3 caracteres.');
					\DankiCode\Utilidades::redirect(INCLUDE_PATH.'perfil');
				}

				if ($senha != '') {
					$senha = \DankiCode\Bcrypt::hash($senha);
					$atualizar = $pdo->prepare("UPDATE usuarios SET nome = ?, senha = ? WHERE id = ?");
					$atualizar->execute(array($nome, $senha, $_SESSION['id']));
					$_SESSION['nome'] = $nome;
			
				}else{
					$atualizar = $pdo->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
					$atualizar->execute(array($nome, $_SESSION['id']));
					$_SESSION['nome'] = $nome;
				

				}


				if ($_FILES['file']['tmp_name'] != '') {
					$file = $_FILES['file'];
					$fileExt = explode('.',$file['name']);
					$fileExt = $fileExt[count($fileExt) -1];
					if ($fileExt == 'png' || $fileExt == 'jpeg' || $fileExt == 'jpg'){
						$size = intval($file['size'] / 1024);
						if ($size <= 300) {
							$uniqid = uniqid().'.'.$fileExt;
							$atualizaImagem = $pdo->prepare("UPDATE usuarios SET img = ? WHERE id = ?");
							$atualizaImagem->execute(array($uniqid,$_SESSION['id']));
							$_SESSION['img'] = $uniqid;
							move_uploaded_file($file['tmp_name'], 'C:\xampp\htdocs\redesocialdevweb20\DankiCode\Views\Pages\uploads/'.$uniqid);
							\DankiCode\Utilidades::alerta('Perfil e Foto atualizados.');
							\DankiCode\Utilidades::redirect(INCLUDE_PATH.'perfil');




						}else{
							\DankiCode\Utilidades::alerta('Erro ao processar seu arquivo(provavelmente o tamnho).');
							\DankiCode\Utilidades::redirect(INCLUDE_PATH.'perfil');
						}
					}else{
						\DankiCode\Utilidades::alerta('Erro ao prcessar seu arquivo(provavelmente arquivo incopaivel).');
						\DankiCode\Utilidades::redirect(INCLUDE_PATH.'perfil');
					}


				}


				\DankiCode\Utilidades::alerta('Seu nome do perfil foi atualizado com seucesso.');
				\DankiCode\Utilidades::redirect(INCLUDE_PATH.'perfil');


			}


			\DankiCode\Views\MainViews::render('perfil');
		}else{
			\DankiCode\Utilidades::redirect('INCLUDE_PATH');
		}
		
	}
}

 ?> 