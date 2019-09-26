<?php

class CRM_Autofillrelatedcontacts_BAO_RelatedContact {

  /**
   * Get custom group id created by this ext.
   */
  public static function getRelatedContactCustomGroupID() {
    try {
      $groupID = civicrm_api3('CustomGroup', 'getvalue', [
        'return' => "id",
        'name' => "related_contact_lookup",
      ]);
    }
    catch (Exception $e) {
      $groupID = NULL;
    }

    return $groupID;
  }

  /**
   * Get custom field id created by this ext.
   */
  public static function getRelatedContactCustomFieldID() {
    try {
      $fieldID = civicrm_api3('CustomField', 'getvalue', [
        'return' => "id",
        'name' => "related_contact_lookup_field",
      ]);
    }
    catch (Exception $e) {
      $fieldID = NULL;
    }

    return $fieldID;
  }

  public static function deleteCustomEntities() {
    if ($fieldID = self::getRelatedContactCustomFieldID()) {
      civicrm_api3('CustomField', 'delete', [
        'id' => $fieldID,
      ]);
    }
    if ($groupID = self::getRelatedContactCustomGroupID()) {
      civicrm_api3('CustomGroup', 'delete', [
        'id' => $groupID,
      ]);
    }
  }

}