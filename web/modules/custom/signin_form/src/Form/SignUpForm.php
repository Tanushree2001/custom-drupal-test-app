<?php

/**
 * @file
 *  A form to collect an email address for RSVP details.
 */

 namespace Drupal\signin_form\Form;

 use Drupal\Core\Form\FormBase;
 use Drupal\Core\Form\FormStateInterface;

 class SignUpForm extends FormBase {
  // Attempt to get the fully loaded node object of the viewed page.
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rsvplist_email_form';
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

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#required' => TRUE,
      '#attributes' => [
        'pattern' => '[0-9]{10}',
      ],
    ];

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email address'),
      '#size' => 25,
      '#description' => $this->t("We will send updates to the email address you provided"),
      '#required' => TRUE,
    ];

    $form['stream'] = [
      '#type' => 'textfield', // You can change this to a select field if needed
      '#title' => $this->t('Stream'),
      '#required' => TRUE,
    ];

    $form['joining_year'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Joining Year'),
        '#required' => TRUE,
      ];
  
      $form['passing_year'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Passing Year'),
        '#required' => TRUE,
      ];
    $form['password'] = [
        '#type' => 'password',
        '#title' => $this->t('Password'),
        '#required' => TRUE,
        '#description' => $this->t('Please enter your password (at least 8 characters).'),
      ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('SignUp'),
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
    $submitted_email = $form_state->getValue('email');
    // $this->messenger()->addMessage("The form is working! You entered @entry.", ['@entry' => $submitted_email]);
    $this->messenger()->addMessage("The form is working!");
    try {
      //Begin phase 1: Initiate variable to save
      //Get current user id

      $uid = \Drupal::currentUser()->id();

      //Demonstration for how to load a full user object of the current user.
      //this $full_user variable is not needed in this code.
      // but it shown for demonstration purposes.
      $full_user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

      //Obtain value as entered in the form
      $nid = $form_state->getValue('nid');
      $email = $form_state->getValue('email');
      $fullname = $form_state->getValue('full_name');
      $stream = $form_state->getValue('stream');
      $joiningyear = $form_state->getValue('joining_year');

      $current_time = \Drupal::time()->getRequestTime();
      //End Phase 1

      // Begin Phase 2: Save the values to the database

      // Start to build a query builder object $query.
      $query = \Drupal::database()->insert('signin_form');

      //Specify the fields that the query will insert into.
      $query->fields([
        'uid',
        'nid',
        'full_name',
        'stream',
        'joining_year',
        'mail',
        'created',
      ]);

      //Set the values of the fields we selected.
      // Note that they must be in the same order as we defined them.
      // in the $query->fields([....]) above.
      $query->values([
        $uid,
        $nid,
        $fullname,
        $stream,
        $joiningyear,
        $email,
        $current_time,
      ]);

      //Execute the query!
      // Drupal handles the exact syntax of the query automatically!
      $query->execute();
      // End Phase 2
    }
    catch(\Exception $e) {
      \Drupal::messenger()->addError('Unable to save Signup form settings at this time due to database error. Please try again');
    }
  }
 }