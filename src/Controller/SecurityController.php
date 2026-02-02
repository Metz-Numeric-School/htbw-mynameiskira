<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Mns\Buggy\Core\AbstractController;

class SecurityController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login()
    {

        if (!empty($_SESSION['user'])) {
            $_SESSION['admin'] ? header('Location: /admin/dashboard') : header('Location: /user/dashboard');
            die;
        }

        if (!empty($_POST)) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userRepository->findByEmail($username);

            if ($user) {
                // Vérification sécurisée du mot de passe haché
                if (password_verify($password, $user->getPassword())) {

                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'firstname' => $user->getFirstname(),
                    ];

                    if ($user->getIsadmin()) {
                        header('Location: /admin/dashboard');
                        $_SESSION['admin'] = $user->getIsAdmin();
                        exit; // Arrêt du script après redirection
                    } else {
                        header('Location: /dashboard');
                        exit; // Arrêt du script après redirection
                    }
                } else {
                    $error = 'Invalid username or password';
                }
            }

            $error = 'Invalid username or password';
        }

        return $this->render('security/login.html.php', [
            'title' => 'Login',
            'error' => $error ?? null,
        ]);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        session_destroy();
        header('Location: /login');
        exit;
    }
}