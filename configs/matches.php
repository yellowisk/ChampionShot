<?php
    session_start();

    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once "model/Team.php";
    require_once "model/User.php";
    require_once "model/Shot.php";
    require_once "model/Matches.php";
    require_once "configs/utils.php";
    require_once "configs/methods.php";

    if (!validParameters($_SESSION, ["idUser"])) {
        reply(401, ["status" => "You must be logged in to access this page!"]);
    }

    if (isMethod("POST")) {
        if (validParameters($_POST, ["nameA", "nameB"])) {
            $nameA = $_POST["nameA"];
            $nameB = $_POST["nameB"];

            $idTeamA = Matches::getTeamID($nameA);
            $idTeamB = Matches::getTeamID($nameB);

            if ($idTeamA == $idTeamB) {
                reply(400, ["status" => "The teams must be different!"]);
            } else {
                reply(200, ["idTeamA" => $idTeamA, "idTeamB" => $idTeamB]);
            }
        }

        if (validParameters($_POST, ["idA", "idB"])) {
            $idTeamA = $_POST["idA"];
            $idTeamB = $_POST["idB"];

            $nameTeamA = Team::getName($idTeamA);
            $nameTeamB = Team::getName($idTeamB);

            if ($idTeamA == $idTeamB) {
                reply(400, ["status" => "The teams must be different!"]);
            } else {
                reply(200, ["nameTeamA" => $nameTeamA, "nameTeamB" => $nameTeamB]);
            }
        }

        if (validParameters($_POST, ["idMatch"])) {
            $idMatch = $_POST["idA"];

            $nameTeamA = Team::getName($idTeamA);
            $nameTeamB = Team::getName($idTeamB);

            if ($idTeamA == $idTeamB) {
                reply(400, ["status" => "The teams must be different!"]);
            } else {
                reply(200, ["nameTeamA" => $nameTeamA, "nameTeamB" => $nameTeamB]);
            }
        }

        if (validParameters($_POST, ["idTeamA", "idTeamB"])) {
            $idTeamA = $_POST["idTeamA"];
            $idTeamB = $_POST["idTeamB"];
            $idUser = $_SESSION["idUser"];

            $chanceA = Team::getChance($idTeamA);
            $chanceB = Team::getChance($idTeamB);

            $winnerArray = Matches::getWinnner($chanceA, $chanceB, $idTeamA, $idTeamB);
            $winnerID = $winnerArray[0];
            $winnerTeam= Team::getName($winnerID);
            $winnerChance = $winnerArray[1];
            $score = Matches::getScore($winnerChance);
            $scoreWinner = $score["winner"];
            $scoreLooser = $score["looser"];

            if (User::existsUserID($idUser)) {
                if (Team::existsTeamID($idTeamA)) {
                    if (Team::existsTeamID($idTeamB)) {
                        if (Matches::addMatch($idTeamA, $idTeamB, $scoreWinner, $scoreLooser)) {
                            reply(200, ["status" => "Match registered successfully!"]);
                        } else {
                            reply(500, ["status" => "Match cannot be registered!"]);
                        }
                    } else {
                        reply(400, ["status" => "The team B doesn't exists!"]);
                    }
                } else {
                    reply(400, ["status" => "The team A doesn't exists!"]);
                }
            } else {
                reply(400, ["status" => "The user doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid data!"]);
        } 
    }

    if (isMethod("GET")) {
        $matches = Matches::getMatches();
        reply(201, $matches);
    }

    if (isMethod("DELETE")) {
        if (validParameters($_DELETE, ["id"])) {
            $id = $_DELETE["id"];

            if (Matches::matchExists($id)) {
                if (Shot::deleteShotMatch($id)) {
                    if (Matches::deleteMatch($id)) {
                        reply(200, ["status" => "The match has been deleted successfully!"]);
                    } else {
                        reply(500, ["status" => "The match cannot be deleted!"]);
                    }
                } else {
                    reply(500, ["status" => "The associated shot couldn't be deleted!"]);
                }
            } else {
                reply(400, ["status" => "The match doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid ID!"]);
        }
    }