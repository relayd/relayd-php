<?php

class Relayd_Auth_MySQLi_SQLWrapper {

    private $relayd;
    private $mysqli = null;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    public function getMySQLi(): mysqli {
        if ($this->mysqli == null) {
            $mysqlConfig = $this->relayd->getServerConfig()['auth'];
            $this->mysqli = new mysqli($mysqlConfig['host'], $mysqlConfig['username'], $mysqlConfig['password'], $mysqlConfig['database']);
            if ($this->mysqli->connect_errno) {
                die("Oh no, there was a database connection error!");
            }
        }
        return $this->mysqli;
    }

    public function execute(string $sql, $params = [], $types = ""): ?mysqli_stmt {
        $types = $types ?: str_repeat("s", count($params));
        if ($stmt = $this->getMySQLi()->prepare($sql)) {
            if (sizeof($params) != 0) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            return $stmt;
        }
        return null;
    }

    public function query(string $sql, $params = [], $types = ""): ?mysqli_result {
        $stmt = $this->execute($sql, $params, $types);
        if ($stmt != null) {
            return $stmt->get_result();
        }
        return null;
    }
}

class Relayd_Auth_MySQLi implements RelaydAuth {

    private $relayd;
    private $sqlWrapper;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
        $this->sqlWrapper = new Relayd_Auth_MySQLi_SQLWrapper($relayd);
    }

    public function isValidAuthKey(string $authKey): bool {
        $result = $this->sqlWrapper->query("SELECT * FROM authKeys WHERE authKey = ?", [$authKey]);
        if ($row = $result->fetch_assoc()) {
            return true;
        }
        return false;
    }

    public function canUseAddress(string $authKey, string $address): bool {
        if (!$this->isValidAuthKey($authKey)) {
            return false;
        }
        return in_array($address, $this->getAddressesForAuthKey($authKey));
    }

    public function getAddressesForAuthKey(string $authKey): array {
        if (!$this->isValidAuthKey($authKey)) {
            return array();
        }
        $result = $this->sqlWrapper->query("SELECT * FROM authKeys WHERE authKey = ?", [$authKey]);
        $addresses = array();
        while ($row = $result->fetch_assoc()) {
            array_push($addresses, $row['address']);
        }
        return $addresses;
    }

    public function canGetReceivedMessage(string $authKey, string $uuid): bool {
        $receivedMessage = $this->relayd->getReceivedMessage($uuid);
        if (!$receivedMessage->doesExist()) {
            return false;
        }
        return $this->canUseAddress($authKey, $receivedMessage->getTo());
    }
}