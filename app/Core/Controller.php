<?php
namespace App\Core;

class Controller {
    public function view($view, $data = []) {
        if (file_exists('../app/Views/' . $view . '.php')) {
            extract($data);
            require_once '../app/Views/' . $view . '.php';
        } else {
            die('View does not exist.');
        }
    }

    public function model($model) {
        require_once '../app/Models/' . $model . '.php';
        $modelClass = "App\\Models\\$model";
        return new $modelClass();
    }
}
