<?php

namespace DankiCode\Models;


class UsuariosModel{

	public static function emailExists($email){

		$pdo = \DankiCode\MySql::connect();
		$verifica = $pdo->prepare("SELECT email FROM usuarios WHERE email = ?");
		$verifica->execute(array($email));


		if($verifica->rowCount() == 1){
			return true;
		}else{
			return false;
		}
	}



	public static function listarComunidade(){

		$pdo = \DankiCode\MySql::connect();

		$comunidade = $pdo->prepare("SELECT * FROM usuarios");

		$comunidade->execute();

		return $comunidade->fetchAll();
	}



	public static function solicitarAmizade($idPara){


		$pdo = \DankiCode\MySql::connect();


		$verificaAmizade = $pdo->prepare("SELECT * FROM amizades WHERE (enviou = ? AND recebeu = ?) OR (enviou = ? AND recebeu = ?)");
		$verificaAmizade->execute(array($_SESSION['id'],$idPara,$idPara,$_SESSION['id']));

		if ($verificaAmizade->rowCount() == 1) {
			return false;
		}else{
			$inserirAmizade = $pdo->prepare("INSERT INTO amizades VALUES (null, ?, ?, 0)");

			if ($inserirAmizade->execute(array($_SESSION['id'], $idPara))) {
				return true;
			}
		}

		return true;
	}


	public static function listarAmizadesPendentes(){

		$pdo = \DankiCode\MySql::connect();

		$listarAmizadesPendentes = $pdo->prepare("SELECT * FROM amizades WHERE recebeu = ? AND status = 0");	

		$listarAmizadesPendentes->execute(array($_SESSION['id']));

		return $listarAmizadesPendentes->fetchAll();

	}


	public static function getUsuarioById($id){

		$pdo = \DankiCode\MySql::connect();

		$usuario = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");	

		$usuario->execute(array($id));

		return $usuario->fetch();

	}





	public static function existePedidoAmizade($idPara){

		$pdo = \DankiCode\MySql::connect();


		$verificaAmizade = $pdo->prepare("SELECT * FROM amizades WHERE (enviou = ? AND recebeu = ?) OR (enviou = ? AND recebeu = ?)");
		$verificaAmizade->execute(array($_SESSION['id'],$idPara,$idPara,$_SESSION['id']));

		if ($verificaAmizade->rowCount() == 1) {
			return false;
		}else{
			return true;			
		}

	}


	public static function atualizarPedidoAmizade($enviou, $status){

		$pdo = \DankiCode\MySql::connect();

		if ($status == 0) {
			// recusou o pedido de amizade
			$del = $pdo->prepare("DELETE FROM amizades WHERE enviou = ? AND recebeu = ? AND status = 0");
			$del->execute(array($enviou, $_SESSION['id']));

		}elseif ($status == 1) {
			// aceitou o pedido 
			$aceitarPedido = $pdo->prepare("UPDATE amizades SET status = 1 WHERE enviou = ? AND recebeu = ?");
			$aceitarPedido->execute(array($enviou, $_SESSION['id']));

			if ($aceitarPedido->rowCount() == 1) {
				return true;
			}else{
				return false; 
			}

		}	

	}

	public static function listarAmigos(){

		$pdo = \DankiCode\MySql::connect();

		$amizades = $pdo->prepare("SELECT * FROM amizades WHERE (enviou = ? AND status = 1) OR (recebeu = ? AND status = 1)");
		$amizades->execute(array($_SESSION['id'], $_SESSION['id']));


		$amizades = $amizades->fetchAll();

		$amigoConfirmados = array();

		foreach ($amizades as $key => $value) {
			if ($value['enviou'] == $_SESSION['id']) {
				$amigoConfirmados[] = $value['recebeu'];
			}else{
				$amigoConfirmados[] = $value['enviou'];
			}
		}


		$listaAmigos = array();

		foreach ($amigoConfirmados as $key => $value) {
			$listaAmigos[$key]['nome'] = self::getUsuarioById($value)['nome'];
			$listaAmigos[$key]['email'] = self::getUsuarioById($value)['email'];
			$listaAmigos[$key]['img'] = self::getUsuarioById($value)['img'];

		}

		return $listaAmigos;	

	}








}
?>