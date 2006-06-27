<?php
// $Id: view.php,v 1.1 2006-06-27 20:08:02 gage Exp $

/// This page prints a particular instance of wwassignment
/// (Replace wwassignment with the name of your module)

require_once("../../config.php");
require_once("lib.php");

    $id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
    $a  = optional_param('a', 0, PARAM_INT);  // NEWMODULE ID

if ($id) {
	if (! $cm = get_record("course_modules", "id", $id)) {
		error("Course Module ID was incorrect");
	}
	
	if (! $course = get_record("course", "id", $cm->course)) {
		error("Course is misconfigured");
	}
	
	if (! $wwassignment = get_record("wwassignment", "id", $cm->instance)) {
		error("Course module is incorrect");
	}
} else {
	if (! $wwassignment = get_record("wwassignment", "id", $a)) {
		error("Course module is incorrect");
	}
	if (! $course = get_record("course", "id", $wwassignment->course)) {
		error("Course is misconfigured");
	}
	if (! $cm = get_coursemodule_from_instance("wwassignment", $wwassignment->id, $course->id)) {
		error("Course Module ID was incorrect");
	}
}

require_login($course->id);

add_to_log($course->id, "wwassignment", "view", "view.php?id=$cm->id", "$wwassignment->id");

/// Print the page header

if ($course->category) {
	$navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
}

$strwwassignments = get_string("modulenameplural", "wwassignment");
$strwwassignment  = get_string("modulename", "wwassignment");

print_header("$course->shortname: $wwassignment->name", "$course->fullname", "$navigation <a href='index.php?id=$course->id'>$strwwassignments</a> -> $wwassignment->name", "", "", true, update_module_button($cm->id, $course->id, $strwwassignment), navmenu($course, $cm));

/// Print the main part of the page

$sSetLink = wwassignment_linkToSet($wwassignment->set_id, wwassignment_courseIdToShortName($wwassignment->course));
print("<p style='font-size: smaller; color: #aaa;'>" . get_string("iframeNoShow-1", "wwassignment")
      . "<a href='$sSetLink'>" . get_string("iframeNoShow-2", "wwassignment") . "</a>.</p>\n");
print("<p align='center'><iframe id='wwPage' src='$sSetLink' frameborder='1' "
      . "width='".$CFG->wwassignment_iframe_width."' "
      . "height='".$CFG->wwassignment_iframe_height."'>"
      . get_string("iframeNoShow-1", "wwassignment") . "<a href='$sSetLink'>" . get_string("iframeNoShow-2", "wwassignment")
      . "</a>.</iframe></p>\n");

print("<script>ww.Init(".isteacher($course->id).")</script>");


/// Finish the page
print_footer($course);

?>
