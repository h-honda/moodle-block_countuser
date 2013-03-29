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

class block_countuser extends block_base {
	function init() {
		$this->title = get_string('pluginname', 'block_countuser');
	}

	function get_content() {
		if ($this->content !== NULL) {
			return $this->content;
		}

		$this->content = new stdClass;

		global $CFG,$USER,$COURSE;
		require_once $CFG->dirroot.'/config.php';

		if(is_siteadmin($USER->id)){//管理者のみ表示する
			if($COURSE->id != 1){
				//コース内で表示する
				$contenturl = new moodle_url("$CFG->wwwroot/blocks/countuser/countuser.php?id=".$COURSE->id);
				$contentlink = html_writer::link($contenturl, get_string('pluginname','block_countuser'));
				$contents[] = html_writer::tag('li', $contentlink);

				$this->content->text = html_writer::tag('ol', implode('', $contents), array('class' => 'list'));
				$this->content->text = html_writer::tag('div',$this->content->text);
			}else{
				//サイトトップでは表示しない
				$this->content->text = '';
			}
		}
		$this->content->footer = '';

		return $this->content;
	}
	//インスタンス設定の実装　手でエンピツ持ってるマークが有効になり、設定画面が開けるようになる。
	//config_instance.html　が設定画面となるので、そちらも忘れずに作成してください。
	function instance_allow_config() {
		return false;
	}
}
