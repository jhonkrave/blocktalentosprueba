<?php

require_once('query.php');

if(isset($_POST['role']) && isset($_POST['username']))
{
  if($_POST['role'] == 'profesional_ps' && isset($_POST['professional'])){
    $success =  manage_role_profesional_ps($_POST['username'], $_POST['role'], $_POST['professional']);
    switch($success){
      case 1:
        echo "Rol profesional psicoeducativo asignado con éxito";
        break;
      
      case 2:
        echo "No se ha podido asignar el rol profesional psicoeducativo";
        break;
      
      case 3:
        echo "Rol psicoeducativo actualizado con éxito.";
        break;
  
      case 4:
        echo "Actualización de rol psicoeducativo fallida.";
        break;
    }
  }
  else if($_POST['role'] == 'monitor_ps' && isset($_POST['students'])){
    $success =  update_role_monitor_ps($_POST['username'], $_POST['role'], $_POST['students'], $_POST['boss']);
    switch($success){
      case 1:
        echo "Rol asignado con éxito";
        break;
      
      case 2:
        echo "No se ha podido asignar el rol";
        break;
      
      case 3:
        echo "Rol actualizado con éxito.";
        break;
  
      case 4:
        echo "Actualización de rol fallida(monitor)";
        break;
      default:
        echo $success;
        break;
    }
    
  }
  else{
    $success =  update_role_user($_POST['username'], $_POST['role']);
    switch($success){
      case 1:
        echo "Rol asignado con éxito";
        break;
      
      case 2:
        echo "No se ha podido asignar el rol";
        break;
      
      case 3:
        echo "Rol actualizado con éxito.";
        break;
  
      case 4:
        echo "Actualización de rol fallida.ultimo";
        break;
      
      default:
        echo $success;
        break;
    }
  }
}else if(isset($_POST['deleteStudent']) && isset($_POST['student']) && isset($_POST['username'])){
    echo dropStudentofMonitor($_POST['username'], $_POST['student']);
}else if(isset($_POST['changeMonitor']) && isset($_POST['oldUser']) && isset($_POST['newUser']) ){
  
  try{
    $isdelete = $_POST['isdelete'];
    // SE ALMACENA LA INFORMACION del ususario viejo 
    $oldUserArray =  json_decode($_POST['oldUser']);
    $oldUser = new stdClass();
    $oldUser->id = $oldUserArray[0];
    $oldUser->username = $oldUserArray[1];
    
    // //Se almacena la información del usuario nuevo
    $newUserArray = json_decode($_POST['newUser']);
    $newUser = new stdClass();
    $newUser->id = $newUserArray[0];
    $newUser->username = $newUserArray[1];
    
    //adiciona actualiza el rol monitor para el nuevo usuario
    update_role_monitor_ps($newUser->username, 'monitor_ps', array(), null, 1);
    
    //se actualizan el listado de estduiantes a cargo
    changeMonitor($oldUser->id,  $newUser->id );
    
    //se deshabilita el viejo ususario
    update_role_monitor_ps($oldUser->username, 'monitor_ps', array(), null, 0);
    echo 1;
  }catch(Exception $e){
    echo $e->getMessage();
  }

}else if(isset($_POST['deleteProfesional']) && isset($_POST['user'])){
  try{
    
    $newUserArray = json_decode($_POST['user']);
    $user = new stdClass();
    $user->id = $newUserArray[0];
    $user->username = $newUserArray[1];
    manage_role_profesional_ps($user->username, 'profesional_ps', 'ninguno',0);
    echo 1;
    
  }catch(Exception $e){
    echo $e->getMessage();
  }
  
}else if(isset($_POST['deleteOtheruser']) && isset($_POST['user']) ){
  try{
    
    $newUserArray = json_decode($_POST['user']);
    $user = new stdClass();
    $user->id = $newUserArray[0];
    $user->username = $newUserArray[1];
    $user->rol =  $newUserArray[2];
    update_role_user($user->username, $user->rol, 0);
    echo 1;
    
  }catch(Exception $e){
    echo $e->getMessage();
  }
}
else{
  echo "Actualización de rol fallida";
}

?>