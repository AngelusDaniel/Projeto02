<?php
class Login
{
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $inicio = new Template("public/view/login.html");
    $retorno["msg"] = $inicio->saida();
    return $retorno;
  }
  public function salvar()
  {

    if (isset($_POST["email"]) && isset($_POST["senha"])) {
      try {
        $conexao = Transaction::get();
        $email = $conexao->quote($_POST["email"]);
        $senha = $conexao->quote(sha1($_POST["senha"]));
        $crud = new Crud();
        $retorno = $crud->select(
          "usuario",
          "id, nome",
          "email={$email} AND senha={$senha}"
        );
        if (!$retorno["erro"]) {
          new Session;
          Session::setValue("id", $retorno["msg"][0]["id"]);
          Session::setValue("nome", $retorno["msg"][0]["nome"]);
          header("Location:restrita.php");
        }
      } catch (Exception $e) {
        $retorno["msg"] = "Ocorreu um erro! " . $e->getMessage();
        $retorno["erro"] = TRUE;
      }
    } else {
      $retorno["msg"] = "Preencha todos os campos! ";
      $retorno["erro"] = TRUE;
    }
    $retorno["msg"] .= "<br /> <a href='?'>Voltar</a>";
    return $retorno;
  }

  public function __destruct()
  {
    Transaction::close();
  }
}