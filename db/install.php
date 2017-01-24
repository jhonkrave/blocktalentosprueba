<?php

require_once(dirname(__FILE__).'/../../../config.php');

function xmldb_block_talentospilos_install_recovery()
{
    xmldb_block_talentospilos_install();
}

function xmldb_block_talentospilos_install()
{
	global $DB;
	
	$roles_array = array('administrativo', 'reportes', 'profesional_ps', 'monitor_ps', 'estudiante_t', 'sistemas');
	$descripcion_roles_array = array('Actualizar ficha', 'Rol general para directivos y demas personas que tengan permiso de lectura', 'Profesional Psicosocial', 'Monitor Psicosocial con estudiantes a cargo', 'Estudiante talentos pilos', 'Rol desarrollador');
	$funcionalidades_array = array('carga_csv','reporte_general','f_general','f_academica','f_asistencia','f_socioeducativa_pro','f_socioeducativa_mon', 'gestion_roles');
	$descripcion_funcionalidades_array = array('Carga de información a tablas de la base de datos','Reporte general de estudiantes pilos','Ficha general de un estudiante pilos','Ficha académica de un estudiante pilos','Ficha asistencia de un estudiante pilos','Ficha psicosocial de un estudiante pilos desde un profesional', 'Ficha psicosocial de un estudiante pilos desde un monitor','Gestiona los roles de los usuarios');
	$permisos_array = array('C','R','U','D');
	$descripcion_permisos_array = array('Crear','Leer','Actualizar','Borrar');
	

    for($i = 0; $i < count($roles_array); $i++)
    {
        $record = new stdClass; 
        $record->nombre_rol = $roles_array[$i];
        $record->descripcion = $descripcion_roles_array[$i];
        $DB->insert_record('talentospilos_rol', $record, false);
    }
    for($i = 0; $i < count($funcionalidades_array); $i++)
    {
        $record = new stdClass; 
        $record->nombre_func = $funcionalidades_array[$i];
        $record->descripcion = $descripcion_funcionalidades_array[$i];
        $DB->insert_record('talentospilos_funcionalidad', $record, false);
    }
    for($i = 0; $i < count($permisos_array); $i++)
    {
        $record = new stdClass; 
        $record->permiso = $permisos_array[$i];
        $record->descripcion = $descripcion_permisos_array[$i];
        $DB->insert_record('talentospilos_permisos', $record, false);
    }
    for($i = 1; $i <= 7; $i++)
    {
        for($j = 1; $j <= 4; $j++)
        {
            $record->id_rol = 6;
            $record->id_permiso = $j;
            $record->id_funcionalidad = $i;
            $DB->insert_record('talentospilos_permisos_rol', $record, false);
        }
    }
    
    $record->id_rol = 1;
    $record->id_permiso = 2;
    $record->id_funcionalidad = 2;
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 1;
    $record->id_permiso = 2;
    $record->id_funcionalidad = 3;
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 1;
    $record->id_permiso = 3;
    $record->id_funcionalidad = 3;
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    //permiso para monitor
    $record->id_rol = 4;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 7; //f_socioeducativa_monitor
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 4;
    $record->id_permiso = 1; //crear
    $record->id_funcionalidad = 7; //f_socioeducativa_monitor
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 4;
    $record->id_permiso = 3; //actualizar
    $record->id_funcionalidad = 7; //f_socioeducativa_monitor
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    //permisos para rol profesional
    $record->id_rol = 3;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 6; //f_socioeducativa_profesional
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 3;
    $record->id_permiso = 1; //crear
    $record->id_funcionalidad = 6; //f_socioeducativa_profesional
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 3;
    $record->id_permiso = 3; //actualizar
    $record->id_funcionalidad = 6; //f_socioeducativa_profesional
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 3;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 2; //reporte_general
    $DB->insert_record('talentospilos_permisos_rol', $record, false);

    $record->id_rol = 3;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 3; //f_general
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 3;
    $record->id_permiso = 3; //actualizar
    $record->id_funcionalidad = 3; //f_general
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 3;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 4; //f_academica
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    //permisos para rol reportes
    $record->id_rol = 2;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 2; //reporte_general
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 2;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 3; //f_general
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    $record->id_rol = 2;
    $record->id_permiso = 2; //leer
    $record->id_funcionalidad = 5; //f_asistencia
    $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    // if(($archivo = fopen("../files/10.semestre.csv", "r")) !== FALSE){
    //     $record = new stdClass();
         
    //     while($data = fgetcsv($handle, 100, ","))
    //     {
    //         $record->nombre = $data[0];
    //         $record->fecha_inicio = $data[1];
    //         $record->fecha_fin = $data[2];
    //         $DB->insert_record('talentospilos_semestre', $record);
    //     }
    //     $respuesta = 1;
    // }
    
    
    //$time = time();
    //$current_month = date("m", $time);
    //$current_year = date("Y", $time);
     
    //$record_semester = new stdClass;
    //$initial_year = "2016";
    //$year_aux = "";
     
    //while($initial_year <= $current_year){
         
        //$year_aux = $initial_year."A";
        //$record_semester->nombre=$year_aux;
        //$DB->insert_record('talentospilos_semestre', $record_semester, false);
        //if($current_month >= 6){
            //$year_aux = $initial_year."B";
            //$record_semester->nombre=$year_aux;
            //$DB->insert_record('talentospilos_semestre', $record_semester, false);
        //}
        //$initial_year++;
     //}
    
    // $handle = fopen('../files/departamentos.csv', 'r');
    
    // while($data = fgetcsv($handle, 1000, ","))
    // {
    //     $record->codigodivipola = $data[0];
    //     $record->nombre = $data[1];
        
    //     $DB->insert_record('talentospilos_departamento', $record, false);
    // }
    
    // $handle = fopen("../files/municpios.csv", 'r');
    // while($data = fgetcsv($handle, 100, ","))
    // {  
    //     array_push($array_data, $data);
        
    //     $query = "SELECT id FROM {talentospilos_departamento} WHERE codigodivipola = ".intval($data[1]).";";
           
    //     $result = $DB->get_record_sql($query);
    // }
    // foreach ($array_data as $dat)
    // {
    //     $record->codigodivipola = $dat[0];
    //     $record->cod_depto = $array_id[$count];
    //     $record->nombre = $dat[2];
    //     $DB->insert_record('talentospilos_municipio', $record, false);
    //     $count += 1;
    // }

    set_config('block_talentospilos_late_install', 1);
}
	 


