<?php

// require('query.php');
require_once $CFG->libdir.'/gradelib.php';
require('../../../grade/querylib.php');
require_once '../../../config.php';
require_once $CFG->dirroot.'/grade/lib.php';
require_once $CFG->dirroot.'/grade/report/user/lib.php';

/**
 * Return final grade of a course for a single student
 *
 * @param string $username_student Is te username of moodlesite 
 * @return array() of stdClass object representing courses and grades for single student
 */

function get_grades_courses_student_semester($id_student){
  
    global $DB;
    
    $id_first_semester = getIdFirstSemester($id_student);
    
    $semesters = get_semesters_student($id_first_semester);
    $courses = get_courses_by_student($id_student);
    $array_semesters_courses =  array();
    
    $counter = 0;
    foreach ($semesters as $semester){
        
        $semester_object = new stdClass;
        
        $semester_object->id_semester = $semester->id;
        $semester_object->name_semester = $semester->nombre;
        $array_courses = array();
        
        
        while(compare_dates(strtotime($semester->fecha_inicio), strtotime($semester->fecha_fin), strtotime( $courses[$counter]->time_created))){
            array_push($array_courses, $courses[$counter]);
            $counter+=1;
            
            if ($counter == count($courses)){
                break;
            }
            
        }
        
        $semester_object->courses = $array_courses;
        array_push($array_semesters_courses, $semester_object);
    }
    
     //print_r($array_semesters_courses);
    
    return $array_semesters_courses; 
}
 
// Test
// get_grades_courses_student_semester(171);

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
     
     $sql_query = "SELECT id, nombre, fecha_inicio::DATE, fecha_fin::DATE FROM {talentospilos_semestre} WHERE id >= $id_first_semester ORDER BY {talentospilos_semestre}.id DESC";
     
     $result_query = $DB->get_records_sql($sql_query);
     
     $semesters_array = array();
     
     foreach ($result_query as $result){
       array_push($semesters_array, $result);
     }
    //print_r($semesters_array);
    return $semesters_array;
}

// Test
//get_semesters_student(1);

/**
 * Return courses of a student
 *
 * @param username moodle site
 * @return array of courses 
 */

function get_courses_by_student($id_student){
    
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
    $courses_array = array();
    $courses_id = array();
  
    foreach ($result_query as $result){
        
        $result->grade = grade_get_course_grade($id_student, $result->id_course)->grade;
        $result->descriptions = getCoursegradelib($result->id_course, $id_student);
        array_push($courses_array, $result);
    }
    print_r($courses_array);
    return $courses_array;
}

//Test
get_courses_by_student(98);

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
 *
 * 
 * 
 * 
 */

function getIdFirstSemester($id){
    try {
        global $DB;
        
        $sql_query = "SELECT timecreated from {user} where id = ".$id;
        $timecreated = $DB->get_record_sql($sql_query);
        if(!$timecreated) throw new Exception('error al consultar fecha de creación');
        
        $datecreated = $timecreated->timecreated;
        
        $sql_query = "select id, nombre ,fecha_inicio::DATE, fecha_fin::DATE  from {talentospilos_semestre} ORDER BY fecha_fin ASC;";
        
        $semesters = $DB->get_records_sql($sql_query);
        
        foreach ($semesters as $semester){
            $fecha_inicio = new DateTime($semester->fecha_inicio);
            date_add($fecha_inicio, date_interval_create_from_date_string('-30 days'));
            if((strtotime($fecha_inicio->format('Y-m-d')) <= $datecreated ) && ($datecreated <= strtotime($semester->fecha_fin))){
                return $semester->id;
            }
            
        }return false;
    }catch(Exeption $e){
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
        // print_r($report->tabledata);
        return $report->print_table(true);
        
    }
    return null;

}
//(getCoursegradelib();