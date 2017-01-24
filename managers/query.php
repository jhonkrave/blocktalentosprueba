<?php
require_once(dirname(__FILE__). '/../../../config.php');
require_once('MyException.php');
require_once $CFG->libdir.'/gradelib.php';
require('../../../grade/querylib.php');
require_once '../../../config.php';
require_once $CFG->dirroot.'/grade/lib.php';
require_once $CFG->dirroot.'/grade/report/user/lib.php';



function get_userById($column, $id){
    global $DB;
    
    $columns_str= "";
    for($i = 0; $i < count($column); $i++){
        
        $columns_str = $columns_str.$column[$i].",";
        
    }
    
    if(strlen($id) > 7){
        $id = substr ($id, 0 , -5);
    }
    
    $columns_str = trim($columns_str,",");
    $sql_query = "SELECT ".$columns_str.", (now() - fecha_nac)/365 AS age  FROM (SELECT *, idnumber as idn, name as namech FROM {cohort}) AS ch INNER JOIN (SELECT * FROM {cohort_members} AS chm INNER JOIN ((SELECT * FROM (SELECT *, id AS id_user FROM {user}) AS userm INNER JOIN (SELECT userid, CAST(d.data as int) as data FROM {user_info_data} d WHERE d.data <> '' and fieldid = (SELECT id FROM  {user_info_field} as f WHERE f.shortname ='idtalentos')) AS field ON userm. id_user = field.userid ) AS usermoodle INNER JOIN (SELECT *,id AS idtalentos FROM {talentospilos_usuario}) AS usuario ON usermoodle.data = usuario.id) AS infouser ON infouser.id_user = chm.userid) AS userchm ON ch.id = userchm.cohortid WHERE userchm.id_user in (SELECT userid FROM {user_info_data} as d INNER JOIN {user_info_field} as f ON d.fieldid = f.id WHERE f.shortname ='estado' AND d.data ='ACTIVO') AND substr(userchm.username,1,7) = '".$id."';";
    
    $result_query = $DB->get_record_sql($sql_query);
    //se formatea el codigo  para eliminar la info del programa
    if($result_query) {
        if(property_exists($result_query,'username'))  $result_query->username = substr ($result_query->username, 0 , -5);
    }
    //print_r($result_query);
    return $result_query;
}

function getPrograma($id){
    global $DB;
    return $DB -> get_record_sql("SELECT * FROM  {talentospilos_programa} WHERE id=".$id.";"); 
}

function getEnfasisFinal($idtalentos){
    global $DB;
    return $DB -> get_record_sql("SELECT * FROM (SELECT nombre AS nom_enfasis, * FROM {talentospilos_enfasis}) enf INNER JOIN {talentospilos_vocacional} voc ON enf.id = voc.final_enfasis  WHERE id_estudiante=".$idtalentos.";"); 
}

function get_usersByPopulation($column, $population, $risk){
    global $DB;
    //consulta
    $sql_query = "";
    //cohorte
    $ch = $population[0];
    //grupo
    $group = $population[1];
    //estado
    $state = $population[2];
    //enfasis
    $enfasis = $population[3];
    
    //se formatean las columnas
    $chk = array("Código","Nombre","Apellidos", "Documento", "Dirección", "Nombre acudiente", "Celular acudiente", "Grupo", "Estado", "Email","Celular");
    $name_chk_db = array("username", "firstname", "lastname", "num_doc","direccion_res","acudiente", "tel_acudiente","grupo","estado","email","celular");
    
    //se eliminan las columnas con valores nulos: en caso de que el checkbox de grupo esté disable
    $column = array_filter($column, function($var){return !is_null($var);} );
    
    $columns_str= "";
    for($i = 0; $i < count($column); $i++){
        if (in_array($column[$i],$chk)){
            $column[$i] = $name_chk_db [array_search($column[$i],$chk)];
        } 
        $columns_str = $columns_str.$column[$i].",";
    }
    //print_r($columns_str);
    $columns_str = trim($columns_str,",");
    //se formatea la consulta
    if($ch == "TODOS"){
        $sql_query = "SELECT ".$columns_str." FROM {cohort} AS pc INNER JOIN (SELECT * FROM {cohort_members} AS pcm INNER JOIN (SELECT * FROM (SELECT * FROM (SELECT *, id AS id_user FROM {user}) AS userm INNER JOIN (SELECT userid, CAST(d.data as int) as data FROM {user_info_data} d WHERE d.data <> '' and fieldid = (SELECT id FROM  {user_info_field} as f WHERE f.shortname ='idtalentos')) AS field ON userm. id_user = field.userid ) AS usermoodle INNER JOIN {talentospilos_usuario} as usuario ON usermoodle.data = usuario.id WHERE usermoodle.id_user in (SELECT userid FROM {user_info_data} as d INNER JOIN {user_info_field} as f ON d.fieldid = f.id WHERE f.shortname ='estado' AND d.data ='ACTIVO')) as usertm ON usertm.id_user = pcm.userid) as pcmuser on pc.id = pcmuser.cohortid WHERE pc.idnumber like 'SP%';";
        // $sql_query = "SELECT ".$columns_str." FROM {cohort} AS pc INNER JOIN (SELECT * FROM {cohort_members} AS pcm INNER JOIN (SELECT * FROM (SELECT * FROM (SELECT *, id AS id_user FROM {user}) AS userm INNER JOIN (SELECT * FROM {user_info_data} as d INNER JOIN {user_info_field} as f ON d.fieldid = f.id WHERE f.shortname ='idtalentos' ) AS field ON userm. id_user = field.userid ) AS usermoodle INNER JOIN {talentospilos_usuario} as usuario ON CAST( usermoodle.data AS INT) = usuario.id WHERE usermoodle.id_user in (SELECT userid FROM {user_info_data} as d INNER JOIN {user_info_field} as f ON d.fieldid = f.id WHERE f.shortname ='estado' AND d.data ='ACTIVO')) as usertm ON usertm.id_user = pcm.userid) as pcmuser on pc.id = pcmuser.cohortid WHERE pc.idnumber like '%SP%';";
        if($state != "TODOS"){
            $sql_query = trim($sql_query,";");
            $sql_query = $sql_query." AND estado = '".$state."';";
        }
       
    }else {
        $sql_query = "SELECT ".$columns_str." FROM {cohort} AS pc INNER JOIN (SELECT * FROM {cohort_members} AS pcm INNER JOIN (SELECT * FROM (SELECT * FROM (SELECT *, id AS id_user FROM {user}) AS userm INNER JOIN (SELECT userid, CAST(d.data as int) as data FROM {user_info_data} d WHERE d.data <> '' and fieldid = (SELECT id FROM  {user_info_field} as f WHERE f.shortname ='idtalentos')) AS field ON userm. id_user = field.userid ) AS usermoodle INNER JOIN (SELECT id as idtalentos, * FROM {talentospilos_usuario}) as usuario ON usermoodle.data = usuario.id WHERE usermoodle.id_user in (SELECT userid FROM {user_info_data} as d INNER JOIN {user_info_field} as f ON d.fieldid = f.id WHERE f.shortname ='estado' AND d.data ='ACTIVO')) as usertm ON usertm.id_user = pcm.userid) as pcmuser on pc.id = pcmuser.cohortid ";
    	$whereclause = "WHERE pc.idnumber ='".$ch."';";
    	//validar si es una cohorte talentos para identificar el grupo en la consulta               
        if((strpos($ch, "SPT")!== false) ){ //consultar como dentificar cohorte talentos,   && ($group != "TODOS")
            
            //videntificar grupo
            if($group != "TODOS" ){
                $whereclause = trim($whereclause,";");
               $whereclause.= " AND grupo = ".$group.";";
            }
            
            if($enfasis == "NO REGISTRA"){
                $whereclause = trim($whereclause,";");
                $whereclause .= " AND idtalentos NOT IN  (SELECT id_estudiante FROM {talentospilos_vocacional}) ";
            }else if ($enfasis == "TODOS"){
                $whereclause = trim($whereclause,";");
                $sql_query .= " INNER JOIN (SELECT * FROM {talentospilos_enfasis} enf INNER JOIN {talentospilos_vocacional} voc ON enf.id = voc.final_enfasis) as enfa ON enfa.id_estudiante = idtalentos ";
            }else{
                $whereclause = trim($whereclause,";");
                $sql_query .= " INNER JOIN (SELECT * FROM {talentospilos_enfasis} enf INNER JOIN {talentospilos_vocacional} voc ON enf.id = voc.final_enfasis) as enfa ON enfa.id_estudiante =idtalentos AND enfa.nombre = '".$enfasis."' ";
            }
         }
        
        $sql_query.=  $whereclause;
        
        //validar el estado
        if($state != "TODOS"){
            $sql_query = trim($sql_query,";");
            $sql_query = $sql_query." AND estado = '".$state."';";
        }
    }
    
    $result_query = $DB->get_records_sql($sql_query,null);
    
    $result  = array();
    foreach ($result_query as $ri){
        $temp = array();
        foreach($column as $c){
            $tempc;
            if (in_array($c,$name_chk_db)){
            $tempc = $chk [array_search($c,$name_chk_db)];
            }else{
                $tempc = $c;
            }
            if ($c == "username"){
                $temp[$tempc] = substr ($ri->$c, 0 , -5);
            }else{
                $temp[$tempc] = $ri->$c;
            }
        }
        array_push($result, $temp);    
    }
    $prueba =  new stdClass;
    $prueba->data= $result;
    $prueba->columns = $columns_str." y la poblacion es: ".$population[0]." - ".$population[1]." - ".$population[2]." - ".$population[3];
    //print_r($prueba);
    
    
    if($risk != -1){
        
        // ****************
        // Academic risk
        // ****************
        if($risk == 0 || $risk == 2){
            foreach($prueba->data as &$student){
                
                $sql_query = "SELECT id FROM {user} WHERE username LIKE '".$student['Código']."%';";
                $id_student = $DB->get_record_sql($sql_query);
                
                // $idFirstSemester = getIdFirstSemester();
    
                // $grades_student = get_grades_courses_student_semester($id_student->id);
                
                $sql_query = "SELECT grades.finalgrade  
                              FROM {grade_items} AS items INNER JOIN {grade_grades} AS grades ON items.id = grades.itemid 
                              WHERE items.itemtype = 'course' AND grades.userid = $id_student->id;"; 
                              
                $grades_student = $DB->get_records_sql($sql_query);
                
                
                // die();
      
                $risktype = 'Bajo';
                
                if($grades_student){
                    foreach($grades_student as $grade){
    
                        if($grade->finalgrade < 3.0){
            
                            $risktype = 'Alto';
            
                            break;
                        }
                        else if($grade->finalgrade > 3.0 && $grade->finalgrade < 3.5){
                            $risktype = 'Medio';
                        }
            
                    }
                }
    
                $student['academic_risk'] = $risktype;
            }            
        }

         // ****************
         // Social risk
         // ****************
         if($risk == 1 || $risk == 2){
            foreach ($prueba->data as &$student){
                $idtalentos = get_userById(array('idtalentos'), $student['Código']);  
                $idmoodle = get_userById(array('id_user'), $student['Código']);
                $lastsemestre = getIdLastSemester($idmoodle->id_user);
                if($lastsemestre){
                     $student['social_risk'] = getRiskString(getPormStatus($idtalentos->idtalentos, $lastsemestre)->promedio);
                }else{
                    $student['social_risk'] = "Sin registros";
                }
            }               
        }
    }
    //print_r($prueba);
    return $prueba;
}
//get_usersByPopulation(array("Código"),array("TODOS","TODOS","ACTIVO","TODOS"));

function getRiskString($val){
    if($val ==0){
        return '<span ><span style="color: red;">Sin Contacto</span></span>';
    }else if($val>0 && $val<2){
        return "Bajo";
    }else if($val>=2 && $val<3){
        return "Medio Bajo";
    }else if($val>=3 && $val<4){
        return "Medio";
    }else if($val>=4 && $val<5){
        return "Medio Alto";
    }else if($val == 5){
        return "Alto";
    }
}

function update_talentosusuario($column,$values,$id){
    global $DB;
    try{
        
        //se obtiene el id de  la tabla usario talentos
        $iduser = get_userById(array('idtalentos'),$id);
        //se define un arreglo que va a contener la info a actualizar
        $obj_updatable = array();
        //se inserta la info
        for($i = 0; $i < count($column); $i++){
            $obj_updatable[$column[$i]] = $values[$i];
        }
        $obj_updatable = (object) $obj_updatable;
        //se le asigna el id del usario a actualizar
        $obj_updatable->id = $iduser->idtalentos;
        
        return $DB->update_record('talentospilos_usuario', $obj_updatable);
    }catch(Exception $e){
       return false;
    }
}

/** 
 *****************************
 Funciones gestión de usuarios
 *****************************
**/

function get_role_user($id_moodle)
{
    global $DB;
    $current_semester = get_current_semester(); 
    $sql_query = "select nombre_rol, rol.id as rolid from {talentospilos_user_rol} as ur inner join {talentospilos_rol} as rol on rol.id = ur.id_rol where  ur.estado = 1 AND ur.id_semestre =".$current_semester->max."  AND id_usuario = ".$id_moodle.";";
    return $DB->get_record_sql($sql_query);
}

function get_permisos_role($idrol,$page){
    global $DB;
    
    $fun_str ="";
    switch ($page) {
        case "ficha":
            $fun_str = " AND  substr(fun.nombre_func,1,2) = 'f_';";
            break;
        case 'archivos':
            $fun_str = " AND fun.nombre_func = 'carga_csv';";
            break;
        case 'index':
            $fun_str = " AND fun.nombre_func = 'reporte_general';";
            break;
        case 'role':
            $fun_str = " AND fun.nombre_func = 'gestion_roles';";
            break;
        default:
            // code...
            break;
    }
    
    $sql_query = "select pr.id as prid , fun.id as funid,* from {talentospilos_permisos_rol} as pr inner join {talentospilos_funcionalidad} as fun on id_funcionalidad = fun.id inner join {talentospilos_permisos} p  on id_permiso = p.id inner join {talentospilos_rol} r on r.id = id_rol   where id_rol=".$idrol.$fun_str;
    $result_query = $DB->get_records_sql($sql_query);
    //print_r(json_encode($result_query));
    return $result_query;
}

/**
 * Función que asigna un rol a un usuario
 *
 * @see assign_role_user($username, $id_role, $state, $semester, $username_boss){
 * @return Integer
 */
 
 function assign_role_user($username, $role, $state, $semester, $username_boss = null){
     
    global $DB;
    
    $sql_query = "SELECT id FROM {user} WHERE username='$username'";
    $id_user_moodle = $DB->get_record_sql($sql_query);
     
    $sql_query = "SELECT id FROM {talentospilos_rol} WHERE nombre_rol='$role';";
    $id_role = $DB->get_record_sql($sql_query);
    
    $id_semester = get_current_semester();
    
    if($role == "monitor_ps")
    {
        $sql_query = "SELECT * FROM {user} WHERE username='$username_boss'";
        $id_boss = $DB->get_record_sql($sql_query);    
    }
    else{
        $id_boss = null;
    }
        
    $array = new stdClass;
    $array->id_rol = $id_role->id;
    $array->id_usuario = $id_user_moodle->id;
    $array->estado = $state;
    $array->id_semestre = $id_semester->max;
    $array->id_jefe = $id_boss;
    
    //print_r($array);
    
    $insert_user_rol = $DB->insert_record('talentospilos_user_rol', $array, false);
        
    if($insert_user_rol){
        return 1;
    }
    else{
        return 2;
    }
}

/**
 * Función que revisa si un usuario tiene un rol asignado
 *
 * @see checking_role($username)
 * @return Boolean
 */
 
function checking_role($username){
     
    global $DB;
     
    $sql_query = "SELECT id FROM {user} WHERE username = '$username'";
    $id_moodle_user = $DB->get_record_sql($sql_query);
    
    $semestre =  get_current_semester();
    
    $sql_query = "SELECT ur.id_rol as id_rol , r.nombre_rol as nombre_rol, ur.id as id, ur.id_usuario, ur.estado FROM {talentospilos_user_rol} ur INNER JOIN {talentospilos_rol} r ON r.id = ur.id_rol WHERE ur.id_usuario = ".$id_moodle_user->id." and ur.id_semestre = ".$semestre->max.";";
    $role_check = $DB->get_record_sql($sql_query); 
    
    return $role_check;
}

/**
 * Función que actualiza el rol de un usuario en particular
 *
 * @see update_role_user($id_moodle_user, $id_role, $state, $id_semester, $username_boss){
 * @return Entero
 */
function update_role_user($username, $role, $state = 1, $semester = null, $username_boss = null){
    
    global $DB;
    
    $sql_query = "SELECT id FROM {user} WHERE username='$username'";
    $id_user_moodle = $DB->get_record_sql($sql_query);
     
    $sql_query = "SELECT id FROM {talentospilos_rol} WHERE nombre_rol='$role';";
    $id_role = $DB->get_record_sql($sql_query);
    
    $sql_query ="select max(id) as id from {talentospilos_semestre};";
    $id_semester = $DB->get_record_sql($sql_query);
    
     $array = new stdClass;
    $id_boss = null;
    if($username_boss != null){
        $sql_query = "SELECT * FROM {user} WHERE username='$username_boss'";
        $result = $DB->get_record_sql($sql_query);
        $id_boss =  $result->id;
    }
    
    
   
    $array->id_rol = $id_role->id;
    $array->id_usuario = $id_user_moodle->id;
    $array->estado = $state;
    $array->id_semestre = $id_semester->id;
    $array->id_jefe = $id_boss;
    
    $result = 0;
    
    if ($checkrole = checking_role($username)){
        
        if ($checkrole->nombre_rol == 'monitor_ps'){
            $whereclause = "id_monitor = ".$id_user_moodle->id;
            $DB->delete_records_select('talentospilos_monitor_estud',$whereclause);
            
        }else if($checkrole->nombre_rol == 'profesional_ps'){ 
            $whereclause = "id_usuario = ".$id_user_moodle->id;
            $DB->delete_records_select('talentospilos_usuario_prof',$whereclause);
        } 
        
        
        $array->id = $checkrole->id;
        $update_record = $DB->update_record('talentospilos_user_rol', $array);
        if($update_record){
            $result = 3;
        }else{
            $result = 4;
        }
    }else{
        $insert_record = $DB->insert_record('talentospilos_user_rol', $array);
        if($insert_record){
            $result =1;
        }else{
            $result = 2;
        }
    }

    return $result;
}

/*
*********************************************************************************
FUNCIONES RELACIONADAS CON EL ROL PROFESIONAL PSICOEDUCATIVO
*********************************************************************************
*/

/**
 * Función que asigna un tipo de profesional a un usuario con rol profesional psicoeducativo
 *
 * @see assign_professional_user($id_user, $professional)
 * @return Integer
 */
 
 function assign_professional_user($id_user, $professional){
    
    global $DB;
    
    $sql_query = "SELECT id FROM {talentospilos_profesional} WHERE nombre_profesional = '$professional'";
    $id_professional = $DB->get_record_sql($sql_query);
    
    $record_professional_type = new stdClass;
    $record_professional_type->id_usuario = $id_user;
    $record_professional_type->id_profesional = $id_professional->id;
    
    //print_r($record_professional_type);
    
    $insert_record = $DB->insert_record('talentospilos_usuario_prof', $record_professional_type, true);
    
    return $insert_record;
 }
 
 /**
 * Función que actualiza en l
 *
 * @see assign_professional_user($id_user, $professional)
 * @return Integer
 */
 
 
 /**
 * Función que actualiza el tipo de profesional a un usuario con rol profesional psicoeducativo
 *
 * @see update_professional_user($id_user, $professional)
 * @return Integer
 */
 
 function update_professional_user($id_user, $professional){
     
    global $DB;
    
    $sql_query = "SELECT id FROM {talentospilos_profesional} WHERE nombre_profesional = '$professional'";
    $id_professional = $DB->get_record_sql($sql_query);
    
    if($id_professional){
        $sql_query = "SELECT id FROM {talentospilos_usuario_prof} WHERE id_usuario = '$id_user'";
        $id_to_update = $DB->get_record_sql($sql_query);
    
        $record_professional_type = new stdClass;
        $record_professional_type->id = $id_to_update->id;
        $record_professional_type->id_profesional = $id_professional->id;
    
        $update_record = $DB->update_record('talentospilos_usuario_prof', $record_professional_type);
    
        return $update_record;
    }else{
        return false;
    }
    
 }
 
 // Testing
 // update_professional_user(221, 'trabajador_social');
 
/**
 * Función que administra el rol profesional psicoeducativo
 *
 * @see manage_role_profesional_ps($username, $role, $professional)
 * @return booleano confirmando el éxito de la operación
 */

function manage_role_profesional_ps($username, $role, $professional,$state = 1)
{
    global $DB;
    try{
        
        $sql_query = "SELECT * FROM {user} WHERE username ='$username';";
        $object_user = $DB->get_record_sql($sql_query);
        
        $sql_query = "SELECT id_rol,nombre_rol FROM {talentospilos_user_rol} ur INNER JOIN {talentospilos_rol} r ON r.id = ur.id_rol  WHERE id_usuario = ".$object_user->id." AND id_semestre = (select max(id)  from {talentospilos_semestre});";
        $id_current_role = $DB->get_record_sql($sql_query);
        
        $id_current_semester = get_current_semester();
        
        if(empty($id_current_role)){
        
            // Start db transaction
            pg_query("BEGIN") or die("Could not start transaction\n");
            
            assign_role_user($username, $role, 1, $id_current_semester->max, null);
            
            assign_professional_user($object_user->id, $professional);
            
            // End db transaction
            pg_query("COMMIT") or die("Transaction commit failed\n");
        
        }
        else{
            //en la consulta se hace tiene en cuenta el semestre concurrente
            $sql_query = "SELECT * FROM {talentospilos_user_rol} userrol INNER JOIN {talentospilos_usuario_prof} userprof 
                            ON userrol.id_usuario = userprof.id_usuario INNER JOIN {talentospilos_rol} rol ON rol.id = userrol.id_rol  WHERE userprof.id_usuario = ".$object_user->id." AND userrol.id_semestre=".$id_current_semester->max.";";
            $object_user_role = $DB->get_record_sql($sql_query);
            
            if($object_user_role){
                // Incluir el estado
                
                $sql_query = "SELECT id FROM {talentospilos_profesional} WHERE nombre_profesional = '$professional'";
                $new_id_professional_type = $DB->get_records_sql($sql_query);
                
                foreach ($new_id_professional_type as $n){
                    if($object_user_role->id_profesional != $n->id){
                        update_professional_user($object_user->id, $professional);
                    }
                }
                
                //se actualiza el estado en caso de que se hjaya desactivado anteriormente
                update_role_user($username,$role,$state);
                if($state == 0){
                    $whereclause = "id_usuario = ".$object_user->id;
                    $DB->delete_records_select('talentospilos_usuario_prof',$whereclause);
                }
               
            }else{
                
                // caso monitor
                
                
                // Start db transaction
                pg_query("BEGIN") or die("Could not start transaction\n");
                
                if($id_current_role->nombre_rol == 'monitor_ps'){ 
                    $whereclause = "id_monitor = ".$object_user->id;
                    $DB->delete_records_select('talentospilos_monitor_estud',$whereclause);
                } 
                
                update_role_user($username, $role, $state, $id_current_semester->max, null);
                
                assign_professional_user($object_user->id, $professional);
                
                // End db transaction
                pg_query("COMMIT") or die("Transaction commit failed\n");
            }
            
        }
    //print_r(1);
    return 1;
        
    }catch(Exception $e){
        return "Error al gestionar los permisos profesional ".$e->getMessage();
    }
    
}

// Testing
//manage_role_profesional_ps('1124153-3743', 'profesional_ps', 'terapeuta_ocupacional');
//manage_role_profesional_ps('1673003-1008', 'profesional_ps', 'psicologo');

/**
 * Función que asigna el rol profesional psicoeducativo y el tipo de profesional 
 *
 * @see update_role_profesional_ps($username, $role, $professional)
 * @return booleano confirmando el éxito de la operación
 */

function assign_role_professional_ps($username, $role, $state = 1, $semester, $username_boss = null, $professional)
{
    global $DB;
    
    $sql_query = "SELECT id FROM {user} WHERE username ='$username';";
    $id_user = $DB->get_record_sql($sql_query);
    
    $sql_query = "SELECT id_rol FROM {talentospilos_user_rol} WHERE id_usuario = '$id_user->id';";
    $id_current_role = $DB->get_record_sql($sql_query);
    
    if(empty($id_current_role)){
        
        // Start db transaction
        pg_query("BEGIN") or die("Could not start transaction\n");
        
        assign_role_user($username, $role, $state, $semester->max, null);
        
        assign_professional_user($id_user->id, $professional);
        
        // End db transaction
        pg_query("COMMIT") or die("Transaction commit failed\n");
        
        //print_r("funcinoa");
    }
}

function getProfessionals($id = null){
    global $DB;
    $sql_query = "SELECT username,firstname,lastname,us.id, prof.nombre_profesional FROM {user} us INNER JOIN  {talentospilos_usuario_prof} p ON p.id_usuario = us.id INNER JOIN {talentospilos_profesional} prof on prof.id = p.id_profesional";
    
    if($id) $sql_query .= " WHERE us.id =".$id.";";
    return $DB->get_records_sql($sql_query);
}

/*
*********************************************************************************
FIN FUNCIONES RELACIONADAS CON EL ROL PROFESIONAL PSICOEDUCATIVO
*********************************************************************************
*/

function update_role_monitor_ps($username, $role, $array_students, $boss,$state = 1)
{
    global $DB;
    
    $sql_query = "SELECT id FROM {user} WHERE username ='$username';";
    $id_moodle = $DB->get_record_sql($sql_query);
    
    //se consulta el id del semestre actual
    $sql_query = "select max(id) as id_semestre from {talentospilos_semestre};";
    $semestre = $DB->get_record_sql($sql_query);
    
    $sql_query = "SELECT rol.id as id, rol.nombre_rol as nombre_rol, ur.id as id_user_rol, id_usuario FROM {talentospilos_user_rol} ur INNER JOIN {talentospilos_rol} rol ON rol.id = ur.id_rol  WHERE id_usuario = ".$id_moodle->id." and id_semestre =".$semestre->id_semestre." ;";
    $id_rol_actual = $DB->get_record_sql($sql_query);
    
    
    //se consulta el id del rol
    $sql_query = "SELECT id FROM {talentospilos_rol} WHERE nombre_rol='monitor_ps';";
    $id_role = $DB->get_record_sql($sql_query);
    
    //se consulta el jefe
    $bossid = null;
    if(intval($boss)){
        if (getProfessionals($boss)) $bossid = $boss;
    }

    

    $object_role = new stdClass;
    $object_role->id_rol = $id_role->id;
    $object_role->id_usuario = $id_moodle->id;
    $object_role->estado = $state;
    $object_role->id_semestre = $semestre->id_semestre;
    $object_role->id_jefe = $bossid;

    if(empty($id_rol_actual)){
        $insert_user_rol = $DB->insert_record('talentospilos_user_rol', $object_role, true);
        
        if($insert_user_rol){
            //procesar el array de estudiantes
            $check_assignment = monitor_student_assignment($username, $array_students);
            if($check_assignment == 1){
                return 1;
            }else{
                return $check_assignment;
            }
            
        }
        else{
            
            return 2;
        }
        
    }else{
        // if ($id_rol_actual->nombre_rol != 'monitor_ps'){
        //     $object_role->id = $id_rol_actual->id_user_rol;
        //     $DB->update_record('talentospilos_user_rol',$object_role);
        // }
        //print_r($id_rol_actual);
        if($id_rol_actual->nombre_rol == 'profesional_ps'){
            
            $whereclause = "id_usuario = ".$id_rol_actual->id_usuario;
            $DB->delete_records_select('talentospilos_usuario_prof',$whereclause);
        } 
        
        $object_role->id = $id_rol_actual->id_user_rol;
        $DB->update_record('talentospilos_user_rol',$object_role);
        
        $check_assignment = monitor_student_assignment($username, $array_students);
        
        if($check_assignment ==1){
            return 3;
        }else{
            return $check_assignment;
        }
        
    }
}

/**
 * Función que elimina el ultimo registro de una tabla
 *
 * @see delete_last_register($table_name)
 * @return booleano confirmando el éxito de la operación
 */

function delete_last_register($table_name){
    
    global $DB;
    
    $sql_query = "SELECT MAX(id) FROM {$table_name}";
    $max_id = get_record_sql($sql_query);
    
    $sql_query = "DELETE FROM {$table_name} WHERE id = $max_id->max";
    $success = $DB->execute($sql_query);
    
    return $success;
}


/**
 * Función que actualiza el tipo de profesional de un usuario
 *
 * @see update_professional_type()
 * @return booleano confirmando el éxito de la operación
 */
 
 function update_professional_type($id_user, $name_prof)
 {
     global $DB;
     
     $sql_query = "SELECT id FROM {talentospilos_profesional} WHERE nombre_profesional = $name_prof";
     $id_profesional = $DB->get_record_sql($sql_query);
     
     $object = new stdClass();
     $object->id_usuario = $id_user;
     $object->id_profesional = $id_profesional->id;
     
     $update = $DB->update_record('talentospilos_usuario_prof', $object);
     
     return $update;
 }
 
 /**
 * Función que verifica si un registro existe en la tabla usuario_profesional
 *
 * @see record_check_professional($id_user, $id_professional)
 * @return boolean
 */
 
 function record_check_professional($id_user)
 {
     global $DB;
     
     $sql_query = "SELECT id FROM {talentospilos_usuario_prof} WHERE id_usuario = $id_user";
     $check = $DB->get_record_sql($sql_query);
     
     print_r(empty($check));
 }




/**
 * Función que relaciona a un conjunto de estudiantes con un monitor
 *
 * @see monitor_student_assignment()
 * @return booleano confirmando el éxito de la operación
 */
function monitor_student_assignment($username_monitor, $array_students)
{
    global $DB;
    
    try{
        $sql_query = "SELECT id FROM {user} WHERE username = '$username_monitor'";
        $idmonitor = $DB->get_record_sql($sql_query);
        
        $first_insertion_sql = "SELECT MAX(id) FROM {talentospilos_monitor_estud};";
        $first_insertion_id = $DB->get_record_sql($first_insertion_sql);
        
        $insert_record = "";
        $array_errors = array();
        $hadErrors = false; 
        
        foreach($array_students as $student)
        {
            
                //$sql_query = "SELECT id FROM {user} WHERE username= '$student'";
                //$studentid = $DB->get_record_sql($sql_query);
                
                //se obtiene el id en la tabla de {talentospilos_usuario} del estudiante
                $studentid = get_userById(array('*'),$student);
                
                if($studentid){
                    //se valida si el estudiante ya tiene asignado un monitor
                    $sql_query = "SELECT u.id as id, username,firstname, lastname FROM {talentospilos_monitor_estud} me INNER JOIN {user} u  ON  u.id = me.id_monitor WHERE me.id_estudiante =".$studentid->idtalentos."";
                    $hasmonitor = $DB->get_record_sql($sql_query);
                
                    if(!$hasmonitor){
                        $object = new stdClass();
                        $object->id_monitor = $idmonitor->id;
                        $object->id_estudiante = $studentid->idtalentos;
              
                        $insert_record = $DB->insert_record('talentospilos_monitor_estud', $object, true);
                
                        if(!$insert_record){
                            $hadErrors = true; 
                            array_push($array_errors, "Error al asignar el estudiante ".$student." al monitor (monitor_student_assignment). Operaciòn de asignaciòn del estudiante anulada.");
                            
                        }
                
                    }elseif($hasmonitor->id != $idmonitor->id){
                        $hadErrors = true; 
                        array_push($array_errors,"El estudiante con codigo ".$student." ya tiene asigando el monitor: ".$hasmonitor->username."-".$hasmonitor->firstname."-".$hasmonitor->lastname.". Operaciòn de asignaciòn del estudiante anulada.");
                    }
                }else{
                    $hadErrors = true; 
                    array_push($array_errors,"El estudiante con codigo '".$student."' no se encontro en la base de datos. Operaciòn de asignaciòn del estudiante anulada.");
                } 
        }
        
        if(!$hadErrors){
            return 1;
        }else{
            $message = "";
            foreach ($array_errors as $error){
                $message .= "*".$error."<br>";
            }
            throw new MyException("Rol Actualizado con los siguientes inconvenientes:<br><hr>".$message);
        }
        
    
    }
    catch(MyException $ex){
        return $ex->getMessage();
    }
    catch(Exception $e){
        $error = "Error en la base de datos(monitor_student_assignment).".$e->getMessage();
        echo $error;
    }
}

/**
 * dropStudentofMonitor
 * 
 * Elimina de base de datos la relacion monitor - estudiante
 * @param $monitor [string] username en moodle del ususario del monitor 
 * @param $student [string] username en moodle del usuario studiante
 * @return void
 **/
 
function dropStudentofMonitor($monitor,$student){
    global $DB;
    
    //idmonitor
    $sql_query = "SELECT id FROM {user} WHERE username = '$monitor'";
    $idmonitor = $DB->get_record_sql($sql_query);
    
    //se obtiene el id en la tabla de {talentospilos_usuario} del estudiante
    $studentid = get_userById(array('idtalentos'),$student);

    //where clause
    $whereclause = "id_monitor = ".$idmonitor->id." AND id_estudiante =".$studentid->idtalentos;
    return $DB->delete_records_select('talentospilos_monitor_estud',$whereclause);

}

function changeMonitor ($oldMonitor, $newMonitor){
    global $DB;
    
    try{
        
        $sql_query ="SELECT  id from {talentospilos_monitor_estud} where id_monitor =".$oldMonitor;
        $result = $DB->get_records_sql($sql_query);
        
        foreach ($result as $row){
            $newObject = new stdClass();
            $newObject->id = $row->id;
            $newObject->id_monitor = $newMonitor;
            $DB->update_record('talentospilos_monitor_estud', $newObject);
        }
        
        return 1;
        
    }catch(Exception $e){
        return $e->getMessage();
    }
    
}

/**
 * Función que retorna los usuarios en el sistema
 *
 * @see get_users_role()
 * @return Array 
 */
 
function get_users_role()
{
    global $DB;
    
    $array = Array();
    
    $sql_query = "SELECT {user}.id, {user}.username, {user}.firstname, {user}.lastname, {talentospilos_rol}.nombre_rol FROM {talentospilos_user_rol} INNER JOIN {user} ON {talentospilos_user_rol}.id_usuario = {user}.id 
                                INNER JOIN {talentospilos_rol} ON {talentospilos_user_rol}.id_rol = {talentospilos_rol}.id INNER JOIN {talentospilos_semestre} s ON  s.id = {talentospilos_user_rol}.id_semestre 
                                WHERE {talentospilos_user_rol}.estado = 1 AND s.id = (SELECT MAX(id) FROM {talentospilos_semestre});";
    $users_array = $DB->get_records_sql($sql_query);
    
    foreach ($users_array as $user){
        $user->button = "<a id = \"delete_user\"  ><span  id=\"".$user->id."\" class=\"red glyphicon glyphicon-remove\"></span></a>";
        array_push($array, $user);
    }
    return $array;
}

/** 
 ***********************************
 Fin consultas gestión de  usuarios
 ***********************************
**/

/** 
 **********************
 Consultas asistencias 
 **********************
**/

/**
 * Función que retorna un arreglo con las faltas justificadas e injustificadas
 * de cada estudiante del plan Talentos Pilos
 *
 * @see general_attendance()
 * @return array de objetos con las faltas justificas e injustificadas de un estudiante
 */
function general_attendance($semestre)
{
    global $DB;

    $user_report = array();
    
    $sql_query = "SELECT id FROM {course_categories} WHERE name LIKE '%TALENTOS PILOS'";
    $id_category_pilos = $DB->get_record_sql($sql_query);
    
    $sql_query = "SELECT id FROM {talentospilos_semestre} WHERE nombre = '$semestre'";
    $id_semestre = $DB->get_record_sql($sql_query);

    $sql_query = "SELECT id FROM {course} WHERE category = CAST(".$id_category_pilos->id." AS INT)";
    $id_courses = $DB->get_records_sql($sql_query);
    
    $sql_query = "SELECT SUBSTRING({user}.username FROM 1 FOR 7) AS codigoestudiante, {user}.lastname AS apellidos, {user}.firstname AS nombres, COUNT({attendance_statuses}.description) AS faltasinjustificadas
                FROM ({attendance} INNER JOIN {attendance_sessions} ON {attendance}.id = {attendance_sessions}.attendanceid) INNER JOIN {attendance_log} ON {attendance_sessions}.id = {attendance_log}.sessionid INNER JOIN {attendance_statuses} ON {attendance_log}.statusid = {attendance_statuses}.id  
                INNER JOIN {user} ON {attendance_log}.studentid = {user}.id
                INNER JOIN {course} ON {course}.id = {attendance}.course
                INNER JOIN (SELECT {user_info_data}.userid, {user_info_data}.data  FROM {user_info_data} INNER JOIN {user_info_field} ON {user_info_data}.fieldid = {user_info_field}.id WHERE {user_info_field}.shortname = 'idtalentos') AS fieldsadd
                ON fieldsadd.userid = {user}.id
                INNER JOIN {talentospilos_usuario} ON CAST({talentospilos_usuario}.id AS VARCHAR) = fieldsadd.data
                INNER JOIN {talentospilos_cursos} ON {talentospilos_cursos}.id_curso = {course}.id
                WHERE {attendance_statuses}.description = 'Falta injustificada' AND {talentospilos_cursos}.id_semestre = $id_semestre->id AND {talentospilos_usuario}.estado = 'ACTIVO' 
                GROUP BY codigoestudiante, apellidos, nombres";
                 
    $attendance_report = $DB->get_records_sql($sql_query, null);
    
    $sql_query = "SELECT SUBSTRING({user}.username FROM 1 FOR 7) AS codigoestudiante, {user}.lastname AS apellidos, {user}.firstname AS nombres, COUNT({attendance_statuses}.description) AS faltasjustificadas
                FROM ({attendance} INNER JOIN {attendance_sessions} ON {attendance}.id = {attendance_sessions}.attendanceid) INNER JOIN {attendance_log} ON {attendance_sessions}.id = {attendance_log}.sessionid INNER JOIN {attendance_statuses} ON {attendance_log}.statusid = {attendance_statuses}.id
                INNER JOIN {user} ON {attendance_log}.studentid = {user}.id
                INNER JOIN {course} ON {course}.id = {attendance}.course
                INNER JOIN (SELECT {user_info_data}.userid, {user_info_data}.data  FROM {user_info_data} INNER JOIN {user_info_field} ON {user_info_data}.fieldid = {user_info_field}.id WHERE {user_info_field}.shortname = 'idtalentos') AS fieldsadd
                ON fieldsadd.userid = {user}.id
                INNER JOIN {talentospilos_usuario} ON CAST({talentospilos_usuario}.id AS VARCHAR) = fieldsadd.data
                INNER JOIN {talentospilos_cursos} ON {talentospilos_cursos}.id_curso = {course}.id
                WHERE {attendance_statuses}.description = 'Falta justificada' AND {talentospilos_cursos}.id_semestre = $id_semestre->id AND {talentospilos_usuario}.estado = 'ACTIVO' 
                GROUP BY codigoEstudiante, apellidos, nombres";
                
    $attendance_report_justified = $DB->get_records_sql($sql_query, null);
    
    foreach ($attendance_report as $report)
    {
        $count = 0;
        foreach($attendance_report_justified as $justified)
        {
            if($report->codigoestudiante == $justified->codigoestudiante)
            {
                $report->faltasjustificadas = $justified->faltasjustificadas;
                unset($attendance_report_justified[$justified->codigoestudiante]);
                $count = $count + 1;
                break;
            }
        }
        if($count == 0)
        {
            $report->faltasjustificadas = 0;
        }
        
    }
    foreach($attendance_report_justified as $justified)
    {
        $justified->faltasinjustificadas = 0;
    }

    $result = array_merge($attendance_report, $attendance_report_justified);
    
    foreach($result as $val)
    {
        $val->totalfaltas = (int) $val->faltasjustificadas + (int)$val->faltasinjustificadas;
    }
    return $result;
    // print_r($result);
}

/**
 * Función que retorna las faltas de cada en estudiante en cada curso 
 * monitoreado desde el Plan Talentos Pilos
 *
 * @see attendance_by_course()
 * @return array de objetos con las faltas justificas e injustificadas de un estudiante por curso matriculado
 */
function attendance_by_course($code_student)
{
    global $DB;
    
    $user_report = array();
    
    $sql_query = "SELECT id FROM {user} WHERE username LIKE '$code_student%'";
    $id_user_moodle = $DB->get_record_sql($sql_query);
    
    $sql_query = "SELECT id FROM {talentospilos_semestre} WHERE nombre ='".get_current_semester()->nombre."';";
    $id_current_semester = $DB->get_record_sql($sql_query);
    
    $sql_query = "SELECT fecha_inicio::DATE FROM {talentospilos_semestre} WHERE id = $id_current_semester->id";
    $start_date = $DB->get_record_sql($sql_query);
    
    $sql_query = "SELECT fecha_fin FROM {talentospilos_semestre} WHERE id = $id_current_semester->id";
    $end_date = $DB->get_record_sql($sql_query);
    
    
    // $sql_query = "SELECT courses.timecreated AS tcreated
    //               FROM {user_enrolments} AS userEnrolments INNER JOIN {enrol} AS enrols ON userEnrolments.enrolid = enrols.id 
    //                                                       INNER JOIN {course} AS courses ON enrols.courseid = courses.id 
    //               WHERE userEnrolments.userid = $id_user_moodle->id";
                          
    // $courses = $DB->get_record_sql($sql_query);

    $sql_query = "SELECT {course}.id AS idcourse, {course}.fullname AS coursename, COUNT({attendance_statuses}.description) AS injustifiedabsence FROM ({attendance} INNER JOIN {attendance_sessions} ON {attendance}.id = {attendance_sessions}.attendanceid)
                    INNER JOIN {attendance_log} ON {attendance_sessions}.id = {attendance_log}.sessionid INNER JOIN {attendance_statuses} ON {attendance_log}.statusid = {attendance_statuses}.id
                    INNER JOIN {user} ON {attendance_log}.studentid = {user}.id
                    INNER JOIN {course} ON {course}.id = {attendance}.course
                    INNER JOIN (SELECT {user_info_data}.userid, {user_info_data}.data  FROM {user_info_data} INNER JOIN {user_info_field} ON {user_info_data}.fieldid = {user_info_field}.id WHERE {user_info_field}.shortname = 'idtalentos') AS fieldsadd
                    ON fieldsadd.userid = {user}.id
                    INNER JOIN {talentospilos_usuario} ON {talentospilos_usuario}.id = CAST(fieldsadd.data AS INT)
                    INNER JOIN (SELECT courses.id AS idcourse, courses.timecreated AS tcreated
                                FROM {user_enrolments} AS userEnrolments INNER JOIN {enrol} AS enrols ON userEnrolments.enrolid = enrols.id 
                                                                         INNER JOIN {course} AS courses ON enrols.courseid = courses.id 
                                WHERE userEnrolments.userid = $id_user_moodle->id AND to_timestamp(courses.timecreated) < (SELECT fecha_fin::DATE FROM {talentospilos_semestre} WHERE id = $id_current_semester->id) 
                                								                  AND to_timestamp(courses.timecreated) > (SELECT fecha_inicio::DATE - INTERVAL '30 days' FROM {talentospilos_semestre} WHERE id = $id_current_semester->id)) 
                                AS coursesSemester ON coursesSemester.idcourse = {course}.id
                    WHERE {attendance_statuses}.description = 'Falta injustificada' AND {user}.id = $id_user_moodle->id
                    GROUP BY {course}.id, coursename";
                    
    $attendance_report_injustified = $DB->get_records_sql($sql_query, null);
    
    $sql_query = "SELECT {course}.id AS idcourse, {course}.fullname AS coursename, COUNT({attendance_statuses}.description) AS justifiedabsence FROM ({attendance} INNER JOIN {attendance_sessions} ON {attendance}.id = {attendance_sessions}.attendanceid)
                    INNER JOIN {attendance_log} ON {attendance_sessions}.id = {attendance_log}.sessionid INNER JOIN {attendance_statuses} ON {attendance_log}.statusid = {attendance_statuses}.id
                    INNER JOIN {user} ON {attendance_log}.studentid = {user}.id
                    INNER JOIN {course} ON {course}.id = {attendance}.course
                    INNER JOIN (SELECT {user_info_data}.userid, {user_info_data}.data  FROM {user_info_data} INNER JOIN {user_info_field} ON {user_info_data}.fieldid = {user_info_field}.id WHERE {user_info_field}.shortname = 'idtalentos') AS fieldsadd
                    ON fieldsadd.userid = {user}.id
                    INNER JOIN {talentospilos_usuario} ON {talentospilos_usuario}.id = CAST(fieldsadd.data AS INT)
                    INNER JOIN (SELECT courses.id AS idcourse, courses.timecreated AS tcreated
                                FROM {user_enrolments} AS userEnrolments INNER JOIN {enrol} AS enrols ON userEnrolments.enrolid = enrols.id 
                                                                         INNER JOIN {course} AS courses ON enrols.courseid = courses.id 
                                WHERE userEnrolments.userid = $id_user_moodle->id AND to_timestamp(courses.timecreated) < (SELECT fecha_fin::DATE FROM {talentospilos_semestre} WHERE id = $id_current_semester->id) 
                                								                  AND to_timestamp(courses.timecreated) > (SELECT fecha_inicio::DATE - INTERVAL '30 days' FROM {talentospilos_semestre} WHERE id = $id_current_semester->id)) 
                                AS coursesSemester ON coursesSemester.idcourse = {course}.id
                    WHERE {attendance_statuses}.description = 'Falta justificada' AND {user}.id = $id_user_moodle->id
                    GROUP BY {course}.id, coursename";
                    
    $attendance_report_justified = $DB->get_records_sql($sql_query, null);
    
    foreach ($attendance_report_injustified as $report)
    {
        $count = 0;
        foreach($attendance_report_justified as $justified)
        {
            if($report->coursename == $justified->coursename)
            {
                $report->justifiedabsence = $justified->justifiedabsence;
                unset($attendance_report_justified[$justified->idcourse]);
                $count = $count + 1;
                break;
            }
        }
        if($count == 0)
        {
            $report->justifiedabsence = 0;
        }
        
    }
    foreach($attendance_report_justified as $justified)
    {
        $justified->injustifiedabsence = 0;
    }
    
    $result = array_merge($attendance_report_injustified, $attendance_report_justified);
    
    foreach($result as $val)
    {
        $val->total = (int)$val->justifiedabsence + (int)$val->injustifiedabsence;
    }
    
    // print_r($result);
    
    return $result;
}

// Testing
// attendance_by_course('1673003');

/**
 * Función que retorna las faltas de cada en estudiante en cada semestre cursado
 * exceptuando el semestre actual
 *
 * @see attendance_by_semester()
 * @return array de objetos con las faltas justificas e injustificadas de un estudiante por semestre cursado exceptuando el actual
 * 
 */
 function attendance_by_semester($code_student) 
 {
    global $DB;
    
    $user_report = array();
    
    $sql_query = "SELECT id FROM {user} WHERE username LIKE '$code_student%'";
    $id_user_moodle = $DB->get_record_sql($sql_query);
    
    $sql_query = "SELECT id FROM {talentospilos_semestre} WHERE nombre='".get_current_semester()->nombre."';";
    $id_current_semester = $DB->get_record_sql($sql_query);

    $sql_query = "SELECT coursesSemester.semesterid AS idsemester, coursesSemester.semestersname AS semestername, COUNT({attendance_statuses}.description) AS injustifiedabsence 
                  FROM ({attendance} INNER JOIN {attendance_sessions} ON {attendance}.id = {attendance_sessions}.attendanceid)
                                    INNER JOIN {attendance_log} ON {attendance_sessions}.id = {attendance_log}.sessionid INNER JOIN {attendance_statuses} ON {attendance_log}.statusid = {attendance_statuses}.id
                                    INNER JOIN {user} ON {attendance_log}.studentid = {user}.id
                                    INNER JOIN {course} ON {course}.id = {attendance}.course
                                    INNER JOIN (SELECT {user_info_data}.userid, {user_info_data}.data  
                                                FROM {user_info_data} INNER JOIN {user_info_field} ON {user_info_data}.fieldid = {user_info_field}.id 
                                                WHERE {user_info_field}.shortname = 'idtalentos') AS fieldsadd
                                        ON fieldsadd.userid = {user}.id
                                    INNER JOIN (SELECT courses.id AS idcourse, courses.timecreated AS tcreated, semesters.id AS semesterid, semesters.nombre AS semestersname
                                                FROM {user_enrolments} AS userEnrolments INNER JOIN {enrol} AS enrols ON userEnrolments.enrolid = enrols.id 
                                                                                         INNER JOIN {course} AS courses ON enrols.courseid = courses.id
                                                                                         INNER JOIN {talentospilos_semestre} AS semesters ON (to_timestamp(courses.timecreated) > semesters.fecha_inicio::DATE - INTERVAL '30 days'
                                                                                                                                              AND (to_timestamp(courses.timecreated) < semesters.fecha_fin::DATE)) 
                                                WHERE userEnrolments.userid = $id_user_moodle->id) AS coursesSemester ON coursesSemester.idcourse = {course}.id
                  WHERE {attendance_statuses}.description = 'Falta injustificada' AND coursesSemester.semesterid <> $id_current_semester->id 
                  GROUP BY idsemester, semestername;";
                    
    $attendance_report_injustified = $DB->get_records_sql($sql_query, null);
    
    $sql_query = "SELECT coursesSemester.semesterid AS idsemester, coursesSemester.semestersname AS semestername, COUNT({attendance_statuses}.description) AS justifiedabsence 
                  FROM ({attendance} INNER JOIN {attendance_sessions} ON {attendance}.id = {attendance_sessions}.attendanceid)
                                    INNER JOIN {attendance_log} ON {attendance_sessions}.id = {attendance_log}.sessionid INNER JOIN {attendance_statuses} ON {attendance_log}.statusid = {attendance_statuses}.id
                                    INNER JOIN {user} ON {attendance_log}.studentid = {user}.id
                                    INNER JOIN {course} ON {course}.id = {attendance}.course
                                    INNER JOIN (SELECT {user_info_data}.userid, {user_info_data}.data  
                                                FROM {user_info_data} INNER JOIN {user_info_field} ON {user_info_data}.fieldid = {user_info_field}.id 
                                                WHERE {user_info_field}.shortname = 'idtalentos') AS fieldsadd
                                        ON fieldsadd.userid = {user}.id
                                    INNER JOIN (SELECT courses.id AS idcourse, courses.timecreated AS tcreated, semesters.id AS semesterid, semesters.nombre AS semestersname
                                                FROM {user_enrolments} AS userEnrolments INNER JOIN {enrol} AS enrols ON userEnrolments.enrolid = enrols.id 
                                                                                         INNER JOIN {course} AS courses ON enrols.courseid = courses.id
                                                                                         INNER JOIN {talentospilos_semestre} AS semesters ON (to_timestamp(courses.timecreated) > semesters.fecha_inicio::DATE - INTERVAL '30 days'
                                                                                                                                              AND (to_timestamp(courses.timecreated) < semesters.fecha_fin::DATE)) 
                                                WHERE userEnrolments.userid = $id_user_moodle->id) AS coursesSemester ON coursesSemester.idcourse = {course}.id
                  WHERE {attendance_statuses}.description = 'Falta justificada' AND coursesSemester.semesterid <> $id_current_semester->id
                  GROUP BY idsemester, semestername;";
                    
    $attendance_report_justified = $DB->get_records_sql($sql_query, null);
    
    foreach ($attendance_report_injustified as $report)
    {
        $count = 0;
        foreach($attendance_report_justified as $justified)
        {
            if($report->idsemester == $justified->idsemester)
            {
                $report->justifiedabsence = $justified->justifiedabsence;
                unset($attendance_report_justified[$justified->idsemester]);
                $count = $count + 1;
                break;
            }
        }
        if($count == 0)
        {
            $report->justifiedabsence = 0;
        }
        
    }
    foreach($attendance_report_justified as $justified)
    {
        $justified->injustifiedabsence = 0;
    }
    
    $result = array_merge($attendance_report_injustified, $attendance_report_justified);
    
    foreach($result as $val)
    {
        $val->total = (int)$val->justifiedabsence + (int)$val->injustifiedabsence;
    }
    // /($result);
    return $result;
}

//Testing
// attendance_by_semester('1673003'); 

 /**
 * Función que retorna el semestre actual a partir de la fecha del sistema
 *
 * @see get_current_semester()
 * @return cadena de texto que representa el semestre actual
 */
function get_current_semester_by_date()
{
  $time = time();
  $current_mont = date("m", $time);
  $current_year = date("Y", $time);
  
  if($current_mont > 1 && $current_mont < 7)
  {
      $current_semester = $current_year."A";
  }
  else if($current_mont > 6 && $current_mont <= 12)
  {
      $current_semester = $current_year."B";
  }
  else
  {
      $current_semester = "Error al calcular el semestre actual";
  }
  
  return $current_semester;
}

 /**
 * Función que retorna el semestre actual 
 *
 * @see get_current_semester()
 * @return cadena de texto que representa el semestre actual
 */
 
 function get_current_semester(){
     
     global $DB;
     
     $sql_query = "SELECT id AS max, nombre FROM {talentospilos_semestre} WHERE id = (SELECT MAX(id) FROM {talentospilos_semestre})";
     
     $current_semester = $DB->get_record_sql($sql_query);
     
     return $current_semester;
 }
/** 
 **********************
 Fin consultas asistencias 
 **********************
**/

function getConcurrentCohortsSPP(){
    global $DB;
    $sql_query="SELECT idnumber, name, timecreated FROM {cohort} WHERE idnumber LIKE 'SP%' ORDER BY timecreated DESC;";
    $result = $DB->get_records_sql($sql_query);
    return $result;
}

function getConcurrentEnfasisSPP(){
    global $DB;
    $sql_query="SELECT  nombre FROM {talentospilos_enfasis};";
    $result = $DB->get_records_sql($sql_query);
    return $result;
}

function insertSeguimiento($object, $id_est){
    global $DB;
    $id_seg = $DB->insert_record('talentospilos_seguimiento', $object,true);
    return insertSegEst($id_seg, $id_est);
}

function insertSegEst($id_seg, $id_est){
    global $DB;
    $object_seg_est = new stdClass();
    $id_seg_est = false;
    foreach ($id_est as $id){
        $object_seg_est->id_estudiante = $id;
        $object_seg_est->id_seguimiento = $id_seg;
        $id_seg_est= $DB->insert_record('talentospilos_seg_estudiante', $object_seg_est,true);
    }
    return $id_seg_est;
}

function getSeguimiento($id_est = null, $id_seg = null, $tipo){
    global $DB;
    
    $sql_query="SELECT *, seg.id as id_seg from {talentospilos_seguimiento} seg INNER JOIN {talentospilos_seg_estudiante} seges  on seg.id = seges.id_seguimiento  where seg.tipo ='".$tipo."' ;";
    
    if($id_est != null){
        $sql_query = trim($sql_query,";");
        $sql_query .= " AND seges.id_estudiante =".$id_est.";";
    }
    
    if($id_seg != null){
      $sql_query = trim($sql_query,";");
      $sql_query.= " AND seg.id =".$id_seg.";";
   
    }
    //print_r($sql_query);
    //print_r($DB->get_records_sql($sql_query));
   return $DB->get_records_sql($sql_query);
}


function getSeguimientoOrderBySemester($id_est = null, $tipo,$idsemester = null){
    global $DB;
    $result = getSeguimiento($id_est, null,$tipo );
    
    $seguimientos = array();
    
    foreach ($result as $r){
        array_push($seguimientos, $r);
    }
    
    foreach($seguimientos as $r){
        $r->fecha = date('d-m-Y', $r->fecha);
    }
    
    $lastsemestre = -1;
    
    $sql_query = "select * from {talentospilos_semestre} ";
    if($idsemester != null){
        $sql_query .= " WHERE id = ".$idsemester;
    }else{
        $userid = $DB->get_record_sql("select userid from {user_info_data} d inner join {user_info_field} f on d.fieldid = f.id where f.shortname='idtalentos' and d.data='169';");
        $firstsemester = getIdFirstSemester($userid->userid);
        $lastsemestre = getIdLastSemester($userid->userid);
        
        $sql_query .= " WHERE id >=".$firstsemester;
        
    }
    $sql_query.=" order by fecha_inicio DESC";
    
    $array_semesters_seguimientos =  array();
    
    if($lastsemestre && $firstsemester){
        
        $semesters = $DB->get_records_sql($sql_query);
        $counter = 0;
        
        
        
        $sql_query ="select * from {talentospilos_semestre} where id = ".$lastsemestre;
        $lastsemestreinfo = $DB->get_record_sql($sql_query);
        
        
        foreach ($semesters as $semester){
            
            if($lastsemestreinfo && (strtotime($semester->fecha_inicio) <= strtotime($lastsemestreinfo->fecha_inicio))){ //se valida que solo se obtenga la info de los semestres en que se encutra matriculado el estudiante
            
                $semester_object = new stdClass;
                
                $semester_object->id_semester = $semester->id;
                $semester_object->name_semester = $semester->nombre;
                $array_segumietos = array();
                
                
                while(compare_datatimes(strtotime($semester->fecha_inicio), strtotime($semester->fecha_fin),$seguimientos[$counter]->created)){
                    array_push($array_segumietos, $seguimientos[$counter]);
                    $counter+=1;
                    
                    if ($counter == count($seguimientos)){
                        break;
                    }
                    
                }
                $semester_object->promedio = getPormStatus($id_est,$semester->id)->promedio;
                $semester_object->result = $array_segumietos;
                $semester_object->rows = count($array_segumietos);
                array_push($array_semesters_seguimientos, $semester_object);
            }
        }
        
    }
    
    
    $object_seguimientos =  new stdClass();
    
    $promedio = getPormStatus($id_est);
    $object_seguimientos->promedio = $promedio->promedio;
    $object_seguimientos->semesters_segumientos = $array_semesters_seguimientos;
    
    //print_r($object_seguimientos);
    return $object_seguimientos;
}

//getSeguimientoOrderBySemester(169,'PARES');

function compare_datatimes($fecha_inicio, $fecha_fin, $fecha_comparar){
    return (($fecha_comparar >= $fecha_inicio) && ($fecha_comparar < $fecha_fin));
}

function updateSeguimiento_pares($object){
    global $DB;
   
    //$id_Seg = $DB->update_record('talentospilos_seguimiento', $object);
    return $DB->update_record('talentospilos_seguimiento', $object);
}


function getSegumientoByMonitor($id_monitor, $id_seg= null, $tipo){
    global $DB;
    $sql_query= "";
        $sql_query="SELECT seg.id as id_seg, *  from {talentospilos_seguimiento} seg  where seg.id_monitor = ".$id_monitor." AND seg.tipo = '".$tipo."' ;";

    if($id_seg != null){
      $sql_query = trim($sql_query,";");
      $sql_query.= " AND seg.id =".$id_seg.";";
   
    }
    //print_r($sql_query);
    //print_r($DB->get_records_sql($sql_query));
   return $DB->get_records_sql($sql_query);
}

function getPormStatus($id, $idsemester = null){
    global $DB;
    

    $sql_query ="select sum(status) as sum, count(status) as counts from {talentospilos_seguimiento} seg INNER JOIN {talentospilos_seg_estudiante}  seg_es on seg_es.id_seguimiento = seg.id where seg.tipo='PARES' AND seg_es.id_estudiante=".$id.";";
    
    $semester_result= null;
    if($idsemester){
        $semester_query = "SELECT * from {talentospilos_semestre} where id=".$idsemester;
        $semester_result = $DB->get_record_sql($semester_query);
        $sql_query = trim($sql_query,";");
        $sql_query .= " AND seg.created >= ".strtotime($semester_result->fecha_inicio)." AND seg.created < ".strtotime($semester_result->fecha_fin).";";
    }
    
    $operadores_pares = $DB->get_record_sql($sql_query);
    
    
    
    
    //print_r($operadores_pares);
    $sql_query = "select sum(status) as sum, count(status) as counts from {talentospilos_seg_soc_educ} where id_estudiante =".$id.";";
    
    if($semester_result){
        $sql_query = trim($sql_query,";");
        $sql_query .= " AND created >= ".strtotime($semester_result->fecha_inicio)." AND created < ".strtotime($semester_result->fecha_fin).";";
    }
    
    $operadores_socio = $DB->get_record_sql($sql_query);
    
    
    //print_r($operadores_socio);
    $result_pares = new stdClass();
    $result_socio = new stdClass();
    $total_promedio = new stdClass();
    $ponde_pares = 0.5;
    $ponde_socio = 0.5;
        
    if($operadores_pares->counts == 0){
        $operadores_pares->promedio = 1;
        $ponde_socio = 1;
        $ponde_pares = 0;
    }else{
        $promedio = $operadores_pares->sum / $operadores_pares->counts;
        $operadores_pares->promedio =  number_format($promedio,1);
    }
    
    
    if($operadores_socio->counts == 0){
        $operadores_socio->promedio = 1;
        $ponde_socio = 0;
        $ponde_pares = 1;
    }else{
        $promedio = $operadores_socio->sum / $operadores_socio->counts;
        $operadores_socio->promedio =  number_format($promedio,1);
    }    
        
        
    $promedio = $operadores_pares->promedio*$ponde_pares + $operadores_socio->promedio*$ponde_socio;
    $total_promedio->promedio =  number_format($promedio,1);
    

    
    if($operadores_socio->counts == 0 && $operadores_pares->counts == 0 ) $total_promedio->promedio = 0;
   
    //print_r($total_promedio);
    return $total_promedio;
}
//getPormStatus(169, 1);

function getStudentsGrupal($id_monitor){
    global $DB;
    $sql_query = "SELECT * FROM (SELECT * FROM 
                    (SELECT *, id AS id_user FROM {user}) AS userm 
                            INNER JOIN 
                            (SELECT * FROM {user_info_data} as d INNER JOIN {user_info_field} as f ON d.fieldid = f.id WHERE f.shortname ='idtalentos' AND data <> '') AS field 
                            ON userm. id_user = field.userid ) AS usermoodle 
                        INNER JOIN 
                        (SELECT *,id AS idtalentos FROM {talentospilos_usuario}) AS usuario 
                        ON CAST( usermoodle.data AS INT) = usuario.id 
                    where  idtalentos in (select id_estudiante from {talentospilos_monitor_estud} where id_monitor =".$id_monitor.");";
    
   $result = $DB->get_records_sql($sql_query);
   //print_r($result);
   return $result;
}

function getEstudiantesSegGrupal($id_seg){
    global $DB;
    $sql_query = "SELECT id_estudiante FROM {talentospilos_seg_estudiante} WHERE id_seguimiento =".$id_seg;
    return $DB->get_records_sql($sql_query);
}

function insertPrimerAcerca($object){
    global $DB;
    return $DB->insert_record('talentospilos_primer_acerca',$object);
}

function updatePrimerAcerca($object){
    global $DB;
    return $DB->update_record('talentospilos_primer_acerca', $object);
}

function getPrimerAcerca($idtalentos){
    global $DB;
    $sql_query = "SELECT * FROM {talentospilos_primer_acerca} WHERE id_estudiante =".$idtalentos;
    return $DB->get_records_sql($sql_query);
}

function dropTalentosFromSeg($idSeg,$id_est){
    global $DB;
    $whereclause = "id_seguimiento =".$idSeg." AND id_estudiante=".$id_est;
    return $DB->delete_records_select('talentospilos_seg_estudiante',$whereclause);
}


function insertnewAcompaSocio($record){
    global $DB;
    return $DB->insert_record('talentospilos_socioeducativo',$record);
}

function insertInfoEconomica($infoEconomica){
    global $DB;
    $result = false;
    foreach ($infoEconomica as $object){
        $result = $DB->insert_record('talentospilos_economia', $object);
    }
    
    return $result; 
}
function insertInfoFamilia($infoFamilia){
    global $DB;
     $result = false;
    foreach ($infoFamilia as $object){
        $result = $DB->insert_record('talentospilos_familia', $object);
    }
    
    return $result; 
}

function getAcompaSocio($idtalentos){
    global $DB;
    $sql_query = "SELECT * FROM {talentospilos_socioeducativo} WHERE id_estudiante =".$idtalentos;
    return $DB->get_records_sql($sql_query);
}

function getEconomia($idtalentos,$tipo ){
    global $DB;
    $sql_query = "SELECT * FROM {talentospilos_economia} WHERE id_estudiante =".$idtalentos." AND tipo='".$tipo."';";
    return $DB->get_records_sql($sql_query);
}

function getFamilia($idtalentos){
    global $DB;
    $sql_query = "SELECT * FROM {talentospilos_familia} WHERE id_estudiante =".$idtalentos.";";
    return $DB->get_records_sql($sql_query);
}

function updateAcompaSocio($object){
    global $DB;
    return $DB->update_record('talentospilos_socioeducativo', $object);
}

function updateInfoEconomica($object){
    global $DB;
    return $DB->update_record('talentospilos_economia', $object);
}

function updateInfoFamilia($object){
    global $DB;
    return $DB->update_record('talentospilos_familia', $object);
}

function dropInfoEconomica($idInfo){
    global $DB;
    $whereclause = "id =".$idInfo;
    //print_r($DB->delete_records_select('talentospilos_economia',$whereclause));
    return $DB->delete_records_select('talentospilos_economia',$whereclause);
}

function dropFamilia($idInfo){
    global $DB;
    $whereclause = "id =".$idInfo;
    //print_r($DB->delete_records_select('talentospilos_economia',$whereclause));
    return $DB->delete_records_select('talentospilos_familia',$whereclause);
}

function insertSegSocio($object){
    global $DB;
    return $DB->insert_record('talentospilos_seg_soc_educ',$object);
}

function updateSegSocio($object){
    global $DB;
    return $DB->update_record('talentospilos_seg_soc_educ', $object);
}

function getSegSocio($idtalentos, $idseg = null){
    global $DB;
    $sql_query = "SELECT * FROM {talentospilos_seg_soc_educ} WHERE id_estudiante =".$idtalentos.";";
    if( $idseg != null){
        $sql_query = trim($sql_query,";");
        $sql_query.= " AND id =".$idseg.";";
    }
    //print_r($sql_query);
    return $DB->get_records_sql($sql_query);
}

function getSegSocioOrderBySemester($idtalentos, $idseg = null, $idsemester= null){
    global $DB;
    $result = getSegSocio($idtalentos, null);
    
    $seguimientos = array();
    
    foreach ($result as $r){
        array_push($seguimientos, $r);
    }
    
    foreach($seguimientos as $r){
        $r->fecha = date('d-m-Y', $r->fecha);
    }
    
    //print_r($seguimientos);
    
    $sql_query = "select * from {talentospilos_semestre} ";
    if($idsemester != null){
        $sql_query .= " WHERE id = ".$idsemester;
    }else{
        $userid = $DB->get_record_sql("select userid from {user_info_data} d inner join {user_info_field} f on d.fieldid = f.id where f.shortname='idtalentos' and d.data='169';");
        $firstsemester = getIdFirstSemester($userid->userid);
        $sql_query .= " WHERE id >=".$firstsemester;
    }
    
    $sql_query.=" order by fecha_inicio DESC";
    
    $semesters = $DB->get_records_sql($sql_query);
    
    $object_seguimientos =  new stdClass();
    
    $array_semesters_seguimientos =  array();

    $counter = 0;
    foreach ($semesters as $semester){
        
        $semester_object = new stdClass;
        
        $semester_object->id_semester = $semester->id;
        $semester_object->name_semester = $semester->nombre;
        $array_segumietos = array();
        
        
        while(compare_datatimes(strtotime($semester->fecha_inicio), strtotime($semester->fecha_fin),$seguimientos[$counter]->created)){
            //print_r("fecha segumiento:".$seguimientos[$counter].)
            array_push($array_segumietos, $seguimientos[$counter]);
            $counter+=1;
            
            if ($counter == count($seguimientos)){
                break;
            }
            
        }
        
        $semester_object->result = $array_segumietos;
        $semester_object->rows = count($array_segumietos);
        array_push($array_semesters_seguimientos, $semester_object);
    }
    
    $promedio = getPormStatus($idtalentos);
    
    $object_seguimientos->promedio = $promedio->promedio;
    $object_seguimientos->semesters_segumientos = $array_semesters_seguimientos;
    //print_r("adaf<br>");
    //print_r($object_seguimientos);
    return $object_seguimientos;
}

//getSegSocioOrderBySemester(169);

function getUserMoodleByid($id){
    global $DB;
    $sql_query = "SELECT * FROM {user} WHERE id =".$id.";";
    return $DB->get_record_sql($sql_query);
}

/**
 * Return final grade of a course for a single student
 *
 * @param string $username_student Is te username of moodlesite 
 * @return array() of stdClass object representing courses and grades for single student
 */

function get_grades_courses_student_semester($id_student, $coursedescripctions){
    //print_r("<br><hr>".$id_student."<hr><br>");
    global $DB;
    
    // var_dump($id_student);
    
    $id_first_semester = getIdFirstSemester($id_student);
    
    // var_dump($id_first_semester);
    
    $semesters = get_semesters_student($id_first_semester);
    
    // var_dump($semesters);
    
    // print_r($semesters);
    
    $courses = get_courses_by_student($id_student, $coursedescripctions);
    $array_semesters_courses =  array();
   
    $counter = 0;
    foreach ($semesters as $semester){
        
        $semester_object = new stdClass;
        
        $semester_object->id_semester = $semester->id;
        $semester_object->name_semester = $semester->nombre;
        $array_courses = array();
        
        $coincide =false;
        
        if ($courses){
            while($coincide = compare_dates(strtotime($semester->fecha_inicio), strtotime($semester->fecha_fin), strtotime( $courses[$counter]->time_created))){
                array_push($array_courses, $courses[$counter]);
                $counter+=1;
                
                if ($counter == count($courses)){
                    break;
                }
                
            }
        }
        if($coincide || $counter != 0){
            $semester_object->courses = $array_courses;
            array_push($array_semesters_courses, $semester_object);
        }
    }
    // print_r($array_semesters_courses);
    return $array_semesters_courses; 
}
 
// Test
// get_grades_courses_student_semester(10304);

function compare_dates($fecha_inicio, $fecha_fin, $fecha_comparar){
    
    $fecha_inicio = new DateTime(date('Y-m-d',$fecha_inicio));
    date_add($fecha_inicio, date_interval_create_from_date_string('-30 days'));
    return (($fecha_comparar >= strtotime($fecha_inicio->format('Y-m-d'))) && ($fecha_comparar <= $fecha_fin));
}

/**
 * Return array of semesters of a student
 *
 * @param string $username_student Is te username of moodlesite 
 * @return array() of stdClass object representing semesters of a student
 */
 function get_semesters_student($id_first_semester){
     
     global $DB;
     
     $sql_query = "SELECT id, nombre, fecha_inicio::DATE, fecha_fin::DATE FROM {talentospilos_semestre} WHERE id >= $id_first_semester ORDER BY {talentospilos_semestre}.fecha_inicio DESC";
     
     $result_query = $DB->get_records_sql($sql_query);
     
     $semesters_array = array();
     
     foreach ($result_query as $result){
       array_push($semesters_array, $result);
     }
    //print_r($semesters_array);
    return $semesters_array;
}

// Test
//get_semesters_student(getIdFirstSemester(169));

/**
 * Return courses of a student
 *
 * @param username moodle site
 * @return array of courses 
 */

function get_courses_by_student($id_student, $coursedescripction = false){
    //print_r("<br><br>id: ".$id_student."<br>");
    global $DB;
    
    $sql_query = "SELECT subcourses.id_course, name_course, tgcategories.fullname, to_timestamp(subcourses.time_created)::DATE AS time_created
                  FROM {grade_categories} as tgcategories INNER JOIN
                     (SELECT tcourse.id AS id_course, tcourse.fullname AS name_course, tcourse.timecreated AS time_created 
                     FROM {user}  AS tuser INNER JOIN {user_enrolments}  AS tenrolments ON tuser.id = tenrolments.userid
                          INNER JOIN {enrol}  AS tenrol ON  tenrolments.enrolid = tenrol.id
                          INNER JOIN {course}  AS tcourse ON tcourse.id = tenrol.courseid
                     WHERE tuser.id = $id_student) AS subcourses
                     ON subcourses.id_course = tgcategories.courseid
                  ORDER BY subcourses.time_created DESC;";
    $result_query = $DB->get_records_sql($sql_query);
    
    if($coursedescripction){
        
        $courses_array = array();
        foreach ($result_query as $result){
            
            $result->grade = grade_get_course_grade($id_student, $result->id_course)->grade;
            $result->descriptions = getCoursegradelib($result->id_course, $id_student);
            array_push($courses_array, $result);
        }
        return $courses_array;
        
    }else{
        //print_r($result_query);
        return $result_query;
    }
}

//Test
//get_courses_by_student(3);

/**
 * Return total of semesters 
 *
 * @param null
 * @return integer representing the total number of semesters registered in db
 */
 
 function get_total_numbers_semesters(){
     
     global $DB;
     
     $sql_query = "SELECT COUNT(id) FROM {talentospilos_semestre}";
     $total_semesters = $DB->get_record_sql($sql_query);

     return $total_semesters->count;
}

/**
 * Return id of first semester of a student
 *
 * @param int --- id student 
 * @return int --- id first semester
 */
function getIdFirstSemester($id){
    try {
        global $DB;
        
        $sql_query = "SELECT timecreated from {user} where id = ".$id;
        $result = $DB->get_record_sql($sql_query);
        
        if(!$result) throw new Exception('error al consultar fecha de creación');
        
        $timecreated = $result->timecreated;
        
        if($timecreated <= 0){
            
            $sql_query = "SELECT MIN(courses.timecreated)
                          FROM {user_enrolments} AS userEnrolments INNER JOIN {enrol} AS enrols ON userEnrolments.enrolid = enrols.id 
                                                                   INNER JOIN {course} AS courses ON enrols.courseid = courses.id 
                          WHERE userEnrolments.userid = $id";
                          
            $courses = $DB->get_record_sql($sql_query);

            $timecreated = $courses->min;
        }

        $sql_query = "select id, nombre ,fecha_inicio::DATE, fecha_fin::DATE from {talentospilos_semestre} ORDER BY fecha_fin ASC;";
        
        $semesters = $DB->get_records_sql($sql_query);
        
        $id_first_semester = 0; 

        foreach ($semesters as $semester){
            $fecha_inicio = new DateTime($semester->fecha_inicio);

            date_add($fecha_inicio, date_interval_create_from_date_string('-30 days'));

            if((strtotime($fecha_inicio->format('Y-m-d')) <= $timecreated) && ($timecreated <= strtotime($semester->fecha_fin))){
                
                $id_first_semester = $semester->id;
                break;
            }
        }
        
        return $id_first_semester;

    }catch(Exeption $e){
        return "Error en la consulta primer semestre";
    }
}

// Testing
// getIdFirstSemester(103304);
// getIdFirstSemester(103268);
// getIdFirstSemester(6);
// getIdFirstSemester(7);
// getIdFirstSemester(10);

function getIdLastSemester($idmoodle){
    
    $result = get_grades_courses_student_semester($idmoodle);
    if($result){
       return  $result[0]->id_semester;
    }else{
        return false;
    }
    
}


function getCoursegradelib($courseid, $userid){
    /// return tracking object
    //$courseid = 98;
    //$userid = 5;
    
    $context = context_course::instance($courseid);
    
    $gpr = new grade_plugin_return(array('type'=>'report', 'plugin'=>'user', 'courseid'=>$courseid, 'userid'=>$userid));
    $report = new grade_report_user($courseid, $gpr, $context, $userid);
    //echo "si";
    //print_grade_page_head($courseid, 'report', 'user', get_string('pluginname', 'gradereport_user'). ' - '.fullname($report->user));

     if ($report->fill_table()) {
        //print_r($report);
        return $report->print_table(true);
    }
    return null;
}
// getCoursegradelib(98, 5);

//DATOS BASICOS
function getStudentInformation($idTalentos){
    global $DB;
    
    $sql_query = "SELECT usuario.id, usuario.firstname, infor_data.id, infor_data.data, infor_field.shortname, usuario_talentos.sexo, usuario_talentos.id_ciudad_ini, municipios_ini_talentos.nombre AS municipio_procedencia, departamentos_ini_talentos.nombre AS departamento_procedencia, usuario_talentos.id_ciudad_res, municipios_res_talentos.nombre AS municipio_residencia, departamentos_res_talentos.nombre AS departamento_residencia 
    FROM {user} AS usuario 
    INNER JOIN {user_info_data} AS infor_data 
    ON usuario.id = infor_data.userid 
    INNER JOIN {user_info}_field AS infor_field 
    ON infor_data.fieldid = infor_field.id 
    INNER JOIN {talentospilos_usuario} AS usuario_talentos 
    ON  cast(usuario_talentos.id AS varchar) = infor_data.data 
    INNER JOIN {talentospilos_municipio} AS municipios_res_talentos 
    ON municipios_res_talentos.id = usuario_talentos.id_ciudad_res 
    INNER JOIN (SELECT * FROM {talentospilos_municipio}) AS municipios_ini_talentos
    ON municipios_ini_talentos.id = usuario_talentos.id_ciudad_ini 
    INNER JOIN (SELECT * FROM {talentospilos_departamento}) AS departamentos_ini_talentos
    ON municipios_ini_talentos.cod_depto = departamentos_ini_talentos.id
    INNER JOIN (SELECT * FROM {talentospilos_departamento}) as departamentos_res_talentos
    ON municipios_res_talentos.cod_depto = departamentos_res_talentos.id
    WHERE infor_field.shortname = '"+$idTalentos+"';";
    
    $output = $DB->get_records_sql($sql_query);
    
    return $output;
}


//SECTOR PRUEBAS 
// $inf = getStudentInformation('idtalentos');
// print_r($inf);
//dropInfoEconomica(8);
// $infoEconomica = array();
// $object = new stdClass();
//             $object->id_estudiante ='169';
//             $object->concepto = 'sadf';
//             $object->monto = '43';
//             $object->tipo = 'EGRESO';
            
//             array_push($infoEconomica, $object);

// insertInfoEconomica($infoEconomica);

//actualizando funcionalidad
// function addpermiso(){
//     global $DB;
//     $record = new stdClass; 
//     $record->nombre_func = 'f_socioeducativa_mon';
//     $record->descripcion = 'Ficha psicosocial de un estudiante pilos desde un monitor';
//     $DB->insert_record('talentospilos_funcionalidad', $record, false);
// }
// addpermiso();

//

//permiso para monitor
// global $DB;
// $record = new stdClass; 
// $record->id_rol = 4;
// $record->id_permiso = 2; //leer
// $record->id_funcionalidad = 8; //f_socioeducativa_monitor
// $DB->insert_record('talentospilos_permisos_rol', $record, false);

// $record->id_rol = 4;
// $record->id_permiso = 1; //crear
// $record->id_funcionalidad = 8; //f_socioeducativa_monitor
// $DB->insert_record('talentospilos_permisos_rol', $record, false);

// $record->id_rol = 4;
// $record->id_permiso = 3; //actualizar
// $record->id_funcionalidad = 8; //f_socioeducativa_monitor
// $DB->insert_record('talentospilos_permisos_rol', $record, false);
//monitor_student_assignment('1430461-3743', array("1673017"));
//$receiver = get_complete_user_data('id', 2);
//print_r($receiver->username);
//getStudentsGrupal(2)
//getSegumientoByMonitor(2,'GRUPAL');
//get_current_semester();
//getPormStatus(169);
//getSeguimiento(null,1,'GRUPAL');
//getConcurrentCohortsSPP();
// general_attendance('2016B');
// attendance_by_course('1674296');
// attendance_by_semester('1674296');
// get_usersByPopulation(array("Código"),array("SPT12016A","TODOS","ACTIVO","TODOS"));
//get_userById(array('idtalentos'),"1673003-1008");
//record_check_professional(162);
//print_r($u->fecha_nac);
//update_talentosusuario(array("estado"), array("ACTIVO"),"167T4296-1008");
//get_permisos_role(6, "role");
// $array_students = array("1673006-1008", "1673013-1008", "1673046-1008");
//monitor_student_assignment("1430461-3743", array('1673017'));
// checking_role('administrador');
// get_users_role();
// assign_role_profesional_ps('1124153-3743', 'profesional_ps'as, 1, null, null, 'psicologo')
//manage_role_profesional_ps('1430461-3743','profesional_ps','socioeducativo');
?>