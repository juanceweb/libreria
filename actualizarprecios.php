<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file" accept=".csv" />
	<input type="submit" name="submit" />
</form>

<?php 

$phpFileUploadErrors = array(
    0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
);

if ( isset($_POST["submit"]) ) {

	if ( isset($_FILES["file"])) {

		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $phpFileUploadErrors[$_FILES["file"]["error"]] . "<br />";
		}
		else {

			$ext = end((explode(".", $_FILES["file"]["name"])));

			if ($ext == "csv") {

				echo "Upload: " . $_FILES["file"]["name"] . "<br />";
				echo "Type: " . $_FILES["file"]["type"] . "<br />";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";

				$storagename = uniqid().".csv";
				move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				echo "Guardado como: " . "upload/" . $storagename . "<br />";

				$fila = 1;
				$registros = array();
				$codigos = array();

				if (($gestor = fopen("upload/" . $storagename, "r")) !== FALSE) {
				    while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {

			        $fila++;
			        $registros[$datos[0]] = $datos[1];
			        array_push($codigos, $datos[0]);

				    }

				  fclose($gestor);

				  //var_dump($registros);

				  $sql="SELECT `articulos-proveedores`.codExterno,
					  monedas.simbolo,
					  `articulos-proveedores`.precio,
					  terceros.denominacion,
					  `articulos-proveedores`.precioPesos,
					  `articulos-proveedores`.ultimaActualizacion,
					  articulos.denominacionExterna,
					  articulos.denominacionInterna,
					  articulos.calculoPrecio,
					  articulos.rentabilidad,
					  articulos.precioVenta
					FROM articulos
					  INNER JOIN `articulos-proveedores`
					    ON articulos.id = `articulos-proveedores`.idArticulo
					  INNER JOIN monedas ON `articulos-proveedores`.idMoneda = monedas.id
					  INNER JOIN terceros ON terceros.id = `articulos-proveedores`.idTercero
					  WHERE `articulos-proveedores`.codExterno IN('".implode("','", $codigos)."')";

					echo $sql; 

				}

				/*				

				?>
				<table>
					<thead>
						<tr>
							<th>
								Código
							</th>
							<th>
								Importe
							</th>
						</tr>
					</thead>
					<tbody>
						<?php 

							$fila = 1;
							if (($gestor = fopen("upload/" . $storagename, "r")) !== FALSE) {
							    while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {

							        ?>
							        <tr>
							        	<?php 

									        $fila++;

									        for ($c=0; $c < count($datos); $c++) {
									        		?>
									        		<td><?php echo $datos[$c] ?></td>
									        		<?php
									        }

							        	?>
							        </tr>
							      <?php

							    }

							  fclose($gestor);
							}				

						?>
					</tbody>
				</table>

				<?php
			*/
			}else{
				echo "Solo se permiten archivos con extensión csv";
			}

		}
	} else {
		echo "No se seleccionó ningún archivo <br />";
	}
}

?>

