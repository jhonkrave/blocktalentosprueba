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

global $PAGE;

include("../classes/output/seguimiento_grupal.php");
include("../classes/output/renderer.php");

// Set up the page.
$title = "Seguimiento Grupal";
$pagetitle = $title;
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('instanceid', PARAM_INT);

require_login($courseid, false);

$contextcourse = context_course::instance($courseid);
$contextblock =  context_block::instance($blockid);
$url = new moodle_url("/blocks/talentospilos/view/seguimiento_grupal.php", array('courseid' => $courseid, 'instanceid' => $blockid));


//se configura la navegacion
$coursenode = $PAGE->navigation->find($courseid, navigation_node::TYPE_COURSE);
$node = $coursenode->add('Seguimiento Grupal',$url);
$node->make_active();

//Se configura la pagina

$PAGE->set_url($url);
$PAGE->set_title($title);

$PAGE->set_heading($title);

$PAGE->requires->css('/blocks/talentospilos/style/styles_pilos.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/bootstrap_pilos.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/bootstrap_pilos.min.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/sweetalert.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/round-about_pilos.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/sugerenciaspilos.css', true);
$PAGE->requires->css('/blocks/talentospilos/style/forms_pilos.css', true);
$PAGE->requires->js('/blocks/talentospilos/js/jquery-2.2.4.min.js', true);

$PAGE->requires->js('/blocks/talentospilos/js/jquery.validate.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/bootstrap.min.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/bootstrap.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/sweetalert-dev.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/npm.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/checkrole.js', true);
//$PAGE->requires->js('/blocks/talentospilos/js/sugerenciaspilos.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/main.js', true);
$PAGE->requires->js('/blocks/talentospilos/js/seguimiento_grupal.js', true);

$output = $PAGE->get_renderer('block_talentospilos');

echo $output->header();
//echo $output->standard_head_html(); 
$seguimiento_grupal_page = new \block_talentospilos\output\seguimiento_grupal('Some text');
echo $output->render($seguimiento_grupal_page);
echo $output->footer();
