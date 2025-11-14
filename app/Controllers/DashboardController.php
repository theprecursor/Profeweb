<?php 
namespace App\Controllers;

class DashboardController {

    public function index(): void
    {
        require_once APP_ROOT . DS . 'app' . DS . 'Views' . DS . 'panel' . DS . 'dashboard.view.php'; 
    }

}
?>