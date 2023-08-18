<?php
// namespace Drupal\signin_form\Controller;

// use Drupal\Core\Controller\ControllerBase;
// use Drupal\Core\Database\Connection;
// use Symfony\Component\DependencyInjection\ContainerInterface;

// /**
//  * Controller for retrieving inactive students.
//  */
// class InactiveStudentsController extends ControllerBase {

//   /**
//    * The database connection.
//    *
//    * @var \Drupal\Core\Database\Connection
//    */
//   protected $database;

//   /**
//    * Constructs an InactiveStudentsController object.
//    *
//    * @param \Drupal\Core\Database\Connection $database
//    *   The database connection.
//    */
//   public function __construct(Connection $database) {
//     $this->database = $database;
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public static function create(ContainerInterface $container) {
//     return new static(
//       $container->get('database')
//     );
//   }

//   /**
//    * Retrieves inactive students quarterly along with the count.
//    */
//   public function getInactiveStudents() {
//     // Adjust the time range for quarterly inactivity as needed.
//     $inactive_time_range = \Drupal::time()->getRequestTime() - (90 * 24 * 60 * 60); // 90 days

//     $query = $this->database->select('signin_form', 's')
//       ->fields('s', ['id', 'full_name', 'stream'])
//       ->condition('s.created', $inactive_time_range, '<');

//     $results = $query->execute()->fetchAll();

//     $count = count($results);

//     $output = [
//       '#theme' => 'inactive_students_table',
//       '#header' => [
//         $this->t('Student ID'),
//         $this->t('Full Name'),
//         $this->t('Stream'),
//       ],
//       '#rows' => [],
//       '#count' => $count,
//     ];

//     foreach ($results as $result) {
//       $output['#rows'][] = [
//         'id' => $result->id,
//         'full_name' => $result->full_name,
//         'stream' => $result->stream,
//       ];
//     }

//     return $output;
//   }
// }

namespace Drupal\signin_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for retrieving inactive students.
 */
class InactiveStudentsController extends ControllerBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs an InactiveStudentsController object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Retrieves inactive students quarterly along with the count.
   */
  public function getInactiveStudents() {
    // Adjust the time range for quarterly inactivity as needed.
    $inactive_time_range = \Drupal::time()->getRequestTime() - (90 * 24 * 60 * 60); // 90 days

    $query = $this->database->select('signin_form', 's')
      ->fields('s', ['id', 'full_name', 'stream'])
      ->condition('s.created', $inactive_time_range, '<');

    $results = $query->execute()->fetchAll();

    $count = count($results);

    $output = [
      '#theme' => 'inactive_students_table',
      '#header' => [
        $this->t('Student ID'),
        $this->t('Full Name'),
        $this->t('Stream'),
      ],
      '#rows' => [],
      '#count' => $count,
    ];

    foreach ($results as $result) {
      $output['#rows'][] = [
        'id' => $result->id,
        'full_name' => $result->full_name,
        'stream' => $result->stream,
      ];
    }

    return $output;
  }
}
