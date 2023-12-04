<?php 
    $host = "localhost";
    $usuario = "root";
    $password = "";
    $basededatos ="apiweb08";

    $conexion = new mysqli($host, $usuario, $password, $basededatos);

    if($conexion -> connect_error){
        die("Conexion no establecida". $conexion->connect_error);
    }

    header("Content_type: application/json");
    $metodo = $_SERVER["REQUEST_METHOD"];
    //print_r($metodo);0

    $path = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
    $buscarID = explode('/',$path);
    $id = ($path !=='/')? end($buscarID):null;

    switch($metodo){
        //Select usuario
        case 'GET':
            //echo "Consulta de registro -GET";
            consulta($conexion,$id);
            break;
        
        //Insert    
        case 'POST':
            //echo 'Insertar registros -POST';
            insertar($conexion);
            break;

        //Update
        case 'PUT':
            //echo 'Actualizar registros -GET';
            actualizar($conexion,$id);
            break;

        //Delete
        case 'DELETE':
            //echo 'Eliminar registros -DELETE';
            borrar($conexion,$id);
            break;
    }

////////////////////////////////////////////////////////////////////////////////

        function consulta($conexion, $id){
            $sql = ($id===null) ? "SELECT * FROM usuario" : "SELECT * FROM usuario WHERE id=$id";
            $resultado = $conexion->query($sql);

            if($resultado){
                $datos = array();
                while($filas=$resultado->fetch_assoc()){
                    $datos[]= $filas;
                }

            echo json_encode($datos);

            }
        }

////////////////////////////////////////////////////////////////////////////////

        function insertar($conexion){
            $dato = json_decode(file_get_contents('php://input'),true);
            $nombre = $dato['nombre'];
            //print_r($nombre); 

            $sql = "INSERT INTO usuario(nombre) VALUES ('$nombre')";
            $resultado = $conexion->query($sql);

            if($resultado){
                $datos['id'] = $conexion->insert_id;
                    echo json_encode($datos);
            }else{
                    echo json_encode(array('error'=> 'Error al crear un usuario'));
                }
                
            }

////////////////////////////////////////////////////////////////////////////////

function actualizar($conexion,$id){
    
    

    $dato = json_decode(file_get_contents('php://input'),true);
    $nombre = $dato['nombre'];

    echo 'El id a editar es: '.$id.' con el dato: '.$nombre;

    $sql = "UPDATE usuario SET nombre='$nombre' WHERE id ='$id'";
    $resultado = $conexion->query($sql);

    if($resultado){
        echo json_encode(array('correcto'=>'usuario actualizado'));
    }else{
            echo json_encode(array('error'=> 'Error al actualizar el usuario'));
        }

                
}



////////////////////////////////////////////////////////////////////////////////
function borrar($conexion,$id){
    echo 'El id a borrar es: '.$id;

    $sql = "DELETE FROM usuario WHERE id='$id'";
            $resultado = $conexion->query($sql);

            if($resultado){
                echo json_encode(array('correcto'=>'usuario borrado'));
            }else{
                    echo json_encode(array('error'=> 'Error al borrar el usuario'));
                }
                
            }


?>