<?php

/**
 * @file
 * Conatins \Drupal\addressbook\Controller\AddressBook.
 */

namespace Drupal\addressbook\Controller;

use Drupal\Core\Controller\ControllerBase;

class AddressBookController extends ControllerBase {


  static function addressbookListItems($name = NULL) {
    if ($name) {
      $addresses = \Drupal::database()
        ->select('addressbook','addressbook')
        ->fields('addressbook',  array('id', 'name', 'email', 'dob', 'phone'))
        ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
        ->condition('addressbook.name', "% $name %", 'LIKE')
        ->execute()
        ->fetchAll();
    }
    else {
      $addresses = \Drupal::database()
      ->select('addressbook','addressbook')
      ->fields('addressbook',  array('id', 'name', 'email', 'dob', 'phone'))
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->execute()
      ->fetchAll();
    }
    return $addresses;
  }

  static function addressbookValues($address_id) {
    $address_values = \Drupal::database()
      ->select('addressbook', 'addressbook')
      ->fields('addressbook', array('name', 'email', 'phone', 'dob'))
      ->condition('addressbook.id', $address_id)
      ->execute()
      ->fetch();
    return $address_values;
  }

  public function addressbookBirthday() {
	$birthday = array();
    $birthdays = \Drupal::Database()
      ->select('addressbook', 'addressbook')
      ->fields('addressbook')
      ->condition('addressbook.dob', strtotime(date('Y-m-d 00:00:00')))
      ->execute()
      ->fetchAll();
    foreach ($birthdays as $key => $value) {
	    $birthday[] = array(
	      'name' => $value->name
	    );
    }
    return $birthday;
  }

  public function showAddressBook($id = NULL) {
	$results = \Drupal::Database()
	  ->select('addressbook', 'addressbook')
	  ->fields('addressbook')
	  ->condition('addressbook.id', $id)
	  ->execute()
	  ->fetchAll();
	$header = array('Name', 'Phone', 'E-mail', 'DOB');
	foreach ($results as $result) {
	  $output[] = array(
		'phone' => $result->phone,
		'name' => $result->name,
		'mail' => $result->email,
		'dob' => date('Y-m-d', $result->dob),
	  );
	}
	$form['table'] = [
	  '#type' => 'table',
	  '#header' => $header,
	  '#rows' => $output,
	];
	return $form;
  }


}
