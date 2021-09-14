  
<?php
// auto load
spl_autoload_extensions(".php");
function classLoader($class)
{
  $nomeArquivo = $class . ".php";
  $pastas = array(
    "shared/controller",
    "shared/model",
    "public/controller"
  );
  foreach ($pastas as $pasta) {
    $arquivo = "{$pasta}/{$nomeArquivo}";
    if (file_exists($arquivo)) {
      require_once $arquivo;
    }
  }
}
spl_autoload_register("classLoader");

new Session;
Session::freeSession();

// Front Controller
class Aplicacao
{
  public static function run()
  {
    $layout = new Template("public/view/layout.html");
    $conteudo["msg"] = "Aula 01";
    if (!isset($_GET["acao"])) {
      $class = "Login";
    } else {
      $class = $_GET["acao"];
    }
    if (class_exists($class)) {
      $pagina = new $class;
      if (isset($_GET["metodo"])) {
        $metodo = $_GET["metodo"];
        if (method_exists($pagina, $metodo)) {
          $conteudo = $pagina->$metodo();
        }
      } else {
        $conteudo = $pagina->controller();
      }
    }
    $layout->set("conteudo", $conteudo["msg"]);
    echo $layout->saida();
  }
}

Aplicacao::run();
