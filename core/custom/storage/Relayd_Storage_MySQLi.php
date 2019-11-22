<?php

class Relayd_Storage_MySQLi_SQLWrapper {

    private $relayd;
    private $mysqli = null;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    public function getMySQLi(): mysqli {
        if ($this->mysqli == null) {
            $mysqlConfig = $this->relayd->getServerConfig()['storage'];
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

class Relayd_Storage_MySQLi implements RelaydStorage {

    private $relayd;
    private $sqlWrapper;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
        $this->sqlWrapper = new Relayd_Storage_MySQLi_SQLWrapper($relayd);
    }

    public function getSentMessage(string $uuid): SentMessage {
        $result = $this->sqlWrapper->query("SELECT * FROM sentMessages WHERE uuid = ?", [$uuid]);
        if ($row = $result->fetch_assoc()) {
            return new SentMessage($this->relayd, $row, true);
        }
        return new SentMessage($this->relayd, array(), false);
    }

    public function createSentMessage(string $from, string $to, string $text): SentMessage {
        $time = time();
        $generated_uuid = $this->relayd->findRandomCode("sentMessage", 64);
        $this->sqlWrapper->execute("INSERT INTO sentMessages (uuid, time, toAddress, fromAddress, text) VALUES (?, ?, ?, ?, ?)", [
            $generated_uuid, $time, $to, $from, $text
        ]);
        return $this->getSentMessage($generated_uuid);
    }

    public function removeSentMessage(SentMessage $message): void {
        $this->sqlWrapper->execute("DELETE FROM sentMessages WHERE uuid = ?", [$message->getuuid()]);
    }

    public function getReceivedMessage(string $uuid): ReceivedMessage {
        $result = $this->sqlWrapper->query("SELECT * FROM receivedMessages WHERE uuid = ?", [$uuid]);
        if ($row = $result->fetch_assoc()) {
            return new ReceivedMessage($this->relayd, $row, true);
        }
        return new ReceivedMessage($this->relayd, array(), false);
    }

    public function createReceivedMessage(string $from, string $to, string $text): ReceivedMessage {
        $time = time();
        $generated_uuid = $this->relayd->findRandomCode("receivedMessage", 64);
        $this->sqlWrapper->execute("INSERT INTO receivedMessages (uuid, time, toAddress, fromAddress, text) VALUES (?, ?, ?, ?, ?)", [
            $generated_uuid, $time, $to, $from, $text
        ]);
        return $this->getReceivedMessage($generated_uuid);
    }

    public function removeReceivedMessage(ReceivedMessage $message): void {
        $this->sqlWrapper->execute("DELETE FROM receivedMessages WHERE uuid = ?", [$message->getuuid()]);
    }

    public function isAvailableCode(string $category, string $code): bool {
        $table = "";
        $column = "";
        if ($category == "receivedMessage") {
            $table = "receivedMessages";
            $column = "uuid";
        } else if ($category == "sentMessage") {
            $table = "sentMessages";
            $column = "uuid";
        }
        $result = $this->sqlWrapper->query("SELECT * FROM $table WHERE $column = ?", [$code]);
        if ($row = $result->fetch_assoc()) {
            return false;
        } else {
            return true;
        }
    }

    public function getOldestMessagesToFetch(string $address): array {
        $messages = array();
        $result = $this->sqlWrapper->query("SELECT * FROM receivedMessages WHERE toAddress = ? ORDER BY time ASC LIMIT 25", [$address]);
        while ($row = $result->fetch_assoc()) {
            $receivedMessage = new ReceivedMessage($this->relayd, $row, true);
            array_push($messages, $receivedMessage);
        }
        return $messages;
    }
}