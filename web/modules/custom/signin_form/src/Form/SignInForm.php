<?php

/**
 * @file
 *  A form to collect an email address for RSVP details.
 */

 namespace Drupal\signin_form\Form;

 use Drupal\Core\Form\FormBase;
 use Drupal\Core\Form\FormStateInterface;

 class SignInForm extends FormBase {
  // Attempt to get the fully loaded node object of the viewed page.
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sign_in_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $node = \Drupal::routeMatch()->getParameter('node');

    // Some pages may not be nodes though and $node will be NULL on those pages.
    // If a node was loaded, get the node id.
    if ( !(is_null($node)) ) {
      $nid = $node->id();
    }  
    else {
      // If a node could not be loaded, default to 0;
      $nid = 0;
    }

    // Establish the $form render array. It has an email text field,
    // a submit button, and a hidden field containing the node ID.
    
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email address'),
      '#size' => 25,
      '#description' => $this->t("We will send updates to the email address you provided"),
      '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
      '#description' => $this->t('Please enter your password.'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('SignIn'),
    ];

    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form; 
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $phone_number = $form_state->getValue('phone_number');
    if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
      $form_state->setErrorByName('phone_number', $this->t('Invalid phone number'));
    }
    
    // $value = $form_state->getValue('email');
    // if ( !(\Drupal::service('email.validator')->isValid($value)) ) {
    //   $form_state->setErrorByName('email', $this->t('It appears that %mail is not valid. Please try again', ['%mail' => $value]));
    // }

    $email = $form_state->getValue('email');
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Invalid Email Format,'));
    }
    else{
      $allowed = ['@gmail.com', '@yahoo.com', '@outlook.com'];
      $domain = strrchr($email, '@');
      if (!in_array($domain, $allowed)) {
        $form_state->setErrorByName('email', $this->t('Invalid email domain. Only Gmail, Yahoo, and Outlook domains are allowed'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // $submitted_email = $form_state->getValue('email');
    // $this->messenger()->addMessage("The form is working! You entered @entry.", ['@entry' => $submitted_email]);
    $this->messenger()->addMessage("The form is working!");
  }
 }