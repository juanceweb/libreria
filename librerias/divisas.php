<?php

if (isset($_REQUEST["debug"])) {
	$debug=$_REQUEST["debug"];
}else{
	$debug=FALSE;
}

if (isset($_REQUEST["html"])) {
	$html=$_REQUEST["html"];
}else{
	$html=FALSE;
}

if ($debug) {	
	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);
	ini_set('xdebug.var_display_max_data', -1);
}

$url = "https://www.dolarsi.com/api/api.php?";

switch ($_GET["accion"]) {

	case 'monedas':

		//Cotización de las distintas monedas

		$get="type=cotizador";
		$respuesta = file_get_contents($url.$get);
		$respuestaarray = json_decode($respuesta);

		if (isset($_GET["id"])) {
			$respuestaarray = $respuestaarray[$_GET["id"]];
			$respuesta = json_encode($respuestaarray);
		}

		if ($debug) {

			echo "Cotización de Monedas:";
			var_dump($respuestaarray);			
		
		}

		if ($html) {
			
			?>
	
				<table class="table ewTable">
					<thead>
						<tr>
							<th>ID</th>						
							<th>Denominación</th>
							<th>Compra</th>
							<th>Venta</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if (count((array)$respuestaarray) > 1) {							
								foreach ($respuestaarray as $key => $value) {
									?>
										<tr>
											<td><?php echo $key ?></td>
											<td><?php echo $value->casa->nombre ?></td>
											<td><?php echo $value->casa->compra ?></td>
											<td><?php echo $value->casa->venta ?></td>
										</tr>
									<?php
								}
							}else{
									?>
									<tr>
										<td><?php echo $_GET["id"] ?></td>
										<td><?php echo $respuestaarray->casa->nombre ?></td>
										<td><?php echo $respuestaarray->casa->compra ?></td>
										<td><?php echo $respuestaarray->casa->venta ?></td>
									</tr>
								<?php								
							}
						?>
					</tbody>
				</table>

			<?php

		}else{
			echo $respuesta;
		}


	break;

	case 'dolar':

		//Diferentes cotizaciones de dolar

		$get="type=valoresprincipales";
		$respuesta = file_get_contents($url.$get);
		$respuestaarray = json_decode($respuesta);

		//Diferentes cotizaciones de dolar según banco

		$get="type=capital";
		$respuesta2 = file_get_contents($url.$get);
		$respuesta2array = json_decode($respuesta2);

		$respuestaarray = array_merge($respuestaarray, $respuesta2array);

		unset($respuestaarray[2]);
		unset($respuestaarray[5]);

		$respuesta = json_encode($respuestaarray);

		if (isset($_GET["id"])) {
			$respuestaarray = $respuestaarray[$_GET["id"]];
			$respuesta = json_encode($respuestaarray);
		}		

		if ($debug) {

			echo "Diferentes cotizaciones de dolar:";
			var_dump($respuestaarray);			
		
		}

		if ($html) {
			
			?>
	
				<table class="table ewTable">
					<thead>
						<th>ID</th>						
						<th>Denominación</th>
						<th>Compra</th>
						<th>Venta</th>
					</thead>
					<tbody>
						<?php
							if (count((array)$respuestaarray) > 1) {							
								foreach ($respuestaarray as $key => $value) {
									?>
										<tr>
											<td><?php echo $key ?></td>
											<td><?php echo $value->casa->nombre ?></td>
											<td><?php echo $value->casa->compra ?></td>
											<td><?php echo $value->casa->venta ?></td>
										</tr>
									<?php
								}
							}else{
									?>
									<tr>
										<td><?php echo $_GET["id"] ?></td>
										<td><?php echo $respuestaarray->casa->nombre ?></td>
										<td><?php echo $respuestaarray->casa->compra ?></td>
										<td><?php echo $respuestaarray->casa->venta ?></td>
									</tr>
								<?php								
							}
						?>
					</tbody>
				</table>

			<?php

		}else{
			echo $respuesta;
		}


	break;		
	
	default:
		echo "No especifica ninguna acción";	
	break;
}

?>