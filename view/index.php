<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * General Reports
 *
 * @author     Iader E. García Gómez
 * @package    block_talentospilos
 * @copyright  2016 Iader E. García <iadergg@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Standard GPL and phpdocs
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../managers/query.php');
include('../lib.php');
global $PAGE;

include("../classes/output/index_page.php");
include("../classes/output/renderer.php");

// Set up the page.
$title = get_string('pluginname', 'block_talentospilos');
$pagetitle = $title;
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('instanceid', PARAM_INT);

require_login($courseid, false);

$contextcourse = context_course::instance($courseid);
$contextblock =  context_block::instance($blockid);

$url = new moodle_url("/blocks/talentospilos/view/index.php",array('courseid' => $courseid, 'instanceid' => $blockid));
//$url =  $CFG->wwwroot."/blocks/talentospilos/view/index.php?courseid=".$courseid."&instanceid=".$blockid;

//Configuracion de la navegacion
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

$PAGE->requires->js('/blocks/talentospilos/js/jquery-2.0.2.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/sweetalert-dev.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/jquery.dataTables.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/jquery.dataTables.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.jqueryui.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.bootstrap.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/DataTables-1.10.12/js/dataTables.bootstrap.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/table.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/main.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/checkrole.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/bootstrap.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/bootstrap.min.js', true);

$output = $PAGE->get_renderer('block_talentospilos');


//echo $output->standard_head_html(); 
echo $output->header();
$index_page = new \block_talentospilos\output\index_page('Some text');
echo $output->render($index_page);
echo $output->footer();