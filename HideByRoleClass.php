<?php

namespace uzgent\HideByRoleClass;

// Declare your module class, which must extend AbstractExternalModule
class HideByRoleClass extends \ExternalModules\AbstractExternalModule {

    const annotation = "@HIDE_BY_ROLE";
    public function redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance)
    {
        //Get the current role.
        $userInitiator = \UserRights::getRightsAllUsers();
        $roleId = $userInitiator[USERID]["role_id"];

        $projectRoles = \UserRights::getRoles();
        $fieldsToHide = [];
        $metadata = $this->getMetadata($project_id);
        foreach($metadata as $fieldname => $values)
        {
            $foundUserRoleId = $this->parseAnnotationRoleId($values, $projectRoles);

            if ($foundUserRoleId != null)
            {
                if ($roleId != $foundUserRoleId)
                {
                    $fieldsToHide[]= $fieldname;
                }
            }
        }

        $this->printJavaScriptHiders($fieldsToHide);


        //Hide
        //if role equals role as defined in permission then do nothing.
        // Else Loop over every field and disable fields with annotation <annotation>.
    }

    /**
     * @param $elements
     * @param $projectRoles
     * @return int|null|string
     */
    public function getUserRoleId($elements, $projectRoles)
    {
        $annotationRole = $elements[1];
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
     * @param $values
     * @param $projectRoles
     * @return int|null|string
     */
    public function parseAnnotationRoleId($values,$projectRoles)
    {
        $currentAnnotation = $values["field_annotation"];
        $foundUserRoleId = null;
        if (strpos($currentAnnotation, self::annotation) != FALSE) {
            $elements = explode("=", $currentAnnotation);
            if (count($elements) == 2) {
                $foundUserRoleId = $this->getUserRoleId($elements, $projectRoles);
            }
        }
        return $foundUserRoleId;
    }

    /**
     * @param $fieldsToHide
     */
    public function printJavaScriptHiders($fieldsToHide)
    {
        echo "<script>";
        include "hideaway.js";

        foreach ($fieldsToHide as $fieldToHide) {
            echo 'UZG_hideaway.hideIt("' . $fieldToHide . '")';
        }

        echo "</script>";
    }

}
