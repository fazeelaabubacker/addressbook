<?php

/**
 * @file
 * Contains \Drupal\addressbook\Controller\AddressBookListController.
 */

namespace Drupal\addressbook\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class AddressBookListController extends ControllerBase {

  public function addressbookList() {
    $output['form'] = \Drupal::formBuilder()->getForm('Drupal\addressbook\Form\AddressBookSearchForm');
    static $address = array();
    foreach (AddressBookController::addressbookListItems() as $key => $value) {
      $address[] = array(
        'name' => $value->name,
        'email' => $value->email,
        'dob' => date('Y-m-d', $value->dob),
        'phone' => $value->phone,
        'edit' => \Drupal::l('Edit', Url::fromRoute(
          'addressbook.edit', array(
            'id' => $value->id,
          )
        )),
      );
    }
    if (empty($address)) {
      $address = array('No birthdays');
    }
    $header = array('Name', 'Email', 'DOB', 'Phone', 'Operations');
    $output['location_table'] = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $address,
    );
    $output['pager'] = array(
      '#type' => 'pager',
      '#weight' => 5,
    );
    return $output;
  }
  
  public function addressbookSearch($search_type, $search_term) {

	$search_results = \Drupal::database()
      ->select('addressbook', 'addressbook')
      ->fields('addressbook')
	  ->condition('addressbook.' . $search_type, '%' . $search_term . '%', 'LIKE')
	  ->execute()
	  ->fetchAll();
    if (!empty($search_results)) {
      foreach ($search_results as $key => $value) {
        $rows[] = array(
          'name' => $value->name,
          'email' => $value->email,
          'dob' => date('Y-m-d', $value->dob),
          'phone' => $value->phone,
        );
      }
    }
    else {
      $rows[] = array('No results found');
    }
    $header = array('Name', 'Email', 'DOB', 'Phone');
    $output[] = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    );
    return $output;
  }

}
