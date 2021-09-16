<?php

class CRM_Autofillrelatedcontacts_ProfileAPIWrapper implements API_Wrapper {

  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
  * remove child contact to return from profile data.
  */
  public function toApiOutput($apiRequest, $result) {
    $customFieldID = CRM_Autofillrelatedcontacts_BAO_RelatedContact::getRelatedContactCustomFieldID();
    if (empty($result['values'])) {
      return $result;
    }
    $profileFields = [];

    $profileIDs = (array) $apiRequest['params']['profile_id'] ?? NULL;
    foreach ($profileIDs as $profileID) {
      if (!empty($profileID) && !is_array($profileID) && strtolower($profileID) != 'billing') {
        $profileFields[$profileID] = array_keys(civicrm_api3('Profile', 'getfields', ['action' => 'submit', 'profile_id' => $profileID])['values']);
      }
    }

    $isSingleProfile = FALSE;
    foreach ($result['values'] as $key => $profileData) {
      if (is_array($profileData)) {
        foreach ($profileFields as $profileID => $fields) {
          if ($profileID == $key) {
            foreach ($fields as $field) {
              if (!isset($profileData[$field])) {
                $result['values'][$key][$field] = NULL;
              }
            }
          }
        }
        unset($result['values'][$key]["custom_{$customFieldID}"]);
      }
      else {
        $isSingleProfile = TRUE;
        break;
      }
    }

    if ($isSingleProfile) {
      foreach ($profileFields as $profileID => $fields) {
        foreach ($fields as $field) {
          if (!isset($result['values'][$field])) {
            $result['values'][$field] = NULL;
          }
        }
      }
      unset($result['values']["custom_{$customFieldID}"]);
    }

    return $result;
  }

}