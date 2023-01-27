<?php
    session_start();

    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once "model/Team.php";
    require_once "model/User.php";
    require_once "configs/utils.php";
    require_once "configs/methods.php";

    if (!validParameters($_SESSION, ["idUser"])) {
        reply(401, ["status" => "You must be logged in to access this page!"]);
    }

    if (isMethod("POST")) {
        if (validParameters($_POST, ["name", "initials"])) {
            $name = $_POST["name"];
            $initials = $_POST["initials"];
            $idUser = $_SESSION["idUser"];

            if (User::existsUserID($idUser)) {
                if (Team::addTeam($name, $initials)) {
                    reply(201, ["status" => "Team successfully registered!"]);
                } else {
                    reply(500, ["status" => "Failed to register the team!"]);
                }
            } else {
                reply(400, ["status" => "User doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid data!"]);
        }
    }

    if (isMethod("GET")) {
        if (validParameters($_GET, ["id"])) {
            $id = $_GET["id"];
            if (Team::existsTeamID($id)) {
                $team = Team::getTeamID($id);
                reply(200, $team);
            } else {
                reply(200, ["status" => "This team doesn't exists!"]);
            }    
        } else {
            $teams = Team::getTeams();
            reply(200, $teams);
        }
    }

    if (isMethod("PUT")) {
        if (validParameters($_PUT, ["id" , "name", "initials"])) {
            $id = $_PUT["id"];
            $name = $_PUT["name"];
            $initials = $_PUT["initials"];

            if (Team::existsTeamID($id)) {
                if (Team::updateTeam($id, $name, $initials)) {
                    reply(200, ["status" => "$name has been updated successfully!"]);
                } else {
                    reply(500, ["status" => "$name cannot be updated!"]);
                }
            } else {
                reply(400, ["status" => "The team doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid data!"]);
        }
    }

    if (isMethod("DELETE")) {
        if (validParameters($_DELETE, ["id"])) {
            $id = $_DELETE["id"];

            if (Team::existsTeamID($id)) {
                if (Matches::deleteMatchByTeam($id)) {
                    if (Shot::deleteShotByTeam($id)) {
                        if (Team::deleteTeam($id)) {
                            reply(200, ["status" => "$name has been deleted successfully!"]);
                        } else {
                            reply(500, ["status" => "$name cannot be updated!"]);
                        }
                    } else {
                        reply(500, ["status" => "The shot associated couldn't be deleted!"]);
                    }
                } else {
                    reply(500, ["status" => "The match associated couldn't be deleted!"]);
                }
            } else {
                reply(400, ["status" => "The team doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid data!"]);
        }
    }