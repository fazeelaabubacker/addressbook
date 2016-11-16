<?php

/**
 * @file
 * Contains Drupal\addressbook\Form\AddressBookForm.
 */

namespace Drupal\addressbook\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\addressbook\Controller\AddressBookController;
use Drupal\Core\Url;
use Drupal\Core\Datetime\DrupalDateTime;

class AddressBookForm extends FormBase {

  public function getFormId() {
    return 'addressbook_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

	$values = AddressBookController::addressbookValues($id);
	$form['id'] = array(
      '#type' => 'hidden',
      '#value' => !empty($id) ? $id : '',
    );
    $form['name'] = array(
      '#title' => $this->t('Name'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => !empty($values->name) ? $values->name : '',
    );
    $form['email'] = array(
      '#title' => $this->t('E-mail'),
      '#type' => 'email',
      '#required' => TRUE,
      '#default_value' => !empty($values->email) ? $values->email : '',
    );
    $form['phone'] = array(
      '#title' => t('Phone'),
      '#type' => 'tel',
      '#required' => TRUE,
      '#default_value' => !empty($values->phone) ? $values->phone : '',
    );
    $form['dob'] = array(
      '#title' => $this->t('Dob'),
      '#type' => 'datelist',
      '#date_part_order' => array('year', 'month', 'day'),
      '#required' => TRUE,
      '#default_value' => !empty($values->dob) ? DrupalDateTime::createFromTimestamp($values->dob) : new DrupalDateTime('NOW'),
    );
    $form['submit'] = array(
      '#value' => $this->t('submit'),
      '#type' => 'submit',
    );
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('id');
	if ($id) {
	  $values = AddressBookController::addressbookValues($id);
	}
	$name = $form_state->hasValue('name') ? $form_state->getValue('name') : '';
    $email = $form_state->hasValue('email') ? $form_state->getValue('email') : '';
    $phone = $form_state->hasValue('phone') ? $form_state->getValue('phone') : '';
	$dob = $form_state->hasValue('dob') ? $form_state->getValue('dob') : '';
	  if (empty($values)) {
      $id = \Drupal::database()
        ->insert('addressbook')
        ->fields(array(
          'name' => $name,
          'email' => $email,
          'phone' => $phone,
          'dob' => strtotime($dob),
        ))
        ->execute();
      drupal_set_message($this->t('The address has been successfully saved.'));
    }
    else {
	    \Drupal::database()
        ->update('addressbook')
        ->fields(array(
          'name' => $name,
          'email' => $email,
          'phone' => $phone,
          'dob' => strtotime($dob),
        ))
        ->condition('addressbook.id', $id)
        ->execute();
	    drupal_set_message($this->t('The address has been updated successfully.'));
	  }
	  $form_state->setRedirect('addressbook.view', array('id' => $id));
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
	if (!is_numeric($form_state->getValue('phone'))) {
	  $form_state->setErrorByName('phone', $this->t('Mobile number can only be numbers'));
	}
	if (strlen($form_state->getValue('phone')) > 10) {
	  $form_state->setErrorByName('phone', $this->t('Mobile number is too long.'));
	}
  }

}
