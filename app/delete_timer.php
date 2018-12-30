<?php
  function deleteTimer ($id) {
    require_once '../app/database.php';
    global $conn;
    $sql = "DELETE FROM timer_heat WHERE t_id=$id";
    if ($conn->query($sql)) {
      return true;
    } else { return false; }
  }
?>