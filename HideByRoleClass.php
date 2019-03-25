<?php

namespace uzgent\HideByRoleClass;

require_once "HideByRoleParser.php";

// Declare your module class, which must extend AbstractExternalModule
class HideByRoleClass extends \ExternalModules\AbstractExternalModule {

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
            $foundUserRoleId = HideByRoleParser::parseAnnotationRoleId($values, $projectRoles);

            if ($foundUserRoleId != null)
            {
                if ($roleId != $foundUserRoleId)
                {
                    $fieldsToHide[]= $fieldname;
                }
            }
        }

        $this->printJavaScriptHiders($fieldsToHide);
    }

    /**
     * @param $fieldsToHide
     */
    public function printJavaScriptHiders($fieldsToHide)
    {
        echo "<script>";
        include "hideaway.js";

        foreach ($fieldsToHide as $fieldToHide) {
            echo 'UZG_hideaway.hideIt("' . $fieldToHide . '");';
        }

        echo "</script>";
    }

}
