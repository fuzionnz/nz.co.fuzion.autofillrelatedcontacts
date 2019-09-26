<?php

class CRM_Autofillrelatedcontacts_APIWrapper implements API_Wrapper {

  public function fromApiInput($apiRequest) {
    $customFieldID = CRM_Autofillrelatedcontacts_BAO_RelatedContact::getRelatedContactCustomFieldID();
    if (!empty($_GET['context']) && !empty($_GET['id']) && $_GET['context'] == 'customfield' && $_GET['id'] == $customFieldID) {
      $relationships = civicrm_api3('Relationship', 'get', [
        'sequential' => 1,
        'return' => ["contact_id_a"],
        'contact_id_b' => "user_contact_id",
        'is_permission_b_a' => 1,
      ]);

      $relatedContacts = array_column($relationships['values'], 'contact_id_a');
      if (Civi::settings()->get('secondDegRelPermissions')) {
        $contactID = CRM_Core_Session::getLoggedInContactID();
        $tableName = _relatedpermissions_get_permissionedtable($contactID, CRM_Core_Permission::EDIT);
        $dao = CRM_Core_DAO::executeQuery("SELECT contact_id FROM {$tableName}");
        $secondDegreeContacts = [];
        while ($dao->fetch()) {
          $secondDegreeContacts[] = $dao->contact_id;
        }
        $relatedContacts += $secondDegreeContacts;
      }
      if (!empty($relatedContacts)) {
        $apiRequest['params']['id'] = ['IN' => $relatedContacts];
      }
      else {
        $apiRequest['params']['id'] = ['IS NULL' => 1];
      }
    }
    return $apiRequest;
  }

  /**
  * alter the result before returning it to the caller.
  */
  public function toApiOutput($apiRequest, $result) {
    return $result;
  }

}