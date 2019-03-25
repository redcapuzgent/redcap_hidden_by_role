<?php

namespace uzgent\HideByRoleClass;


class HideByRoleParser
{
    const annotation = "@HIDE_BY_ROLE";

    /**
     * @param $elements
     * @param $projectRoles array map of role_id => role details[] role_name
     * @return int|null|string
     */
    public static function getUserRoleId($annotationRole, $projectRoles)
    {
        $foundUserRoleId = null;
        foreach ($projectRoles as $projectRoleId => $projectRoleDetails) {

            if ($projectRoleDetails["role_name"] == $annotationRole) {
                $foundUserRoleId = $projectRoleId;
            }
        }
        if ($foundUserRoleId == null) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">Module config problem: Role $annotationRole was found as an annotation but couldn't be found in your project.</div>";
        }
        return $foundUserRoleId;
    }

    /**
     * @param $values[] array of field annotations.
     * @param $projectRoles array map of role_id => role details[] role_name
     * @return int|null
     */
    public static function parseAnnotationRoleId($values,$projectRoles)
    {
        $rawAnnotation = $values["field_annotation"];
        $foundUserRoleId = null;
        $currentAnnotations = explode("\n", str_replace("\r", "", $rawAnnotation));
        foreach ($currentAnnotations as $currentAnnotation)
        {
            if (strpos($currentAnnotation, self::annotation) !== FALSE) {
                $currentAnnotContent = substr($currentAnnotation, strpos($currentAnnotation, self::annotation) + strlen(self::annotation) + 1);
                $annotationAtPos = strpos($currentAnnotContent, "@");
                if ($annotationAtPos !== FALSE) // slice off other annotations that come after.
                {
                    $currentAnnotContent = substr($currentAnnotContent, 0, $annotationAtPos - 1);
                }
                return self::getUserRoleId($currentAnnotContent, $projectRoles);
            }
        }
        return null;
    }
}