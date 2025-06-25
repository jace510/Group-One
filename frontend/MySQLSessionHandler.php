<?php


class MySQLSessionHandler implements SessionHandlerInterface {
    private $db;
    private $table = 'sessions';

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public function open($savePath, $sessionName) {
        // Optional: perform checks or log opening session
        return true;
    }

    public function close() {
        // Optional: cleanup or close DB connection
        return true;
    }

    public function read($id) {
        $stmt = $this->db->prepare("SELECT data FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['data'];
        }
        return '';
    }

    public function write($id, $data) {
        $timestamp = time();
        $stmt = $this->db->prepare("
            REPLACE INTO {$this->table} (id, data, timestamp) 
            VALUES (:id, :data, :timestamp)
        ");
        return $stmt->execute([
            'id' => $id,
            'data' => $data,
            'timestamp' => $timestamp
        ]);
    }

    public function destroy($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function gc($maxlifetime) {
        $old = time() - $maxlifetime;
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE timestamp < :old");
        return $stmt->execute(['old' => $old]);
    }
}
?>
