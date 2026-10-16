<?php

require_once __DIR__ . '/model.php';

class Controller
{
    private Model $model;

    public function __construct(string $page)
    {
        try {
            $this->model = new Model();
            switch ($page) {
                case 'dev':
                    $this->showDev();
                    break;
                case 'add':
                    $this->traitAdd();
                    break;
                case 'formAdd':
                    $this->form();
                    break;
                case 'formUpdate':
                    $this->form();
                    break;
                case 'update':
                    $this->traitUpdate();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'home':
                default:
                    $this->home();
                    break;
            }
        } catch (Throwable $exception) {
            error_log($exception->__toString());
            http_response_code(500);
            $errorTitle = 'Une erreur est survenue';
            $errorMessage = 'Les données n’ont pas pu être chargées. Vérifiez la connexion à MongoDB puis réessayez.';
            include __DIR__ . '/error.php';
        }
    }

    private function home(): void
    {
        $devs = $this->model->getAllDevs();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/home.php';
    }

    private function showDev(): void
    {
        $dev = $this->model->getDevById($this->inputString($_GET, 'id'));
        if ($dev === null) {
            http_response_code(404);
            $errorTitle = 'Profil introuvable';
            $errorMessage = 'Ce profil n’existe pas ou a été supprimé.';
            include __DIR__ . '/error.php';
            return;
        }

        include __DIR__ . '/showDev.php';
    }

    private function traitAdd(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('formAdd');
        }
        $this->verifyCsrfToken();

        $formData = $this->readFormData();
        $formErrors = $this->validateFormData($formData);
        $isEditing = false;

        if ($formErrors !== []) {
            include __DIR__ . '/add.php';
            return;
        }

        $this->model->addDev($formData);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Le profil a bien été ajouté.'];
        $this->redirect('home');
    }

    private function traitUpdate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('home');
        }
        $this->verifyCsrfToken();

        $id = $this->inputString($_POST, 'id');
        $existingDev = $this->model->getDevById($id);
        if ($existingDev === null) {
            http_response_code(404);
            $errorTitle = 'Profil introuvable';
            $errorMessage = 'Ce profil n’existe pas ou a été supprimé.';
            include __DIR__ . '/error.php';
            return;
        }

        $formData = $this->readFormData();
        $formErrors = $this->validateFormData($formData);
        $isEditing = true;

        if ($formErrors !== []) {
            include __DIR__ . '/add.php';
            return;
        }

        if (!$this->model->updateDev($id, $formData)) {
            throw new RuntimeException('Le profil à modifier est introuvable.');
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Les modifications ont bien été enregistrées.'];
        $this->redirect('dev', ['id' => $id]);
    }

    private function form(): void
    {
        $isEditing = ($_GET['action'] ?? '') === 'formUpdate';
        $formErrors = [];

        if ($isEditing) {
            $dev = $this->model->getDevById($this->inputString($_GET, 'id'));
            if ($dev === null) {
                http_response_code(404);
                $errorTitle = 'Profil introuvable';
                $errorMessage = 'Ce profil n’existe pas ou a été supprimé.';
                include __DIR__ . '/error.php';
                return;
            }
            $id = $dev['_id'];
            $formData = $dev;
        } else {
            $id = '';
            $formData = [
                'nom' => '',
                'prenom' => '',
                'type' => 'Frontend',
                'anneeDexperience' => '',
                'niveau' => '',
                'technos' => [],
                'pays' => '',
            ];
        }

        include __DIR__ . '/add.php';
    }

    private function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('home');
        }
        $this->verifyCsrfToken();

        $id = $this->inputString($_POST, 'id');
        if (!$this->model->deleteDev($id)) {
            http_response_code(404);
            $errorTitle = 'Profil introuvable';
            $errorMessage = 'Ce profil n’existe pas ou a déjà été supprimé.';
            include __DIR__ . '/error.php';
            return;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Le profil a bien été supprimé.'];
        $this->redirect('home');
    }

    private function readFormData(): array
    {
        $technos = $_POST['technos'] ?? [];
        if (!is_array($technos)) {
            $technos = [];
        }

        return [
            'nom' => $this->postText('nom'),
            'prenom' => $this->postText('prenom'),
            'type' => $this->postText('type'),
            'anneeDexperience' => filter_var(
                is_scalar($_POST['anneeDexperience'] ?? null) ? $_POST['anneeDexperience'] : null,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 0]]
            ),
            'niveau' => $this->postText('niveau'),
            'technos' => array_values(array_unique(array_filter(
                array_map(
                    static fn ($techno): string => is_scalar($techno) ? trim(strip_tags((string) $techno)) : '',
                    $technos
                ),
                static fn (string $techno): bool => $techno !== ''
            ))),
            'pays' => $this->postText('pays'),
        ];
    }

    private function postText(string $field): string
    {
        return trim(strip_tags($this->inputString($_POST, $field)));
    }

    private function inputString(array $source, string $field): string
    {
        $value = $source[$field] ?? '';

        return is_scalar($value) ? (string) $value : '';
    }

    private function validateFormData(array $data): array
    {
        $errors = [];

        foreach (['prenom' => 'prénom', 'nom' => 'nom'] as $field => $label) {
            if ($data[$field] === '') {
                $errors[$field] = 'Veuillez renseigner le ' . $label . '.';
            } elseif (mb_strlen($data[$field]) > 80) {
                $errors[$field] = 'Cette valeur ne peut pas dépasser 80 caractères.';
            }
        }

        if ($data['type'] === '' || mb_strlen($data['type']) > 40) {
            $errors['type'] = 'Choisissez ou saisissez un type de profil (40 caractères maximum).';
        }
        if ($data['anneeDexperience'] === false) {
            $errors['anneeDexperience'] = 'Saisissez un nombre d’années d’expérience valide, égal ou supérieur à 0.';
        }
        if (!in_array($data['niveau'], ['Junior', 'Medium', 'Senior', 'Lead'], true)) {
            $errors['niveau'] = 'Choisissez un niveau parmi les options proposées.';
        }
        foreach ($data['technos'] as $techno) {
            if (mb_strlen($techno) > 40) {
                $errors['technos'] = 'Chaque technologie ne peut pas dépasser 40 caractères.';
                break;
            }
        }
        if (mb_strlen($data['pays']) > 80) {
            $errors['pays'] = 'Le pays ne peut pas dépasser 80 caractères.';
        }

        return $errors;
    }

    private function verifyCsrfToken(): void
    {
        $submittedToken = $this->inputString($_POST, 'csrf_token');
        if (!hash_equals((string) ($_SESSION['csrf_token'] ?? ''), $submittedToken)) {
            http_response_code(403);
            $errorTitle = 'Formulaire expiré';
            $errorMessage = 'Rechargez la page avant de réessayer.';
            include __DIR__ . '/error.php';
            exit;
        }
    }

    private function redirect(string $action, array $parameters = []): never
    {
        $query = http_build_query(['action' => $action] + $parameters);
        header('Location: index.php?' . $query);
        exit;
    }
}
