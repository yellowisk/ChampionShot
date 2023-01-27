<?php 
    session_start();

    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once "model/Shot.php";
    require_once "model/User.php";
    require_once "model/Team.php";
    require_once "model/Matches.php";
    require_once "configs/utils.php";
    require_once "configs/methods.php";

    if (!validParameters($_SESSION, ["idUser"])) {
        reply(401, ["status" => "You must be logged in to access this page!"]);
    }

    if (isMethod("POST")) { 
        if (validParameters($_POST, ["idUser", "idWinner", "idMatch", "scoreA", "scoreB"])) {
            $idUser = $_SESSION["idUser"];
            $idWinner = $_POST["idWinner"];
            $idMatch = $_POST["idMatch"];
            $scoreA = $_POST["scoreA"];
            $scoreB = $_POST["scoreB"];

            $userScore = 0;

            if (User::existsUserID($idUser)) {
                
                if (Team::existsTeamID($idWinner)) {
                    $nameWinner = Team::getName($idWinner);

                    if (Matches::matchExists($idMatch)) {
                        
                        $match = Matches::getMatch($idMatch);
                        $winnerID = $match["idTeamA"];
                        $winnerName = Team::getName($winnerID);
                        $scoreWinner = $match["scoreA"];
                        $scoreLooser = $match["scoreB"];

                        if ($winnerID == $idWinner) {
                            $userScore += 10;
                        }

                        if ($scoreA == $scoreWinner && $scoreB == $scoreLooser) {
                            $userScore += 20;
                        } else if ($scoreA == $scoreWinner || $scoreB == $scoreLooser) {
                            $userScore += 10;
                        }

                        if (Shot::addShot($idUser, $idWinner, $idMatch, $scoreA, $scoreB)) {
                            reply(201, ["shot" => "$nameWinner", "winner" => "$winnerName", "realScore" => "$scoreWinner x $scoreLooser", "scoreShot" => "$scoreA x $scoreB", "scoreUser" => "$userScore"]);
                            if ($userScore > 0) {
                                UserScore::changeUserScore($idUser, $userScore);
                            }
                        } else {
                            reply(500, ["status" => "Failed to register the shot!"]);
                        }
                    } else {
                        reply(400, ["status" => "Match doesn't exists!"]);
                    }
                } else {
                    reply(400, ["status" => "Team doesn't exists!"]); 
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
            if (Shot::existsShotID($id)) {
                $shot = Shot::getShotID($id);
                reply(200, $shot);
            } else {
                reply(200, ["status" => "This shot doesn't exists!"]);
            }    
        } else {
            $shots = Shot::getShots();
            reply(200, $shots);
        }
    }

    if (isMethod("PUT")) {
        if (validParameters($_PUT, ["id", "scoreA", "scoreB", "champion"])) {
            $id = $_PUT["id"];
            $scoreA = $_PUT["scoreA"];
            $scoreB = $_PUT["scoreB"];
            $champion = $_PUT["champion"];

            if (Shot::existsShotID($id)) {
                if (Shot::updateShot($id, $scoreA, $scoreB, $champion)) {
                    reply(200, ["status" => "The shot has been updated successfully!"]);
                } else {
                    reply(500, ["status" => "The shot cannot be updated!"]);
                }
            } else {
                reply(400, ["status" => "The shot doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid data!"]);
        }
    }

    if (isMethod("DELETE")) {
        if (validParameters($_DELETE, ["id"])) {
            $id = $_DELETE["id"];
            if (Shot::existsShotID($id)) {
                if (Shot::deleteShot($id)) {
                    reply(200, ["status" => "The shot has been deleted successfully!"]);
                } else {
                    reply(500, ["status" => "The shot cannot be deleted!"]);
                }
            } else {
                reply(400, ["status" => "The shot doesn't exists!"]);
            }
        } else {
            reply(400, ["status" => "Invalid data!"]);
        }
    }