<?php
/**
 * Created by PhpStorm.
 * User: lveeckha
 * Date: 25/03/2019
 * Time: 10:19
 */

require_once "HideByRoleParser.php";


//Empty test.
$values = [];
$values["field_annotation"] = "Nothing";
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, []) == null);

//Found test.
$values = [];
$values["field_annotation"] = "@HIDE_BY_ROLE=role1";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role1"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

//Role mismatch test.
$values = [];
$values["field_annotation"] = "@HIDE_BY_ROLE=role1";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == null);

//2 annotations separated by newline.
$values = [];
$values["field_annotation"] = "@HIDE_BY_ROLE=role2\n@HIDDEN";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

//2 annotations separated by newline.
$values = [];
$values["field_annotation"] = "@HIDE_BY_ROLE=role2\r\n@READONLY";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);




//2 annotations separated by newline and a space.
$values = [];
$values["field_annotation"] = "@HIDE_BY_ROLE=role2 \n@READONLY";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
//assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

//2 annotations.
$values = [];
$values["field_annotation"] = "@HIDDEN @HIDE_BY_ROLE=role2";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

//roles with a space.
$values = [];
$values["field_annotation"] = "@HIDDEN=2 @HIDE_BY_ROLE=role with a space";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role with a space"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

//roles with a space and annotation.
$values = [];
$values["field_annotation"] = "@HIDDEN @HIDE_BY_ROLE=role with a space @ABC";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role with a space"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

//roles with a space and annotation.
$values = [];
$values["field_annotation"] = "@HIDDEN @HIDE_BY_ROLE=role with = B @ABC";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role with = B"];
assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

echo "\nAll tests have run.";