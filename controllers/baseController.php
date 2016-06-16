<?
    abstract class BaseController{
        public $controller;

        protected function redirect($url){
            header("Location: $url");
            exit();
        }

        abstract protected function action();
    }

?>