<?php 
	//require('conexion.php');
	session_start();
	error_reporting(0); 
	//if(!isset($_SESSION["id_usuario"])){
	//	header("Location: index.php");
	//}

	// $id_ss = $_SESSION['id_usuario'];
	// $key_ss = $_SESSION['key_usuario'];
	// $nom_ss = $_SESSION['nombre_usuario'];
	// $pat_ss = $_SESSION['paterno_usuario'];
	// $mat_ss = $_SESSION['materno_usuario'];
	// $tip_ss = $_SESSION['codtip_usuario'];
	// $red_ss = $_SESSION['cod_red_usuario'];
	// $estab_ss = $_SESSION['cod_estab_usuario'];
	// $estad_ss = $_SESSION['estad_usuario'];
	// $usu_ss = $pat_ss." ".$mat_ss.", ".$nom_ss;                  
   	// $dato_ss = $id_ss." | ".$key_ss." | ".$usu_ss." | ".$tip_ss." | ".$red_ss." | ".$estab_ss." | ".$estad_ss;

   	# echo $dato_ss;

// 	$mensaje = "";	$d1 = '';	$d2 = '';	$d3 = '';	$d4 = '';	$d5 = '';	$d6 = '';	
//  if (isset($_POST['btnconsulta'])){

		//$nrodni_btn = '73708997';  //$_POST['nrodni'];
		$nrodni_btn = $_POST['nrodni'];


        //ini_set("soap.wsdl_cache_enabled", "0");                      
        $parametros = array(); 
        $parametros['NroDNI']=  trim($nrodni_btn);
        $parametros['IP_Equipo'] = '127.0.0.1';
        $parametros['Nom_Equipo']=  'PCPRUEBA'  ;
        $parametros['UsuarioSistema'] = 'UsuarioSistema';
        $parametros['NombreSistema'] = 'NombreSistema';
        $parametros['NombreEntidad'] = 'NombreEntidad';
        $parametros['UserWS'] = "71220113";
        $parametros['pwdWS'] = "%%485#OIKSAC";
        try{       
            $servicio = 'http://apps1.diresacallao.gob.pe/wsdiresacallao/ServiceSoapDiresaCallao.asmx?wsdl';
            $data = file_get_contents($servicio);
            if($data==false){
                $respuesta = new \stdClass();
                    $respuesta->CodRespuesta = "504";
                    $respuesta->mensaje = "El WS del DIRESA  no se encuentra disponible en estos momentos .";
                echo json_encode($respuesta);     
                    exit();
            }
            $client = new SoapClient($servicio, $parametros);
            $result = $client->ConsultaReniecV2($parametros); 
            $carga_xml = simplexml_load_string(  $result->ConsultaReniecV2Result    );
            $TablaDatos = $carga_xml->Table;
            $respuesta = new \stdClass();    
            if($TablaDatos->CodRespuesta=="200"){
            $d1 = $TablaDatos->Dni ;
            $d2 = $TablaDatos->Nombre ;
			$d3 = $TablaDatos->ApellidoPaterno;
            $d4 = $TablaDatos->ApellidoMaterno;
            $d5 = $TablaDatos->FechaNacimiento;
			 $date = DateTime::createFromFormat('d/m/Y', $d5);
			 $date2 = $date->format('Y-m-d');
			$anio = $date->format('Y');
			// if ($anio < 2015) {
			// 	$d1 = '';
			// 	$d2 = '';
			// 	$d3 = '';
			// 	$d4 = '';
			// 	$d5 = '';
			// 	$d6 = '';
			// 	$date2 = '';
			// }
           $d6 = $TablaDatos->Direccion;
			$Respuesta=array();
			$Respuesta['Dni']=$d1;
			$Respuesta['Nombre']=$d2;
			$Respuesta['ApellidoPaterno']=$d3;
			$Respuesta['ApellidoMaterno']=$d4;
			$Respuesta['FechaNacimiento']=$d5;
			$Respuesta['Direccion']=$d6;

		   //print_r($Respuesta);
		   echo json_encode($Respuesta);

            }else{        
                $respuesta->CodRespuesta = trim($TablaDatos->CodRespuesta);
                $respuesta->mensaje = trim($TablaDatos->Mensaje);   
            } 
        }catch(SoapFault $fault) {                      
            $respuesta = new \stdClass();
            $respuesta->CodRespuesta = trim($fault->faultcode);
            $respuesta->mensaje = trim($fault->faultstring);
            echo json_encode($respuesta); 
        } catch(Exception $e) {                      
            $respuesta = new \stdClass();
            $respuesta->CodRespuesta = "404";
            $respuesta->mensaje = $e->getMessage();
            echo json_encode($respuesta);     
        }  

 //};



    // if (isset($_POST['btnregistra'])) {
    // 	$error_dato = 0;
    //     date_default_timezone_set('America/Lima');
 	// 	$hoy = date("Y-m-d H:m:s");   
    // 	$tipo_doc = $_POST['tipo_doc'];
    // 	$nrodni = $_POST['nrodni'];
	// 	$nrohc = $_POST['nrohc'];
	// 	$nombre = $_POST['nombre'];
	// 	$paterno = $_POST['paterno'];
	// 	$materno = $_POST['materno'];
	// 	$fchanac = $_POST['fchanac'];
	// 	$direccion = $_POST['direccion'];
	// 	$sector = $_POST['sector'];
	// 	$distrito = $_POST['distrito'];
	// 	$tlfcelular = $_POST['tlfcelular'];
	// 	$tlffijo = $_POST['tlffijo'];
	// 	$condicion = $_POST['condicion'];
	// 	$seguro = $_POST['seguro'];

	// 	$datos_input = $hoy." | ".$tipo_doc." | ".$nrodni." | ".$nrohc." | ".$nombre." | ".$paterno." | ".$materno." | ".$fchanac." | ".$direccion." | ".$distrito." | ".$tlfcelular." | ".$tlffijo;


    // 	if ($tipo_doc == 0) { $error_dato = 1; }
    // 	if (strlen(trim($nrodni)) != 8) { $error_dato = 1; }
	// 	if (strlen(trim($nrohc)) <= 3) { $error_dato = 1; }
	// 	if (strlen(trim($nombre)) < 2) { $error_dato = 1; }
	// 	if (strlen(trim($paterno)) < 2) { $error_dato = 1; }
	// 	if (strlen(trim($materno)) < 2) { $error_dato = 1; }
	// 	if (strlen(trim($fchanac)) != 10) { $error_dato = 1; }
	// 	if (strlen(trim($direccion)) < 5) { $error_dato = 1; }
	// 	if ($sector == 0) { $error_dato = 1; }
	// 	if ($distrito == 0) { $error_dato = 1; }
	// 	if ($condicion == 0) { $error_dato = 1; }
	// 	if ($seguro == 0) { $error_dato = 1; }
	
	// 	echo $error_dato;


	// 	// if ($error_dato == 0) {
	// 	// 	$sql_verifica = "SELECT dni, hc, cod_atencion,desc_estab, fecha_reg FROM tbl_nino AS n
	// 	// 					 LEFT JOIN tbl_establecimiento AS e
	// 	// 					 ON e.cod_estab=n.cod_atencion WHERE dni = '".$nrodni."'";
	// 	// 	$result_verifica = mysqli_query($conexion, $sql_verifica);
	// 	// 	while($fill = mysqli_fetch_assoc($result_verifica)) {
	// 	//     	$cs = substr($fill['desc_estab'],4);
	// 	//     	$csfc = $fill['fecha_reg'];
	// 	//     	$date = date_create($csfc);
	// 	//     	$csfc2	= date_format($date, 'd/m/Y H:i:s');
	// 	//     }
	// 	// 	$rows = mysqli_num_rows($result_verifica);
	// 	// 	# echo "<br>".$rows;

	// 	// 	if ($rows > 0) {
	// 	// 	    $mensaje = '
	// 	// 	        <div class="alert alert-warning alert-dismissible fade show" role="alert">
	// 	// 	          <strong>Alerta!</strong> El registro no pudo grabarse porque ya esta registrado en '.$cs.' desde el '.$csfc2.'
	// 	// 	          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	// 	// 	            <span aria-hidden="true">&times;</span>
	// 	// 	          </button>
	// 	// 	        </div>';


	// 	// 	}
	// 	// 	else{
	// 	// 	    $sql_inserta = "Call Sp_InsertarPaciente('$condicion','$tipo_doc','$nrodni','$nrohc','$nombre','$paterno','$materno','$fchanac','$direccion','$sector','$distrito','$seguro','$tlfcelular','$tlffijo','$estab_ss','$red_ss','$key_ss','$hoy');";
	// 	// 	    $result_inserta = mysqli_query($conexion, $sql_inserta);	
	// 	// 	    $mensaje = '
	// 	// 	        <div class="alert alert-success alert-dismissible fade show" role="alert">
	// 	// 	          <strong>Exito!</strong> El registro se grabó correctamente.
	// 	// 	          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	// 	// 	            <span aria-hidden="true">&times;</span>
	// 	// 	          </button>
	// 	// 	        </div>';
	// 	// 	    echo   '<meta http-equiv="Refresh" content="2;url=list.php">	';
	// 	// 	}
	// 	// }



    // }
 ?>
