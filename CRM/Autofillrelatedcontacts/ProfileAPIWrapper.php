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
    unset($result['values']["custom_{$customFieldID}"]);
    foreach ($result['values'] as $key => $profileData) {
      unset($result['values'][$key]["custom_{$customFieldID}"]);
    }
    return $result;
  }
}