<?php
class notebook{
	private $idnotebook;
	private $precio;
    private $foto;
	private $Color_idColor;
	private $Marca_idMarca;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
		
//*********************** 3.1 METODO update_notebook() **************************************************	
	
	public function update_notebook(){
		$this->idnotebook = $_POST['id'];
		$this->precio = $_POST['precio'];
			
		$this->Color_idColor = $_POST['Color_idColor'];
		$this->Marca_idMarca = $_POST['Marca_idMarca'];
		
		
		
		$sql = "UPDATE notebook SET precio='$this->precio',
									Color_idColor='$this->Color_idColor',
									Marca_idMarca='$this->Marca_idMarca'
				WHERE idnotebook=$this->idnotebook;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}								
										
	}
	

//*********************** 3.2 METODO save_vehiculo() **************************************************	

	public function save_notebook(){
		
		
		$this->idnotebook = $_POST['id'];
		$this->precio = $_POST['precio'];
			
		$this->Color_idColor = $_POST['Color_idColor'];
		$this->Marca_idMarca = $_POST['Marca_idMarca'];
		
		 
				echo "<br> FILES <br>";
				echo "<pre>";
					print_r($_FILES);
				echo "</pre>";
		     
		
		
		$this->foto = $this->_get_name_file($_FILES['foto']['name'],12);
		
		$path = "imagenesNotebook/" . $this->foto;
		
		//exit;
		if(!move_uploaded_file($_FILES['foto']['tmp_name'],$path)){
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}
		
		$sql = "INSERT INTO notebook VALUES(NULL,
											'$this->precio',
											'$this->foto',
											'$this->Color_idColor',
                                            '$this->Marca_idMarca');";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
		}								
										
	}


//*********************** 3.3 METODO _get_name_File() **************************************************	
	
	private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
		$cadena = "";
			for($i=1;$i<=$tamanio;$i++){
				$c = rand(65,122);
				if(($c >= 91) && ($c <=96)){
					$c = NULL;
					 $i--;
				 }else{
					$cadena .= chr($c);
				}
			}
		return $cadena . "." . $ext;
	}
	
	
//*************************************** PARTE I ************************************************************
	
	    
	 /*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_radio($arreglo,$nombre,$defecto){
		
		$html = '
		<table border=0 align="left">';
		
		//CODIGO NECESARIO EN CASO QUE EL USUARIO NO SE ESCOJA UNA OPCION
		
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
				
				if($defecto == NULL){
					// OPCION PARA GRABAR UN NUEVO VEHICULO (id=0)
					$html .= '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>';
				
				}else{
					// OPCION PARA MODIFICAR UN VEHICULO EXISTENTE
					$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>' : '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
				}
			
			$html .= '</tr>';
		}
		$html .= '
		</table>';
		return $html;
	}
	
	
//************************************* PARTE II ****************************************************	

	public function get_form($idnotebook=NULL){
		
		if($idnotebook == NULL){
			$this->precio = NULL;
			$this->foto = NULL;
			$this->Color_idColor =NULL;
            $this->Marca_idMarca =NULL;
			
			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT n.idnotebook, n.precio, n.foto, n.Color_idColor as Color, n.Marca_idMarca as Marca FROM notebook n WHERE idnotebook =$idnotebook ;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar el notebook con idnotebook = ".$idnotebook ;
                echo $this->_message_error($mensaje);
            }else{   
			
				$this->precio = $row['precio'];
				$this->foto = $row['foto'];
				$this->Color_idColor= $row['Color'];
                $this->Marca_idMarca = $row['Marca'];

				
				$flag = "disabled";
				$op = "update";
			}
		}
		
		$html = '
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Datos del Notebook</h4>
        </div>
        <div class="card-body">
            <form name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="' . $idnotebook . '">
                <input type="hidden" name="op" value="' . $op . '">

                <div class="mb-3">
                    <label class="form-label">Precio:</label>
                    <input type="text" class="form-control" name="precio" value="' . $this->precio . '" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto:</label>
                    <input type="file" class="form-control" name="foto" ' . '>
                </div>

                <div class="mb-3">
                    <label class="form-label">Color ID:</label>
                    <input type="text" class="form-control" name="Color_idColor" value="' . $this->Color_idColor . '" '  . ' required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Marca ID:</label>
                    <input type="text" class="form-control" name="Marca_idMarca" value="' . $this->Marca_idMarca . '" ' . ' required>
                </div>

                <div class="text-center">
                     <button type="submit" name="Guardar" class="btn btn-warning btn-lg">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>';
return $html;
	}
	
	

	public function get_list(){
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Lista de Notebooks</h4>
        </div>
        <div class="card-body">
            <div class="mb-3 text-end">
                <a href="index.php?d=' . $d_new_final . '" class="btn btn-success">Nuevo Notebook</a>
            </div>
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
						<th>ID</th>
                        <th>Precio</th>
                        <th colspan="3">Acciones</th>
                    </tr>
                </thead>
                <tbody>';
		$sql = "SELECT n.idnotebook, n.precio, n.foto  FROM notebook n, color c, marca m WHERE n.Color_idColor=c.idColor AND n.Marca_idMarca=m.idMarca;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['idnotebook'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['idnotebook'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['idnotebook'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
                    <tr>
						<td>' . $row['idnotebook'] . '</td>
                        <td>' . $row['precio'] . '</td>
                        <td><a href="index.php?d=' . $d_del_final . '" class="btn btn-danger btn-sm">Borrar</a></td>
                        <td><a href="index.php?d=' . $d_act_final . '" class="btn btn-warning btn-sm">Actualizar</a></td>
                        <td><a href="index.php?d=' . $d_det_final . '" class="btn btn-info btn-sm">Detalle</a></td>
                    </tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_notebook($idnotebook){
		$sql = "SELECT n.idnotebook, n.precio, n.foto, c.descripcion as Color , m.descripcion as Marca
                FROM notebook n, color c, marca m
				WHERE n.idnotebook=$idnotebook AND  n.Color_idColor=c.idColor AND n.Marca_idMarca=m.idMarca;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el vehiculo con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el notebook con id= ".$idnotebook;
            echo $this->_message_error($mensaje);
        }else{ 
            $html = '
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>DATOS DEL NOTEBOOK</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Precio:</strong></td>
                                <td>$' . $row['precio'] . ' USD</td>
                            </tr>
                            <tr class="text-center">
                                <th colspan="2">
                                    <img src="imagenesNotebook/' . $row['foto'] . '" class="img-fluid rounded shadow" width="300px"/>
                                </th>
                            </tr>
                            <tr>
                                <td><strong>Color:</strong></td>
                                <td>' . $row['Color'] . ' </td>
                            </tr>
                            <tr>
                                <td><strong>Marca:</strong></td>
                                <td>' . $row['Marca'] . ' </td>
                            </tr>
                        </table>
                        <div class="text-center mt-3">
                            <a href="index.php" class="btn btn-primary">Regresar</a>
                        </div>
                    </div>
                </div>
            </div>';
                            
            return $html;
		}
	}
	
	
	public function delete_notebook($idnotebook){
		$sql = "DELETE FROM notebook WHERE idnotebook=$idnotebook;";
			if($this->con->query($sql)){
			echo $this->_message_ok("ELIMINÓ");
		}else{
			echo $this->_message_error("eliminar");
		}	
	}
	
//*************************************************************************
	
//*************************************************************************	
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

