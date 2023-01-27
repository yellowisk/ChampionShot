<?php
    session_start();

    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once "model/User.php";
    require_once "model/UserScore.php";
    require_once "configs/utils.php";
    require_once "configs/methods.php";

    if (isMethod("POST")) {
        if (validParameters($_SESSION, ["idUser"])) {
            reply(400, ["status" => "You're currently logged in!"]);
        }

        if (validParameters($_POST, ["name", "login", "password", "doLogin"])) {
            $name = $_POST["name"];
            $login = $_POST["login"];
            $password = $_POST["password"];

            if (!User::existsUserLogin($login)) {
                reply(400, ["status" => "User does not exists!"]);
            }

            $result = User::login($name, $login, $password);
            if (!$result) {
                reply(401, ["status" => "Username or password is invalid!"]);
            }

            $_SESSION["idUser"] = $result;
            reply(200, ["status" => "Welcome, $name!"]);
        }

        if (validParameters($_POST, ["name", "login", "password"])) {
            $name = $_POST["name"];
            $login = $_POST["login"];
            $password = $_POST["password"];

            if (!User::existsUserLogin($login)) {
                if (User::registerUser($name, $login, $password)) {
                    $msg = ["status" => "User successfully registered!"];
                    reply(201, $msg);
                    UserScore::addUserScore($id);
                } else {
                    $msg = ["status" => "Failed to register the user!"];
                    reply(500, $msg);
                }
            } else {
                $msg = ["status" => "User already exists!"];
                reply(400, $msg);
            }
        }
    }

    if (isMethod("GET")) {
        if (validParameters($_GET, ["logout"])) {
            session_destroy();
            reply(200, ["status" => "Logout done successfully!"]);
        }

        $users = User::getUsers();
        reply(200, $users);
    }

    if (isMethod("DELETE")) {
        if (validParameters($_DELETE, ["id"])) {
            $id = $_DELETE["id"];

            if (User::existsUserID($id)) {
                if (Shot::deleteShotByUser($id)) {
                    if (UserScore::deleteScoreByUser($id)) {
                        if (User::deleteUser($id)) {
                            reply(200, ["status" => "The user has been deleted successfully!"]);
                        } else {
                            reply(500, ["status" => "The user cannot be deleted!"]);
                        }
                    } else {
                        reply(500, ["status" => "The user score associated cannot be deleted!"]);
                    }
                } else {
                    reply(500, ["status" => "The shot associated cannot be deleted!"]);
                }
            } else {
                reply(400, ["status" => "The user doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid data!"]);
        }
    }