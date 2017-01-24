<?php

require_once('query.php');

if(isset($_POST['function'])){
    
    switch($_POST['function']){
        case "new":
            upgradePares(0);
            break;
        case "load":
            load();
            break;
        case "loadSegMonitor":
            loadbyMonitor();
            break;
        case "loadJustOne":
            loadJustOneSeg();
            break;
        case "update":
            upgradePares(1);
            break;
        case "load_grupal":
            load_students();
            break;
        default:
            $msg =  new stdClass();
            $msg->error = "Error :(";
            $msg->msg = "Error al comuniscarse con el servidor. Verificar la funcion";
            echo json_encode($msg);
            break;
    }
    
}else{
    $msg =  new stdClass();
    $msg->error = "Error :(";
    $msg->msg = "Error al comuniscarse con el servidor. No se reconocio la funcion a ejecutar";
    echo json_encode($msg);
}


function upgradePares($fun){
    try{
    
        if(isset($_POST['date']) && isset($_POST['place']) && isset($_POST['h_ini']) && isset($_POST['m_ini']) && isset($_POST['h_fin']) && isset($_POST['idtalentos']) && isset($_POST['m_fin']) && isset($_POST['tema']) && isset($_POST['objetivos']) && isset($_POST['actividades']) && isset($_POST['observaciones']) && isset($_POST['optradio']) && isset($_POST['tipo'])){
            global $USER;
            date_default_timezone_set("America/Bogota");
            $today = time();
            
            $insert_object = new stdClass();
            
            $insert_object->id_monitor = $USER->id;
            $insert_object->created = $today;
            $insert_object->fecha = strtotime($_POST['date']);
            $insert_object->hora_ini = $_POST['h_ini'].":".$_POST['m_ini'];
            $insert_object->hora_fin = $_POST['h_fin'].":".$_POST['m_fin'];
            $insert_object->lugar = $_POST['place'];
            $insert_object->tema = $_POST['tema'];
            $insert_object->objetivos = $_POST['objetivos'];
            $insert_object->actividades = $_POST['actividades'];
            $insert_object->observaciones = $_POST['observaciones'];
            $insert_object->status = $_POST['optradio'];
            $insert_object->tipo = $_POST['tipo'];
            
            $id = explode(",", $_POST['idtalentos']);
            
            $result = false;
            //si es cero es insercion sino es actualización
            if($fun == 0){
                
                //se almacena solo una vez la fecha de creacion
                $insert_object->created = $today;
                
               
                insertSeguimiento($insert_object,$id);
                $msg =  new stdClass();
                $msg->exito = "exito";
                $msg->msg = "se ha almacenado la informacion con exito:";
                echo json_encode($msg);
               
            }else{
                $msg="";
                $insert_object->id = $_POST['id_seg'];
                $result = null;
                
                if ($insert_object->tipo == 'PARES'){
                    $msg = "pares";
                    $result = updateSeguimiento_pares($insert_object);
                }elseif($insert_object->tipo == 'GRUPAL'){
                    $msg="grupales";
                    $idtalentos_now = $id;
                    
                    //se define e incializa el arreglo $idtalentos_old que va contener los id de los talentos del segumiento obenidos de la base de datos
                    $idtalentos_old =  array();
                    $result_get = getEstudiantesSegGrupal($insert_object->id);
                    
                    foreach($result_get as $r){
                        array_push($idtalentos_old,$r->id_estudiante);
                    }
                    
                     //se verifican los ids que ya no van ser parte del seguimiento- los que se borraran de la bd
                    foreach ($idtalentos_old as $id_old){
                        if (!in_array($id_old,$idtalentos_now)){
                            $msg="grupales-drop";
                            dropTalentosFromSeg($insert_object->id,$id_old);
                        }
                    }
                    
                    // //se adicionan los nuevos en la lista
                    foreach ($idtalentos_now as $id_now){
                        if(!in_array($id_now, $idtalentos_old)){
                            $msg="grupales-add";
                            insertSegEst($insert_object->id,array($id_now));
                        }
                    }
                    
                    //se actualiza el segumiento
                    $result = updateSeguimiento_pares($insert_object);
                }
                
                if ($result){
                    $msg =  new stdClass();
                    $msg->exito = "exito";
                    $msg->msg = "se ha almacenado la informacion con exito";
                    echo json_encode($msg);
                }else{
                    $msg =  new stdClass();
                    $msg->error = "Error :(";
                    $msg->msg = "error al actualizar";
                    echo json_encode($msg);
                }
            }
           
        }else{
            $msg =  new stdClass();
                $msg->error = "Error :(";
                $msg->msg = "Error al comuniscarse con el servidor. No se reconocio las variblaes necesarias para actualizar un nuevo seguimiento";
                echo json_encode($msg);
        }
    }
    catch(Exception $e){
        $msg =  new stdClass();
        $msg->error = "Error :(";
        $msg->msg = "Error al almacenar el registro. ".$e->getMessage()." ".pg_last_error()."   ".$msg;
        echo json_encode($msg);
    }
}

function load(){
    
    if(isset($_POST['idtalentos']) || isset($_POST['tipo'])){
        $result =  getSeguimientoOrderBySemester($_POST['idtalentos'], $_POST['tipo']);
        echo json_encode($result);
    }else{
        $msg =  new stdClass();
        $msg->error = "Error :(";
        $msg->msg = "Error al almacenar el registro. ";
        echo json_encode($msg);
    }
    
}

function loadJustOneSeg(){
    
    if(isset($_POST['id_seg']) && isset($_POST['tipo'])){
    
    $result =  getSeguimiento(null, $_POST['id_seg'],$_POST['tipo']);
    
        foreach($result as $r){ 
            $r->fecha = date('Y-m-d', $r->fecha);
            
            $hora_ini = explode(":", $r->hora_ini);
            $r->h_ini = $hora_ini[0];
            $r->m_ini = $hora_ini[1];
            
            $hora_fin = explode(":", $r->hora_fin);
            $r->h_fin = $hora_fin[0];
            $r->m_fin = $hora_fin[1];
            $r->days = "asdfasd";
            
            $user = getUserMoodleByid($r->id_monitor);
            $r->infoMonitor = $user->firstname." ".$user->lastname;
            
            //Validar si es editable
            
            $editable = true;
            
            date_default_timezone_set("America/Bogota");
            $today = new DateTime(date('Y-m-d',time()));
            $created = new DateTime(date('Y-m-d',$r->created));
            $interval = $created->diff($today);
            $days = $interval->format('%a');
            
            // $hour_today = date('H',time());
            // $min_today = date('i',time());
                
            // $hour = date('H',$r->created);
            // $min = date('h',$r->created);
                
            //$r->days = "dias:".$days."how:".$hour_today.":".$min_today."  date:".$hour.":".$min;
            
            if (intval($days >= 1)){
                $editable =  false;
            }
            
            $r->editable = $editable;
            //se formatea la fecha de creacíón
            $r->createdate = date('d/m/Y \a \l\a\s h:i a',$r->created);
            $r->act_status = $r->status; //no es pendejada, la variable 'status'  hasta JQuery 3.1 es una variable reservada. Por esa razon  se renombra por 'act_status'
            
            if($_POST['tipo'] == 'GRUPAL') $r->attendande_listid = getEstudiantesSegGrupal($_POST['id_seg']);
            
        }
        
        $msg =  new stdClass();
        $msg->result = $result;
        $msg->rows = count($result);
        echo json_encode($msg);
    }else{
        $msg =  new stdClass();
        $msg->error = "Error :(";
        $msg->msg = "Error al cargar el registro";
        echo json_encode($msg);
    }
}

function load_students(){
    global $USER;
    $id_monitor;
    if(isset($_POST['user_management'])){
        $id_monitor = $_POST['user_management'];
    }else{
        $id_monitor = $USER->id;
    }
   
   
   $result =  new stdClass();
   $result->content = getStudentsGrupal($id_monitor);
   $result->rows = count($result->content);
   echo json_encode($result);
}

function loadbyMonitor(){
    global $USER;
    if(isset($_POST['tipo'])){
        
        $result =  getSegumientoByMonitor($USER->id,null, $_POST['tipo']);
        foreach($result as $r){
            $r->fecha = date('d-m-Y', $r->fecha);

        }
        $msg =  new stdClass();
        $msg->result = $result;
        $msg->rows = count($result);
        
        echo json_encode($msg);
    }else{
        $msg =  new stdClass();
        $msg->error = "Error :(";
        $msg->msg = "Error al almacenar el registro. ";
        echo json_encode($msg);
    }
}
?>