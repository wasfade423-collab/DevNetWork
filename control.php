<?php
require_once("model.php");
class Controller
{
    private $model;
    private function init_model()
    {
        $this->model = new Model();
    }
    function __construct($page)
    {
        if (!is_array($page)) {
            $this->home();
            return;
        } else {
            if (array_key_exists("action", $page)) {
                $action = $page['action'];
                switch ($action) {
                    case "home": {
                            $this->home();
                            break;
                        }
                    case 'dev': {
                        $this->showDev();
                        break;
                    }
                    case "add": {
                        $this->traitAdd();
                        break;
                    }
                    case 'formAdd': {
                        $this->form();
                        break;
                    }
                    case 'formUpdate': {
                        $this->form();
                        break;
                    }
                    case "update": {
                        $this->traitUpdate();
                        break;
                    }
                    case 'delete' : {
                        $this->delete();
                        break;
                    }
                    default : {
                        $this->home();
                        break;
                    }
                    
                    
                }
            } else {
                $this->home();
                return;
            }
        }
    }
    private function home()
    {
        if ($this->init_model()) {
            $devs = $this->model->getAlldevs();

            include("home.php");
        } else {
            echo $error = "Echec de la connection.";
        }
    }

    private function showDev(){
        if ($this->init_model()) {
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $dev = $this->model->getDevById($id);
                include_once("showDev.php");
            }else{
                $this->home();
            }
        } else {
            echo $error = "Echec de la connection.";
        }
    }

    private function traitAdd(){
        if ($this->init_model()) {
            if($_SERVER["REQUEST_METHOD"] =='POST'){
                $devInfos = [];
                $devInfos['nom'] = $_POST["nom"] ?  trim(strip_tags($_POST['nom'])) : "";
                $devInfos['prenom'] = $_POST["prenom"] ? trim(strip_tags($_POST['prenom'])) : "";
                $devInfos['type'] = $_POST['type'] ? trim($_POST['type']) : "";
                $devInfos['anneeDexperience'] = $_POST['anneeDexperience'] ? filter_input(INPUT_POST, "anneeDexperience", FILTER_VALIDATE_INT);
                $devInfos['niveau'] = $_POST["niveau"] ? strip_tags($_POST['niveau']):  "";
                $devInfos['technos'] = $_POST["techns"];
                $devInfos['pays'] = $_POST["pays"] ;
            }
            $dev = $this->model->addDev($devInfos);

            include_once('add.php');
        } else {
            echo $error = "Echec de la connection.";
        }        
    }

    private function traitUpdate(){
        if ($this->init_model()) {
            if($_SERVER["REQUEST_METHOD"] =='POST'){
                $devInfos = [];
                $id = $_POST['id'] ? $_POST['id'] : ""; 
                $devInfos['nom'] = $_POST["nom"] ?  trim(strip_tags($_POST['nom'])) : "";
                $devInfos['prenom'] = $_POST["prenom"] ? trim(strip_tags($_POST['prenom'])) : "";
                $devInfos['type'] = $_POST['type'] ? trim($_POST['type']) : "";
                $devInfos['anneeDexperience'] = $_POST['anneeDexperience'] ? filter_input(INPUT_POST, "anneeDexperience", FILTER_VALIDATE_INT);
                $devInfos['niveau'] = $_POST["niveau"] ? strip_tags($_POST['niveau']):  "";
                $devInfos['technos'] = $_POST["techns"];
                $devInfos['pays'] = $_POST["pays"] ;
                
            }
            $dev = $this->model->updateDev($id, $devInfos);

            include_once('add.php');
        } else {
            echo $error = "Echec de la connection.";
        }              
    }

    private function form(){
        if($this->init_model()){
            if(isset($_GET['id'])){
                $id = $_GET['id'];
            
                $dev = $this->model->getDevById($id);
            }
            
            
            include_once("add.php");
            
        }else
        {
             echo $error = "Echec de la connection.";
        }
    }
    private function delete(){
        if($this->init_model()){
            if(isset($_GET['id'])){
                $id = $_GET['id'];

                $deleteResponse = $this->model->deleteDev($id);
                
                 $this->home();
            }
            else{
                $error = "Quel Dev voulez-vous supprimer ?";
                 $this->home();
            }
        }else
        {
             echo $error = "Echec de la connection.";
        }
    }
}