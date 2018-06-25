<?php

    $valores = array("id" => 0, "cedula" => "", "nombre" => "", "apellido" => "", "comportamiento" => "");

    if($_POST){
        if($_POST['cedula'] && $_POST['nombre'] && $_POST['apellido'] && $_POST['comportamiento']){
            $cedula = $_POST['cedula'];
            
            $bdLink = conexion::getInstancia();                
            $id = $_POST['id'] + 0; 

            if($id > 0){
                # Editar
                $sql = "UPDATE persona SET cedula = ? , nombre = ?, apellido = ?, comportamiento = ? WHERE id = ?";
                
                $stmt = mysqli_prepare($bdLink, $sql);
                mysqli_stmt_bind_param($stmt, 'ssssi', $_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['comportamiento'], $id);
                mysqli_stmt_execute($stmt);                
            } else {
                # Agregar
                $sql = "INSERT INTO persona (cedula, nombre, apellido, comportamiento) VALUES (?, ?, ?, ?)";
                
                $stmt = mysqli_prepare($bdLink, $sql);
                mysqli_stmt_bind_param($stmt, 'ssss', $_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['comportamiento']);
                mysqli_stmt_execute($stmt);
            }
        } else {
            echo "<script> alert('¡Hay campos vacios!'); </script>"; 
        }

        $_POST = array();
    } else {
        $bdLink = conexion::getInstancia();
        
        if(isset($_GET['editar'])){
            $id = $_GET['editar'] + 0;
            $rs = conexion::consulta("select * from persona where id = $id");
            
            if(count($rs) > 0)
                $valores = $rs[0];
        }
        
        if(isset($_GET['eliminar'])){
            $id = $_GET['eliminar'] + 0;

            $sql = "DELETE FROM persona WHERE id = ?";
            $stmt = mysqli_prepare($bdLink, $sql); 
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
        }
    }
    
    function mostrarUsuarios(){
        $personas = conexion::consulta('select * from persona');
        
        if($personas != null){
            foreach($personas as $persona)
            {    
                echo 
                " 
                    <tr>
                        <td> {$persona['cedula']} </td>
                        <td> {$persona['nombre']} </td>
                        <td> {$persona['apellido']} </td>

                        <td>
                            <a href='index.php?editar={$persona['id']}' class='waves-effect yellow darken-4 btn-large'><i class='material-icons'>mode_edit</i></a>
                            <a href='ver.php?editar={$persona['id']}' target='_blank' class='waves-effect blue darken-4 btn-large'><i class='material-icons'>remove_red_eye</i></a>
                            <a href='index.php?eliminar={$persona['id']}' onclick=\"return confirm('¿Estás seguro?')\" class='waves-effect red darken-4 btn-large'><i class='material-icons'>delete_forever</i></a>
                        </td>
                    </tr>
                ";    
            }
        }
    
    }

?>