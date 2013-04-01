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
 *
 * @package   block_countuser
 * @copyright 2013 Human Science Co., Ltd.  {@link http://www.science.co.jp}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class countuser_lib{
	function __construct(){}

	//コース内でロールに割り当てられている人数をカウントする
	function make_roule_count_table($courseid = 1){
		global $CFG,$DB;
		$sql = <<< SQL
SELECT rl.id,rl.name,rl.description,count(ra.roleid) as num,rl.id as roleid FROM {$CFG->prefix}role_assignments AS ra
INNER JOIN {$CFG->prefix}context AS CT ON  CT.id = ra.contextid AND CT.contextlevel = 50
AND CT.instanceid = ?
RIGHT OUTER JOIN {$CFG->prefix}role AS rl ON ra.roleid = rl.id
GROUP BY rl.id,rl.name,rl.description,rl.sortorder
ORDER BY rl.sortorder
SQL;
		$ret = $DB->get_records_sql($sql,array($courseid));

		$table = new html_table();
		$table->head = array(get_string('roles'), get_string('description'), get_string('user'));
		foreach ($ret as $tmp){
			$enroleid = $this->get_enroleid($courseid);
			$url = html_writer::link(new moodle_url('/enrol/manual/manage.php',array('enrolid'=>$enroleid,'roleid'=>$tmp->roleid)),$tmp->name);
			$table->data[] = array($url,$tmp->description,$tmp->num);
		}
		echo html_writer::table($table);

	}

	//roleidを取得
	private function get_enroleid($courseid = 1){
		global $DB;

		$enroleid = -1;
		if($courseid == 1) return $enroleid;

		$ret = $DB->get_record('enrol',array('enrol'=>'manual','courseid'=>$courseid),'id');
		if($ret !== false){
			$enroleid = $ret->id;
		}
		return $enroleid;
	}

}