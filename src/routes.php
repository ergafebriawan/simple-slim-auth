<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $app->get('/', function (Request $req, Response $res, $args) {
        return $res->withRedirect('/home');
    });

    $app->get('/home', function (Request $req, Response $res, $args) {
        $data = [
            "state" => $_SESSION['state'],
            "name" => $_SESSION['name']
        ];
        return $this->view->render($res, 'home.html', $data);
    });

    $app->any('/login', function (Request $req, Response $res, $args) {
        if ($req->isPost()) {
            $post_req = $req->getParams();
            $username = $post_req['username'];
            $password = md5($post_req['password']);

            $query = $this->db->query("SELECT * FROM users WHERE email='$username'");
            $result = $query->fetch_assoc();

            if ($result['email'] == $username && $result['password'] == $password) {
                session_start();
                $_SESSION['state'] = 'login';
                $_SESSION['name'] = $result['username'];
                $data = [
                    "state" => $_SESSION['state'],
                    "name" => $_SESSION['name']
                ];
                return $this->view->render($res, 'home.html', $data);
            } else {
                $this->flash->addMessage('error', 'email or password invalid');

                return $this->view->render($res, 'login.html', [
                    'flash' => $this->flash
                ]);
            }
        }
        return $this->view->render($res, 'login.html');
    });


    $app->get('/logout', function (Request $req, Response $res, $args) {
        unset($_SESSION['state']);
        unset($_SESSION['name']);
        session_destroy();
        return $res->withRedirect('/login');
    });

    $app->get('/about', function (Request $req, Response $res, $args) {
        $data = [
            "state" => $_SESSION['state'],
            "name" => $_SESSION['name']
        ];
        return $this->view->render($res, 'about.html', $data);
    });

    $app->get('/directory', function (Request $req, Response $res, $args) {
        $data = [
            "state" => $_SESSION['state'],
            "name" => $_SESSION['name']
        ];
        if($_SESSION['state'] == ''){
            return $res->withRedirect('/login');
        }
        $list_dir = ['Case', 'Project', 'Library'];
        return $this->view->render($res, 'directory.html', $data);
    });

    $app->get('/library', function (Request $req, Response $res, $args) {
        return $this->view->render($res, 'library.html', $args);
    });
};
