<!DOCTYPE html>
<html>
<head>
	<title>Principal</title>
</head>

<body>
	<fieldset>
		Produto
		<form method=get>
			Nome <input type=text name=produtoNome><br>
			Valor Venda <input type=text name=produtoPreco_venda maxlength=11><br>
			<input type=submit name=produtoCadastrar value=Cadastrar>
			<input type=submit name=produtoListar value=Listar>
		</form>
	</fieldset>

	<?php
		include_once "Produto.php";

		$p= new \produto\Produto($_GET['produtoNome'], $_GET['produtoPreco_venda']);

		if ( isset($_GET['produtoCadastrar']) )
		{
			$p->inserir($conexaodb);
			\produto\listar($conexaodb);
		}

		if ( isset($_GET['produtoListar']) )
			\produto\listar($conexaodb);

		//mysqli_close($conexaodb);
	?>

</body>
</html>