# nz.co.fuzion.autofillrelatedcontacts

This extension allows user to auto-fill the profile fields by selecting a contact in the autocomplete field.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.4+
* CiviCRM (v5.x)

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl nz.co.fuzion.autofillrelatedcontacts@https://github.com/fuzionnz/nz.co.fuzion.autofillrelatedcontacts/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/fuzionnz/nz.co.fuzion.autofillrelatedcontacts.git
cv en autofillrelatedcontacts
```

## Usage

- This ext creates an Individual custom group(Related Contact Lookup) and a custom field(Related Contact) on installation.
- Include this custom field in a profile, eg Name and Address.
- Enable this profile on an additional participant page.
- When a participant registers on the event, this profile will load the autocomplete field.
- User can select related contacts in this autocomplete field. On selection, all fields on the prfoile will be automatically
filled with the contact values. Similar to what civi offers on cid=0 page.