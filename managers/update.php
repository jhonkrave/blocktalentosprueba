<?php
require_once(dirname(__FILE__). '/../../../config.php');
    global $DB;
    
    // *** creacion nueva tabla ***//
    
    $sql = "CREATE TABLE {talentospilos_instancias} (
                add
            )";
    $result = $DB->execute($sql);
    
    // print_r($result);
    
    // *** Fin creacion nueva tabla ***//
    
    // **** insert a semestre ****
    
    
    // $table = '';
    // $record = new stdClass;
    
    // if(($archivo = fopen("../files/10.semestre.csv", "r")) !== FALSE){
    //     $record = new stdClass();
         
    //     while($data = fgetcsv($archivo, 100, ","))
    //     {
    //         $record->nombre = $data[0];
    //         $record->fecha_inicio = $data[1];
    //         $record->fecha_fin = $data[2];
    //         $DB->insert_record('talentospilos_semestre', $record);
    //     }
    //     $respuesta = 1;
    //     echo $respuesta;
    // }
    // else{
    //     echo "Error al leer el archivo";
    // }
    
    // Profesional psicosocial
    
    // $record->id_rol = 3;
    // $record->id_permiso = 3; //actualizar
    // $record->id_funcionalidad = 6; //f_socioeducativa_profesional
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    // $record->id_rol = 3;
    // $record->id_permiso = 2; //leer
    // $record->id_funcionalidad = 2; //reporte_general
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);

    // $record->id_rol = 3;
    // $record->id_permiso = 2; //leer
    // $record->id_funcionalidad = 3; //f_general
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    // $record->id_rol = 3;
    // $record->id_permiso = 3; //actualizar
    // $record->id_funcionalidad = 3; //f_general
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    // $record->id_rol = 3;
    // $record->id_permiso = 2; //leer
    // $record->id_funcionalidad = 4; //f_academica
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);

    // // Monitor ps
    
    // $record->id_rol = 4;
    // $record->id_permiso = 2; //leer
    // $record->id_funcionalidad = 3; //f_general
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    // $record->id_rol = 4;
    // $record->id_permiso = 3; //actualizar
    // $record->id_funcionalidad = 4; //f_academica
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);
    
    // $record->id_rol = 4;
    // $record->id_permiso = 2; //leer
    // $record->id_funcionalidad = 5; //f_asistencia
    // $DB->insert_record('talentospilos_permisos_rol', $record, false);

    // $sql = "DELETE FROM {talentospilos_user_rol} WHERE id=1";
    // $DB->execute($sql);
    
    // $sql_query = "SELECT id FROM {talentospilos_user_rol}";
    // $id_user_rol = $DB->get_records_sql($sql_query);

    // foreach ($id_user_rol as $id){
    //     $sql = "UPDATE {talentospilos_user_rol} SET id_semestre = 2  WHERE id = $id->id";
        
    //     $DB->execute($sql);
    // }
    
    // Get a cache instance
   // $cache = cache::make_from_params(cache_store::MODE_APPLICATION, 'component', 'area');
 
    //$result = $cache->purge();
    
    //print_r($result);
    // $result will contain the number of items successfully deleted.
    
    //$sql = "ALTER TABLE {talentospilos_seg_soc_educ} DROP COLUMN id_profesional";
    // //$result = $DB->execute($sql);
    
    // $sql = "ALTER TABLE {talentospilos_seg_soc_educ} ADD COLUMN id_profesionalps BIGINT NOT NULL";
    // $result = $DB->execute($sql);
    
    // $sql = "ALTER TABLE {talentospilos_seg_soc_educ} ADD FOREIGN KEY (id_profesionalps) REFERENCES {user}(id)";
    // $sql = "ALTER TABLE {talentospilos_seg_soc_educ} RENAME COLUMN id_profesional TO id_profesionalps";
    // $result = $DB->execute($sql);
    
    // print_r($result);
    
    // $sql = "UPDATE {talentospilos_semestre} SET fecha_inicio = '2016-02-19' where nombre='2016A'";
    // $result = $DB->execute($sql);
    
    // $sql = "UPDATE {talentospilos_semestre} SET fecha_fin = '2016-06-20' where nombre='2016A'";
    // $result = $DB->execute($sql);
    // print_r($result);
    
    // $sql = "UPDATE {talentospilos_semestre} SET fecha_inicio = '2016-08-06' where nombre='2016B'";
    // $result = $DB->execute($sql);
    
    // $sql = "UPDATE {talentospilos_semestre} SET fecha_fin = '2016-12-16' where nombre='2016B'";
    // $result = $DB->execute($sql);
        
    // echo $result;