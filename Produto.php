<?php namespace produto;

use PDO;

	include_once 'db.inc';

	class Produto
	{
		private $id, $nome, $preco_venda;

		function __construct(...$parametros)
		{
			switch ( count($parametros) )
			{
				case 0:
					break;

				case 2:
					$this->nome= $parametros[0];
					$this->preco_venda= $parametros[1];
					break;

				case 3:
					$this->id= $parametros[0];
					$this->nome= $parametros[1];
					$this->preco_venda= $parametros[2];
					break;

				default:
					echo "Objeto criado com quantidade incompatível de parâmetros";
					break;
			}
		}

		function __set($atributo, $valor)
		{
			$this->$atributo= $valor;
		}

		function __get($atributo)
		{
			return $this->$atributo;
		}

		public function inserir($conexaodb)
		{
			// mysqli
			/*$conexaodb->query("INSERT INTO PRODUTO (nome, preco_venda) VALUES ('{$conexaodb->real_escape_string($this->nome)}', {$conexaodb->real_escape_string($this->preco_venda)})") or die(mysqli_error($conexaodb));*/

			// pdo
			$conexaodb->prepare("INSERT INTO PRODUTO (nome, preco_venda) VALUES ('{$this->nome}', {$this->preco_venda})")->execute();

			echo "Inserido com sucesso<br>";
		}
	}


?>	<form method=get>
		<div>
<?php
	function listar($conexaodb)
	{
		// mysqli
		//$consulta= $conexaodb->query("select * from PRODUTO") or die(mysqli_error($conexaodb));

		// pdo
		$consulta= $conexaodb->query("select * from PRODUTO order by id");

		// mysqli
		//while ( $item = $consulta->fetch_assoc() )

		// pdo
		foreach ( $consulta->fetchAll(PDO::FETCH_ASSOC) as $item )
		{ ?>
				<input type=checkbox style="min-width: 10PX;" name=selecaoProdutos[] value=<?=$item['id']?>>
			<form method=get style="display:inline-block;">
				<input type=text name=idProduto value=<?=$item['id']?> readonly>
				<input type=text name=nomeProduto value=<?=$item['nome']?>>
				<input type=text name=preco_vendaProduto value=<?=$item['preco_venda']?>>
				<input type=submit name=editarProduto value=Editar>
				<input type=submit name=deletarProduto value=Deletar>
			</form> <br>
	<?php } ?>

		<input type=submit name=excluirSelecaoProdutos value='Excluir Selecionados'>
		</div>

<?php } ?>

	</form>

<?php
	if ( isset($_GET['excluirSelecaoProdutos']) )
	{
		foreach ($_GET['selecaoProdutos'] as $selecionados )
		{
			// mysqli
			//$conexaodb->query("delete from PRODUTO where id = {$conexaodb->real_escape_string($selecionados)}");

			// pdo
			$conexaodb->prepare("delete from PRODUTO where id = {$selecionados}")->execute();

		}

		listar($conexaodb);
	}

	//if ( idfuncao == (dono || vendedor) )
	elseif ( isset($_GET['deletarProduto']) )
	{
		// mysqli
		//$conexaodb->query("delete from PRODUTO WHERE id = {$conexaodb->real_escape_string($_GET['idProduto'])}");

		// pdo
		$conexaodb->prepare("delete from PRODUTO WHERE id = {$_GET['idProduto']}")->execute();

		listar($conexaodb);
	}

	elseif ( isset($_GET['editarProduto']) )
	{
		// mysqli
		//$conexaodb->query("update PRODUTO set nome = '{$conexaodb->real_escape_string($_GET['nomeProduto'])}' where id = {$conexaodb->real_escape_string($_GET['idProduto'])}");

		// pdo
		$conexaodb->prepare("update PRODUTO set nome = '{$_GET['nomeProduto']}' where id = {$_GET['idProduto']}")->execute();

		listar($conexaodb);
	}
?>