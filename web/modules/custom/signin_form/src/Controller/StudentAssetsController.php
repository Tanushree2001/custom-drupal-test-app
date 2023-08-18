<?php

// namespace Drupal\signin_form\Controller;

// use Drupal\Core\Controller\ControllerBase;
// use Symfony\Component\HttpFoundation\JsonResponse;

// class StudentAssetsController extends ControllerBase {

//   public function getStudentAssets() {
//     // Use the Drupal database connection service.
//     $database = \Drupal::database();

//     // Build the query to retrieve student data and count of assets.
//     $query = $database->select('signin_form', 'sf')
//       ->fields('sf', ['id', 'full_name', 'stream'])
//       ->leftJoin('node__field_assets_title', 'af', 'sf.id = af.entity_id')
//       ->fields('af', ['entity_id'])
//       ->groupBy('sf.id', 'sf.full_name', 'sf.stream')
//       ->addExpression('COUNT(af.entity_id)', 'assets_count');

//     // Execute the query.
//     $results = $query->execute()->fetchAll();

//     // Prepare the response.
//     $response_data = [];
//     foreach ($results as $result) {
//       $response_data[] = [
//         'Student_ID' => $result->id,
//         'Name' => $result->full_name,
//         'Stream' => $result->stream,
//         'Assets_Count' => $result->assets_count,
//       ];
//     }

//     return new JsonResponse($response_data);
//   }

// } 
