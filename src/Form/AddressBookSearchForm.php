<?php

/**
 * @file
 * Contains \Drupal\addressbook\Form\AddressBookSearchForm.
 */ 

namespace Drupal\addressbook\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class AddressBookSearchForm extends FormBase {
  
  public function getFormId() {
    return 'addressbook_search_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['search_type'] = array(
      '#title' => $this->t('Search Type'),
      '#type' => 'select',
      '#options' => array(
        'name' => 'Name',
        'email' => 'E-mail',
        'phone' => 'Phone',
        'dob' => 'DOB',
      ),
    );
    $form['search_term'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Search Term'),
      '#states' => array(
        'visible' => array(
          ':input[name="search_type"]' => array(
            array('value' => 'name'),
            array('value' => 'phone'),
          ),
        ),
      ),
    );
    $form['search_email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#states' => array(
        'visible' => array(
          ':input[name="search_type"]' => array('value' => 'email'),
        ),
      ),
    );
    $form['search_date'] = array(
      '#type' => 'date',
      '#title' => $this->t('Search Date'),
      '#states' => array(
        'visible' => array(
          ':input[name="search_type"]' => array('value' => 'dob'),
        ),
      ),
    );
    $form['submit'] = array(
      '#value' => $this->t('Search'),
      '#type' => 'submit',
    );
    return $form;
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $type = $form_state->getValue('search_type');
	$search_term = '';
    if ($type == 'name' || $type == 'phone') {
      $search_term = !empty($form_state->getValue('search_term')) ? $form_state->getValue('search_term') : '';
    }
    elseif ($type == 'email') {
      $search_term = !empty($form_state->getValue('search_email')) ? $form_state->getValue('search_email') : '';
    }
    elseif ($type == 'dob') {
	  $search_term = !empty($form_state->getValue('search_date')) ? strtotime($form_state->getValue('search_date')) : '';
    }
    $form_state->setRedirect('addressbook.search', array(
      'search_type' => $type,
      'search_term' => $search_term,
    ));
  }
  
}
