<?php

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../managers/query.php');
include('../lib.php');
include("../classes/output/prueba_page.php");
include("../classes/output/renderer.php");

global $PAGE;

// Variables for page setup
$title = get_string('pluginname', 'block_talentospilos');
$pagetitle = $title;
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('instanceid', PARAM_INT);

require_login($courseid, false);

$contextcourse = context_course::instance($courseid);
$contextblock =  context_block::instance($blockid);

$url = new moodle_url("/blocks/talentospilos/view/prueba.php",array('courseid' => $courseid, 'instanceid' => $blockid));

//Navigation setup
$coursenode = $PAGE->navigation->find($courseid, navigation_node::TYPE_COURSE);
$blocknode = navigation_node::create($title,$url, null, 'block', $blockid);
$coursenode->add_node($blocknode);
$blocknode->make_active();

$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);

$PAGE->requires->css('/blocks/talentospilos/style/styles_pilos.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/bootstrap_pilos.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/bootstrap_pilos.min.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/sweetalert.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/round-about_pilos.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/dataTables.foundation.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/dataTables.foundation.min.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/dataTables.jqueryui.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/dataTables.jqueryui.min.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/jquery.dataTables.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/jquery.dataTables.min.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/jquery.dataTables_themeroller.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/jquery.dataTables.tableTools.css', true);
$PAGE->requires->css('/blocks/talentospilos/js/DataTables-1.10.12/css/NewCSSExport/buttons.dataTables.min.css', true);

$PAGE->requires->js('/blocks/talentospilos/js/jquery-2.2.4.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/jquery.dataTables.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/jquery.dataTables.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.jqueryui.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.bootstrap.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.bootstrap.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.tableTools.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.tableTools.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/NewJSExport/buttons.flash.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/NewJSExport/buttons.html5.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/NewJSExport/buttons.print.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/NewJSExport/dataTables.buttons.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/NewJSExport/jszip.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/NewJSExport/pdfmake.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/NewJSExport/vfs_fonts.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/attendance_table.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/main.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/checkrole.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/bootstrap.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/bootstrap.min.js', true);

$output = $PAGE->get_renderer('block_talentospilos');

echo $output->header();
//echo $output->standard_head_html(); 
$prueba = new \block_talentospilos\output\prueba_page('Some text');
echo $output->render($prueba);
echo $output->footer();