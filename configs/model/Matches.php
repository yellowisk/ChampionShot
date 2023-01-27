<?php 

    require_once __DIR__ . "/../configs/BancoDados.php";

    class Matches {

        public static function getMatches() {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM Matches");
                $stmt->execute();

                return $stmt->fetchAll();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getMatch($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM Matches WHERE id = ?");
                $stmt->execute([$id]);

                return $stmt->fetchAll()[0];
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getTeamID($name) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT id FROM Team WHERE name = ?");
                $stmt->execute([$name]);

                return $stmt->fetchAll()[0];
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getTeamsByMatch($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT idTeamA, idTeamB FROM Match WHERE id = ?");
                $stmt->execute([$id]);

                return $stmt->fetchAll()[0];
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function addMatch($idTeamA, $idTeamB, $scoreA, $scoreB) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO Matches (idTeamA, idTeamB, scoreA, scoreB) VALUES (?, ?, ?, ?)");
                $stmt->execute([$idTeamA, $idTeamB, $scoreA, $scoreB]);

                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getWinnner($chanceA, $chanceB, $idA, $idB) {
            $totalChance = $chanceA + $chanceB;
            $array = [];
            $random = rand(1, 100);
            if ($chanceA > $chanceB) {
                $newChanceA = $totalChance / 2;
                $newChanceB = 100 - $newChanceA;

                if ($newChanceA > $newChanceB) {
                    $chanceGreater = $newChanceA;
                    if ($random <= $chanceGreater) {
                        array_push($array, $idA, $chanceGreater);
                        return $array;
                    } else {
                        array_push($array, $idB, $newChanceB);
                        return $array;
                    }
                } else {
                    $chanceGreater = $newChanceB;
                    if ($random <= $chanceGreater) {
                        array_push($array, $idB, $chanceGreater);
                        return $array;
                    } else {
                        array_push($array, $idA, $newChanceA);
                        return $array;
                    }
                }
            } else {
                $newChanceA = $totalChance / 2;
                $newChanceB = 100 - $newChanceA;

                if ($newChanceA > $newChanceB) {
                    $chanceGreater = $newChanceA;
                    if ($random <= $chanceGreater) {
                        array_push($array, $idA, $chanceGreater);
                        return $array;
                    } else {
                        array_push($array, $idB, $newChanceB);
                        return $array;
                    }
                } else {
                    $chanceGreater = $newChanceB;
                    if ($random <= $chanceGreater) {
                        array_push($array, $idB, $chanceGreater);
                        return $array;
                    } else {
                        array_push($array, $idA, $newChanceA);
                        return $array;
                    }
                }
            }
        }

        public static function getScore($chanceWinner) {
            $scoreWinner = 0;
            $scoreLooser = 0;
            
            if ($chanceWinner > 15) {
                $scoreW = $chanceWinner / 15;
                $scoreW = number_format($scoreW, 0);

                $randomW = rand(1, $scoreW);
                $scoreWinner = $randomW;

                $scoreL = $scoreWinner / 2;
                $scoreL = number_format($scoreL, 0);
                $randomL = rand(0, $scoreL);
                $scoreLooser = $randomL;

                if ($scoreWinner == 1) {
                    $scoreLooser = 0;
                }
                $array = ["winner" => $scoreWinner, "looser" => $scoreLooser];
                return $array;

            } else {
                $scoreWinner = 1;
                $scoreLooser = 0;

                $array = ["winner" => $scoreWinner, "looser" => $scoreLooser];

                return $array;
            }
        }

        public static function deleteMatchByTeam($idTeam) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM Matches WHERE idTeamA = ? OR idTeamB = ?");
                $stmt->execute([$idTeam]);
    
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function deleteMatch($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM Matches WHERE id = ?");
                $stmt->execute([$id]);
    
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function matchExists($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT COUNT(*) FROM Matches WHERE id = ?");
                $stmt->execute([$id]);

                if ($stmt->fetchColumn() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }
    }