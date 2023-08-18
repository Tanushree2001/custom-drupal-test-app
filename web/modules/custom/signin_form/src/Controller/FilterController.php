<?php

namespace Drupal\signin_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a controller for filtering data.
 */
class FilterController extends ControllerBase {

  /**
   * Filters data based on the provided year and month.
   *
   * @param int|null $year
   *   The year to filter by.
   * @param int|null $month
   *   The month to filter by.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response containing filtered data as JSON.
   */
  public function filterData($year = NULL, $month = NULL) {
    // Select data from the 'signin_form' table with alias 's'.
    $query = \Drupal::database()->select('signin_form', 's')
      ->fields('s') // Select all fields from the 'signin_form' table.

      // Filter records where the YEAR of the 'created' timestamp matches the provided year.
      ->condition('YEAR(FROM_UNIXTIME(s.created))', $year)

      // Filter records where the MONTH of the 'created' timestamp matches the provided month.
      ->condition('MONTH(FROM_UNIXTIME(s.created))', $month);

    // Execute the query and fetch all results.
    $results = $query->execute()->fetchAll();

    // For demonstration purposes, encode the results as JSON and return a response.
    return new Response(json_encode($results));
  }
}

