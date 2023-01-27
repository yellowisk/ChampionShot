<?php 

    require_once __DIR__ . "/../configs/BancoDados.php";

    class Shot {
        public static function getShots() {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM Shot");
                $stmt->execute();

                return $stmt->fetchAll();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getShotID($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM Shot WHERE id = ?");
                $stmt->execute();

                return $stmt->fetchAll()[0];
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function addShot($idUser, $idWinner, $idMatch, $scoreA, $scoreB) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO Shot (idUser, idWinner, idMatch, scoreA, scoreB) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$idUser, $idWinner, $idMatch, $scoreA, $scoreB]);
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

        public static function updateShot($id, $idWinner, $scoreA, $scoreB) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("UPDATE Shot SET scoreA = ?, scoreB = ?, idWinner = ? WHERE id = ?");
                $stmt->execute([$scoreA, $scoreB, $idWinner, $id]);

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

        public static function deleteShotByTeam($idWinner) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM Shot WHERE idWinner = ?");
                $stmt->execute([$idWinner]);

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

        public static function deleteShotByUser($idUser) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM Shot WHERE idUser = ?");
                $stmt->execute([$idUser]);

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

        public static function deleteShotMatch($idMatch) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM Shot WHERE idMatch = ?");
                $stmt->execute([$idMatch]);

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

        public static function deleteShot($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM Shot WHERE id = ?");
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

        public static function existsShotID($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT COUNT(*) FROM Shot WHERE id = ?");
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