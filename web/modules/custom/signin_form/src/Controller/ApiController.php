<?php

// namespace Drupal\signin_form\Controller;

// use Drupal\Core\Controller\ControllerBase;
// use Drupal\Core\Database\Database;
// use Symfony\Component\HttpFoundation\JsonResponse;

// /**
//  * Controller for API endpoints related to student information.
//  */
// class ApiController extends ControllerBase {

//   /**
//    * Lists all students.
//    *
//    * @return \Symfony\Component\HttpFoundation\JsonResponse
//    *   A JSON response containing the student data.
//    */
//   public function listStudents() {
//     // Get the default database connection.
//     $connection = Database::getConnection();

//     // Build and execute the query to retrieve student data.
//     $query = $connection->select('signin_form', 's')
//       ->fields('s', ['id', 'full_name', 'stream', 'joining_year'])
//       ->orderBy('id');
    
//     $results = $query->execute()->fetchAll();

//     // Build an array of student data.
//     $students = [];
//     foreach ($results as $result) {
//       $students[] = [
//         'id' => $result->id,
//         'full_name' => $result->full_name,
//         'stream' => $result->stream,
//         'joining_year' => $result->joining_year,
//       ];
//     }

//     // Return a JSON response containing student data.
//     return new JsonResponse($students);
//   }
// }
namespace Drupal\signin_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for API endpoints related to student information.
 */
class ApiController extends ControllerBase {

  /**
   * Lists students based on optional parameters.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The HTTP request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the student data.
   */
  public function listStudents(Request $request) {
    // Get the default database connection.
    $connection = Database::getConnection();

    // Build the query to retrieve student data.
    $query = $connection->select('signin_form', 's')
      ->fields('s', ['id', 'full_name', 'stream', 'joining_year'])
      ->orderBy('id');

    // Get query parameters.
    $parameters = $request->query->all();
    
    if (isset($parameters['stream'])) {
      $query->condition('s.stream', $parameters['stream']);
    }

    if (isset($parameters['joining_year'])) {
      $query->condition('s.joining_year', $parameters['joining_year']);
    }
    
    $results = $query->execute()->fetchAll();

    // Build an array of student data.
    $students = [];
    foreach ($results as $result) {
      $students[] = [
        'id' => $result->id,
        'full_name' => $result->full_name,
        'stream' => $result->stream,
        'joining_year' => $result->joining_year,
      ];
    }

    // Return a JSON response containing student data.
    return new JsonResponse($students);
  }
}
