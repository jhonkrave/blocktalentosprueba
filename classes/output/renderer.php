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
 * Talentos Pilos
 *
 * @author     Iader E. García Gómez
 * @package    block_generalreports
 * @copyright  2016 Iader E. García <iadergg@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
// Standard GPL and phpdocs
namespace block_talentospilos\output;                                                                                                         
 
defined('MOODLE_INTERNAL') || die;                                                                                                  
 
use plugin_renderer_base;  
 
class renderer extends plugin_renderer_base {
    
    public function render_index_page($page) {                                                                                      
        $data = $page->export_for_template($this);                                                                                  
        return parent::render_from_template('block_talentospilos/index', $data);                                                         
    }           
    
    public function render_upload_files_page($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_talentospilos/upload_files', $data);
    }

    public function render_talentos_profile_page($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_talentospilos/talentos_profile', $data);
    }
    
    public function render_user_management_page($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_talentospilos/user_management', $data);
    }
    
    public function render_attendance_page($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_talentospilos/attendance', $data);
    }
    
    public function render_seguimiento_grupal($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_talentospilos/seguimiento_grupal', $data);
    }
    
    public function render_prueba_page($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_talentospilos/prueba', $data);
    }
    
}