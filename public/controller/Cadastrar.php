<?php
class Cadastrar
{
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $inicio = new Template("public/view/cadastrar.html");
    $retorno["msg"] = $inicio->saida();
    return $retorno;
  }
  public function salvar()
  {

    if (isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["senha"])) {
      try {
        $conexao = Transaction::get();
        $nome = $conexao->quote($_POST["nome"]);
        $email = $conexao->quote($_POST["email"]);
        $senha = $conexao->quote(sha1($_POST["senha"]));
        $crud = new Crud();
        $retorno = $crud->insert(
          "usuario",
          "nome,email,senha",
          "{$nome},{$email},{$senha}"
        );
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