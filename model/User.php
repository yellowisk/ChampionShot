<?php 

    require_once __DIR__ . "/../configs/BancoDados.php";
    
    class User {
        public static function getUsers() {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM User");
                $stmt->execute();

                return $stmt->fetchAll();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function registerUser($name, $login, $password) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO User (name, login, password) VALUES (?, ?, ?)");

                $password = password_hash($password, PASSWORD_BCRYPT);
                $stmt->execute([$name, $login, $password]);

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

        public static function login($name, $login, $password) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM User WHERE login = ?");
                $stmt->execute([$login]);
                $currentUser = $stmt->fetchAll();

                if (count($currentUser) != 1) {
                    return false;
                }

                if (password_verify($password, $currentUser[0]["password"])) {
                    return $currentUser[0]["id"];
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function existsUserLogin($login) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT COUNT(*) FROM User WHERE login = ?");
                $stmt->execute([$login]);

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

        public static function existsUserID($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT COUNT(*) FROM User WHERE id = ?");
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

        public static function deleteUser($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM User WHERE id = ?");
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
    }