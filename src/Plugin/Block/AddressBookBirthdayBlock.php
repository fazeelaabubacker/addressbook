<?php

/**
 * @file
 * Contains \Drupal\addressbook\Plugin\Block\AddressBookBirthdayBlock.
 */ 
namespace Drupal\addressbook\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\addressbook\Controller\AddressBookController;

/**
 * Provides a custom birthday block.
 * 
 * @Block(
 *   id = "addressbook_birthday",
 *   admin_label = @Translation("Today's Birthday"),
 * )
 * 
 */
class AddressBookBirthdayBlock extends BlockBase {

  public function build() {
    $address = AddressBookController::addressbookBirthday();
    if (empty($address)) {
      $address[] = array('No birthdays');
    }
    $build['birthday_block']  = [
      '#theme' => 'table',
      '#header' => '',
      '#rows' => $address,
    ];
    return $build;
  }
}
