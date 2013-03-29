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

require_once('../../config.php');
global $CFG,$COURSE;

$id = required_param('id', PARAM_INT);
require_login($id);

//アクセス権のチェック
if (!(is_siteadmin($USER->id))){ //管理者のみが利用可能
	echo get_string('erromsg01','countuser');
	die;
}

$coursecontext = get_context_instance(CONTEXT_COURSE, $id);
$PAGE->set_context($coursecontext);

// 設定
$PAGE->set_url("$CFG->wwwroot/blocks/countuser/countuser.php");						//このファイルのURLを設定
$PAGE->set_title(get_string('pluginname','block_countuser'));						//ブラウザのタイトルバーに表示されるタイトル
$PAGE->set_heading(get_string('pluginname','block_countuser'));								//ヘッダーに表示する文字列
$PAGE->set_pagelayout('course');
$PAGE->navbar->add(get_string('pluginname','block_countuser'), "");							//ヘッダーのナビゲーションに項目追加

// ヘッダー出力
echo $OUTPUT->header();

//ここにコンテンツの中身を書く
/////////////////////////////////////////////////////////////
require_once 'lib.php';

$obj = new countuser_lib();
$obj->make_roule_count_table($id);

//ここまで
echo $OUTPUT->footer();