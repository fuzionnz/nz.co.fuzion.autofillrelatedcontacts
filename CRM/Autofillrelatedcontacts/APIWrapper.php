<?php

class CRM_Autofillrelatedcontacts_APIWrapper implements API_Wrapper {

  public function fromApiInput($apiRequest) {
    $customFieldID = CRM_Autofillrelatedcontacts_BAO_RelatedContact::getRelatedContactCustomFieldID();
    $context = CRM_Utils_Request::retrieve('context', 'String');
    $id = CRM_Utils_Request::retrieve('id', 'Positive');
    $excludeCids = CRM_Utils_Request::retrieve('exclude_cids', 'String');

    if (!empty($context) && !empty($id) && $context == 'customfield' && $id == $customFieldID) {
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
      $removeCids = [];
      if (!empty($excludeCids)) {
        $removeCids = json_decode($excludeCids, TRUE);
      }
      if (!empty($relatedContacts)) {
        $apiRequest['params']['id'] = ['IN' => array_diff($relatedContacts, $removeCids)];
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