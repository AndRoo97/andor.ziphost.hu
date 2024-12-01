<?php
class ContentModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getContent($identifier) {
        $stmt = $this->db->prepare("SELECT content FROM contents WHERE identifier = ?");
        if ($stmt) {

            $stmt->bind_param('s', $identifier);

            $stmt->execute();

            $stmt->bind_result($content);

            $stmt->fetch();

            $stmt->close();

            return $content;
        } else {
            return null;
        }
    }
}
