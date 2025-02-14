<?php
class color{
	private $idColor;
	private $descripcion;
	private $con;
	
	
	function __construct($cn){
		$this->con = $cn;
        //echo "EJECUTANDOSE EL CONSTRUCTOR Marca<br><br>";
	}

    public function update_color(){

		$this->idColor= $_POST['idColor'];
		$this->descripcion = $_POST['descripcion'];
		
		
		
		$sql = "UPDATE color SET descripcion='$this->descripcion'
				WHERE idColor=$this->idColor;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}								
										
	}
	
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

    //*********************** 3.2 METODO save_Marca() **************************************************	

	public function save_color(){
		
		
		$this->descripcion = $_POST['descripcion'];
		
		
		$sql = "INSERT INTO color VALUES(NULL,
											'$this->descripcion');";
		echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
		}								
										
	}

		
	
	public function get_form($idColor=NULL){

        if($idColor == NULL)  {
			$this->descripcion = NULL;
			$flag = NULL; //el flag nos sirve para quitar las opciones que el usuario no acceda a esas opciones
			$op = "new";
	}else{
			$sql = "SELECT * FROM color WHERE idColor=$idColor;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
            $num = $res->num_rows;            
            if($num==0){
                $mensaje = "tratar de actualizar el color con idColor= ".$idColor . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				//Tupla Encontrada
				
		
             // ATRIBUTOS DE LA CLASE marca  
                $this->descripcion = $row['descripcion'];
				
                //$flag = "disabled";
				$flag = "enabled";
                $op = "update"; 
            }
	}   
		
		$html = '
        <div class="container mt-5">
            <div class="card shadow-lg border-warning">
                <div class="card-body">
                <h2 class="text-center text-warning font-weight-bold mb-4">DATOS DE LA MARCA</h2>

                    <form name="Form_Marca" method="POST" action="index.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="' . $idColor  . '">
                        <input type="hidden" name="op" value="' . $op  . '">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="descripcion"><strong>Descripción:</strong></label>
                                <input type="text" class="form-control" name="descripcion" value="' . $this->descripcion .'" required>
                            </div>
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
    
	
//*****************************  CERRAR LA CONEXION A LA BASE DE DATOS ***************************************************************************	   
 
 
 
//*************************************************************************************************        






	/*
     $this->_get_combo_db("marca","id","descripcion","marcaCMB") 
	 $tabla es la tabla de la base de datos
	 $valor es el nombre del campo que utilizaremos como valor del option
	 $etiqueta es nombre del campo que utilizaremos como etiqueta del option
	 $nombre es el nombre del campo tipo combo box (select)
	 */ 
    
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto=NULL){
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

	/*public function guardar($descripcion, $pais, $direccion, $foto) {
		$sql = "INSERT INTO marca (descripcion, pais, direccion, foto) VALUES (?, ?, ?, ?)";
		
		$stmt = $this->con->prepare($sql);
		if ($stmt) {
			$stmt->bind_param("ssss", $descripcion, $pais, $direccion, $foto);
			if ($stmt->execute()) {
				return true;
			} else {
				echo "Error en la ejecución: " . $stmt->error;
				return false;
			}
		} else {
			echo "Error en la consulta: " . $this->con->error;
			return false;
		}
	} */

    // $this->_get_radio($combustibles, "combustibleRBT")
	private function _get_radio($arreglo,$nombre,$defecto=NULL){
		$html = '
		<table border=0 align="left">';
		foreach($arreglo as $i=>$etiqueta){
            $html .= '
			<tr>
				<td>' . $etiqueta . '</td>';
                $html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>':'<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';

			$html .= '</tr>';
		}
		$html .= '
		</table>';
		return $html;
	}
    public function get_detail_color($idColor){
		$sql = "SELECT c.idColor, c.descripcion
                FROM color c
                WHERE c.idColor=$idColor ;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle del color con idColor= ".$idColor . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
	
		$html = '
		<div class="container mt-4">
            <div class="card shadow-lg border-info">
                <div class="card-header bg-info text-white text-center">
                    <h4>Detalles de color</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Descripción:</strong> 
                            <p>' . $row['descripcion'] . '</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="index.php" class="btn btn-outline-info">Regresar</a>
                </div>
            </div>
        </div>';																					
	
		
		return $html;
	}	
	
}
public function delete_color($idColor){
    $sql = "DELETE FROM color WHERE idColor=$idColor;";
        if($this->con->query($sql)){
        echo $this->_message_ok("ELIMINÓ");
    }else{
        echo $this->_message_error("eliminar");
    }	
}

	
	//***********************************************************************************************	
	public function get_list(){
        $d_new = "new/0";                           //Línea agregada es  una anueva variable ayudara a controla
        $d_new_final = base64_encode($d_new);       //Línea agregada
				
		$html = '
		<div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-secondary">Lista de Colores</h3>
            <a href="index.php?d=' . $d_new_final . '" class="btn btn-success">Ingresa Un nuevo Color</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-red">
                    <tr>
                        <th>Descripción</th>
                        <th colspan="3" class="text-center">Acciones</th>
                    </tr>
                    
                </thead>
                <tbody>';

		$sql = "SELECT c.idColor, c.descripcion 
                FROM color c;";	
				
		$res = $this->con->query($sql);

        //$num = $res->num_rows;//devuelve el numero de filas 
		//if($num != 0){
		
		    while($row = $res->fetch_assoc()){ 
				$d_del = "del/" . $row['idColor']; //tengo el id del registro que yo escoja 
				$d_del_final = base64_encode($d_del);
				
				// URL PARA ACTUALIZAR
				$d_act = "act/" . $row['idColor'];
				$d_act_final = base64_encode($d_act);
				
				// URL PARA EL DETALLE
				$d_det = "det/" . $row['idColor'];
				$d_det_final = base64_encode($d_det);	
			
			//ImpResultQuery($row);
			$html .= '
				 <tr> 
                    <td>' . $row['descripcion'] . '</td>
                    <td class="text-center">
                        <a href="index.php?d=' . $d_del_final . '" class="btn btn-outline-danger btn-sm">Borrar</a>
                        <a href="index.php?d=' . $d_act_final . '" class="btn btn-outline-warning btn-sm">Actualizar</a>
                        <a href="index.php?d=' . $d_det_final . '" class="btn btn-outline-info btn-sm">Detalle</a>
                    </td>
                </tr>';
		
		
            }
		
		$html .= '</table>';
		return $html;
		
	}

	

//******************************************************************************************
	private function _message_error($tipo){
        $html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a ..............</th>
			</tr>
			<tr>
				<th><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
    private function _message_BD_Vacia($tipo){
        $html = '
         <table border="0" align="center">
             <tr>
                 <th> NO existen registros en la ' . $tipo . 'Favor contactar a .................... </th>
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
	
	
	
//*******************************************************************************************************************


}



function ImpResultQuery($data){
	
			echo "<pre>";
				print_r($data);
			echo "</pre>"; 

 }
 
 

?>

