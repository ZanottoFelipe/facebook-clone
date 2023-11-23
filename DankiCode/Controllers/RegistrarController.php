<?php
namespace DankiCode\Controllers;

class RegistrarController{

	public function index(){
		if(isset($_POST['registrar'])){
			$nome = $_POST['nome'];
			$email = $_POST['email'];
			$senha = $_POST['senha'];

			if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
				\DankiCode\Utilidades::alerta('E-mail invalido!');
				\DankiCode\Utilidades::redirect(INCLUDE_PATH.'registrar');
			}
			else if (strlen($senha) < 6){
				\DankiCode\Utilidades::alerta('Senha precisa de 6 caracteres!');
				\DankiCode\Utilidades::redirect(INCLUDE_PATH.'registrar');
			}

			else if(\DankiCode\Models\UsuariosModel::emailExists($email)){
				\DankiCode\Utilidades::alerta('Este E-mail já esta registrado!');
				\DankiCode\Utilidades::redirect(INCLUDE_PATH.'registrar');
			}
			else{
				$senha = \DankiCode\Bcrypt::hash($senha);
				$registro = \DankiCode\MySql::connect()->prepare("INSERT INTO usuarios VALUES (null,? , ?, ?, '','')");
				$registro->execute(array($nome, $email, $senha));

				\DankiCode\Utilidades::alerta('Registrado com sucesso!');
				\DankiCode\Utilidades::redirect(INCLUDE_PATH);
			}
		}
		\DankiCode\Views\MainViews::render('registrar');	
	}
}



?>