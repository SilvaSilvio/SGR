<?php namespace produto;

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
			$conexaodb->query("INSERT INTO PRODUTO (nome, preco_venda) VALUES ('{$conexaodb->real_escape_string($this->nome)}', {$conexaodb->real_escape_string($this->preco_venda)})") or die(mysqli_error($conexaodb));

			echo "Inserido com sucesso";
		}
	}


?>	<form action=index.php method=get>
		<div>
<?php
	function listar($conexaodb)
	{
		$consulta= $conexaodb->query("select * from PRODUTO") or die(mysqli_error($conexaodb));

		while ( $item = $consulta->fetch_assoc() )
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

		<input type=submit name=excluirSelecao value='Excluir Selecionados'>
		</div>

<?php } ?>

	</form>

<?php
	if ( isset($_GET['excluirSelecao']) )
	{
		foreach ($_GET['selecaoProdutos'] as $selecionados )
	-		$conexaodb->query("delete from PRODUTO where id = {$conexaodb->real_escape_string($selecionados)}");

		listar($conexaodb);
	}

	//if ( idfuncao == (dono || vendedor) )
	if ( isset($_GET['deletarProduto']) )
	{
		$conexaodb->query("delete from PRODUTO WHERE id = {$conexaodb->real_escape_string($_GET['idProduto'])}");

		listar($conexaodb);
	}

	if ( isset($_GET['editarProduto']) )
	{
		$conexaodb->query("update PRODUTO set nome = '{$_GET['nomeProduto']}' where id = {$_GET['idProduto']}");

		listar($conexaodb);
	}
?>