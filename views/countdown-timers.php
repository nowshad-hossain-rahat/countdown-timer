<style>
  <?php require_once plugin_dir_path(dirname(__FILE__)) . 'css/admin-page.css'; ?>
</style>

<?php

require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

use Nhrdev\CountdownTimer\AdminPages;
use Nhrdev\CountdownTimer\DbHandler;


class CountdownTimers extends WP_List_Table
{

  public function prepare_items()
  {

    $certificates = DbHandler::getAllTimers(ARRAY_A);

    $per_page = 20;
    $current_page = $this->get_pagenum();
    $total_items = count($certificates);

    $this->set_pagination_args([
      'per_page' => $per_page,
      'total_items' => $total_items
    ]);

    $certificates = array_slice($certificates, (($current_page - 1) * $per_page), $per_page);

    $this->items = $certificates;
    $cols = $this->get_columns();
    $this->_column_headers = [$cols];
  }

  public function get_columns()
  {
    $columns = [
      'timer_name' => 'Name',
      'countdown_till' => 'Countdown till',
      'status' => 'Status',
      'updated_at' => 'Last updated',
      'actions' => 'Actions'
    ];

    return $columns;
  }

  public function column_default($item, $col_name)
  {

    switch ($col_name) {
      case 'timer_name':
      case 'countdown_till':
      case 'status':
      case 'updated_at':
        return $item[$col_name];
      case 'actions':

        $page = $_GET['page'];
        $id = $item['timer_id'];
        $editAction = AdminPages::$editAction;
        $deleteAction = AdminPages::$deleteAction;

        return "<a href='?page=$page&action=$editAction&timer_id=$id'>Edit</a> | " .
          "<a href='?page=$page&action=$deleteAction&timer_id=$id' style='color: firebrick;'>Delete</a>";
      default:
        return 'NULL';
    }
  }
}

function displayTimers()
{

  // to show a cancellable admin notice
  function notify(string $msg, string $type, bool $is_dismissible = true)
  {
    $dismissible = ($is_dismissible == true) ? "is-dismissible" : '';
    $notice_board = "<div style='margin-left: 2px;margin-right: 10px;' class='notice notice-$type $dismissible'>" .
      "<p>$msg</p>" .
      "</div>";
    echo $notice_board;
  }

  // delete a certificate
  $action = isset($_GET['action']) ? $_GET['action'] : '';

  if ($action == AdminPages::$deleteAction) {

    $timer_id = intval($_GET['timer_id']);
    $deleted = DbHandler::deleteTimer($timer_id);
    if ($deleted) {
      notify('Timer deleted sccessfully!', 'success');
    }
  }


  $NhrCountdownTimers = new CountdownTimers();
  $NhrCountdownTimers->prepare_items();
  echo "<h1> Countdown Timers </h1>";
  $NhrCountdownTimers->display();
}

displayTimers();

?>
