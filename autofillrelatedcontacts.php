<?php

require_once 'autofillrelatedcontacts.civix.php';
use CRM_Autofillrelatedcontacts_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function autofillrelatedcontacts_civicrm_config(&$config) {
  _autofillrelatedcontacts_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function autofillrelatedcontacts_civicrm_xmlMenu(&$files) {
  _autofillrelatedcontacts_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function autofillrelatedcontacts_civicrm_install() {
  //Create Custom Group.
  $customGroup = civicrm_api3('CustomGroup', 'create', [
    'title' => "Related Contact Lookup",
    'extends' => "Individual",
    'name' => "related_contact_lookup",
    'collapse_display' => 1,
    'is_active' => 1,
  ]);

  civicrm_api3('CustomField', 'create', [
    'custom_group_id' => $customGroup['id'],
    'label' => "Related Contact",
    'name' => "related_contact_lookup_field",
    'data_type' => "ContactReference",
    'html_type' => "Autocomplete-Select",
  ]);
  _autofillrelatedcontacts_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function autofillrelatedcontacts_civicrm_postInstall() {
  _autofillrelatedcontacts_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function autofillrelatedcontacts_civicrm_uninstall() {
  CRM_Autofillrelatedcontacts_BAO_RelatedContact::deleteCustomEntities();
  _autofillrelatedcontacts_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function autofillrelatedcontacts_civicrm_enable() {
  _autofillrelatedcontacts_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function autofillrelatedcontacts_civicrm_disable() {
  _autofillrelatedcontacts_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function autofillrelatedcontacts_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _autofillrelatedcontacts_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function autofillrelatedcontacts_civicrm_managed(&$entities) {
  _autofillrelatedcontacts_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function autofillrelatedcontacts_civicrm_caseTypes(&$caseTypes) {
  _autofillrelatedcontacts_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function autofillrelatedcontacts_civicrm_angularModules(&$angularModules) {
  _autofillrelatedcontacts_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function autofillrelatedcontacts_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _autofillrelatedcontacts_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function autofillrelatedcontacts_civicrm_entityTypes(&$entityTypes) {
  _autofillrelatedcontacts_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_apiWrappers().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_apiWrappers
 */
function autofillrelatedcontacts_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if ($apiRequest['entity'] == 'Contact' && $apiRequest['action'] == 'get') {
    $wrappers[] = new CRM_Autofillrelatedcontacts_APIWrapper();
  }

  if ($apiRequest['entity'] == 'Profile' && $apiRequest['action'] == 'get') {
    $wrappers[] = new CRM_Autofillrelatedcontacts_ProfileAPIWrapper();
  }
}

function autofillrelatedcontacts_civicrm_preProcess($formName, &$form) {
  if ($formName == 'CRM_Event_Form_Registration_AdditionalParticipant') {
    $contactLookupField = 'custom_' . CRM_Autofillrelatedcontacts_BAO_RelatedContact::getRelatedContactCustomFieldID();
    $params = $form->getVar('_params');
    $filledCids = [];
    foreach ($params as $p) {
      if (!empty($p[$contactLookupField])) {
        $filledCids[] = $p[$contactLookupField];
      }
    }
    $form->assign('exclude_cids', json_encode($filledCids));
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function autofillrelatedcontacts_civicrm_buildForm($formName, &$form) {
  //contact reference lookup field by relationship
  $contactLookupField = 'custom_' . CRM_Autofillrelatedcontacts_BAO_RelatedContact::getRelatedContactCustomFieldID();
  if (in_array($formName, [
    'CRM_Event_Form_Registration_Register',
    'CRM_Event_Form_Registration_AdditionalParticipant',
    'CRM_Contribute_Form_Contribution_Main'
  ]) && $form->elementExists($contactLookupField)) {
    $profiles = [];
    if ($form->_values['custom_pre_id']) {
      $profiles[] = $form->_values['custom_pre_id'];
    }
    if ($form->_values['custom_post_id']) {
      $profiles = array_merge($profiles, (array) $form->_values['custom_post_id']);
    }
    $profiles[] = 'billing';
    if (!empty($form->_values)) {
      $autoCompleteField = [
        'id_field' => $contactLookupField,
      ];
      CRM_Core_Resources::singleton()->addScriptFile('nz.co.fuzion.autofillrelatedcontacts', 'js/RelatedContactSelector.js', 1, 'html-header')
        ->addSetting([
          'form' => ['autocompletes' => $autoCompleteField],
          'ids' => ['profile' => $profiles],
        ]);
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function autofillrelatedcontacts_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function autofillrelatedcontacts_civicrm_navigationMenu(&$menu) {
  _autofillrelatedcontacts_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _autofillrelatedcontacts_civix_navigationMenu($menu);
} // */
