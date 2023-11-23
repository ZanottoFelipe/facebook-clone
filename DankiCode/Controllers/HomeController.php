<?php
namespace DankiCode\Controllers;

class HomeController{

	public function index(){

		if (isset($_GET['loggout'])) {
			session_unset();
			session_destroy();
			\DankiCode\Utilidades::redirect(INCLUDE_PATH);

		}

		if (isset($_SESSION['login'])){

			if (isset($_GET['recusarAmizade'])) {
				$idEnviou = (int) $_GET['recusarAmizade'];
				\DankiCode\Models\UsuariosModel::atualizarPedidoAmizade($idEnviou,0);
				\DankiCode\Utilidades::alerta('Pedido Recusado!');
				\DankiCode\Utilidades::redirect(INCLUDE_PATH);
 			}elseif (isset($_GET['aceitarAmizade'])){
 				$idEnviou = (int) $_GET['aceitarAmizade'];
				if(\DankiCode\Models\UsuariosModel::atualizarPedidoAmizade($idEnviou,1)){
					\DankiCode\Utilidades::alerta('Pedido Aceito!');
					\DankiCode\Utilidades::redirect(INCLUDE_PATH);
				}else{
					\DankiCode\Utilidades::alerta('Ocorreu um erro!');
					\DankiCode\Utilidades::redirect(INCLUDE_PATH);
				}

 			}


 			if(isset($_POST['post_feed'])){

 				if ($_POST['post_cotent'] == '') {

 					\DankiCode\Utilidades::alerta('Post não pode ficar vazio!');
					\DankiCode\Utilidades::redirect(INCLUDE_PATH);

 				}else{

 					\DankiCode\Models\homeModel::postFeed($_POST['post_cotent']);
 					\DankiCode\Utilidades::alerta('Post feito com sucesso!');
					\DankiCode\Utilidades::redirect(INCLUDE_PATH);
				}
 			}






			// renderizo a home do usuario pagina
			\DankiCode\Views\MainViews::render('home');
		}else{
			// renderizar para criar conta


			if (isset($_POST['login'])){
				$login = $_POST['email'];
				$senha = $_POST['senha'];


				$verifica = \DankiCode\MySql::connect()->prepare("SELECT * FROM usuarios WHERE email = ?");
				$verifica->execute(array($login));

				if ($verifica->rowCount() == 0) {
					\DankiCode\Utilidades::alerta('E-mail não existe!');
					\DankiCode\Utilidades::redirect(INCLUDE_PATH);
				}else{

					$dados = $verifica->fetch();
					$senhaBanco = $dados['senha'];
					if (\DankiCode\Bcrypt::check($senha,$senhaBanco)) {
						$_SESSION['login'] = $dados['email'];
						$_SESSION['id'] = $dados['id'];
						$_SESSION['nome'] = explode(' ', $dados['nome'])[0];
						$_SESSION['img'] = $dados['img'];
						\DankiCode\Utilidades::alerta('Logado com sucesso');
						\DankiCode\Utilidades::redirect(INCLUDE_PATH);
						
					}else{						
						\DankiCode\Utilidades::alerta('Senha errada');
						\DankiCode\Utilidades::redirect(INCLUDE_PATH);
					}
				}
			}		

			\DankiCode\Views\MainViews::render('login');

		}
	}
}

?>