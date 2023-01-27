<?php 

    require_once __DIR__ . "/../configs/BancoDados.php";

    class Team {
        public static function getTeams() {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM Team");
                $stmt->execute();

                return $stmt->fetchAll();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getTeamID($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM Team WHERE id = ?");
                $stmt->execute([$id]);

                return $stmt->fetchColumn();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getName($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT name FROM Team WHERE id = ?");
                $stmt->execute([$id]);

                return $stmt->fetchColumn();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function getChance($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT chance FROM Team WHERE id = ?");
                $stmt->execute([$id]);

                return $stmt->fetchColumn();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function addTeam($name, $initials) {
            try {
                $chance = rand(1, 100);
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO Team (name, initials, chance) VALUES (?, ?, ?)");
                $stmt->execute([$name, $initials, $chance]);

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

        public static function updateTeam($id, $name, $initials) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("UPDATE Team SET name = ?, initials = ? WHERE id = ?");
                $stmt->execute([$name, $initials, $id]);

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

        public static function deleteTeam($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM Team WHERE id = ?");
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
    
        public static function existsTeamID($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT COUNT(*) FROM Team WHERE id = ?");
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