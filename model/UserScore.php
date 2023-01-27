<?php 

    require_once __DIR__ . "/../configs/BancoDados.php";

    class UserScore { 
        public static function getUserScores() {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM Score");
                $stmt->execute();

                return $stmt->fetchAll();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function addUserScore($idUser) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO UserScore (idUser, score) VALUES (?, ?)");
                $stmt->execute([$idUser, 0]);

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

        public static function changeUserScore($idUser, $score) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("UPDATE UserScore SET score = ? WHERE idUser = ?");
                $stmt->execute([$score, $idUser]);

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

        public static function deleteScoreByUser($idUser) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM UserScore WHERE idUser = ?");
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
    }