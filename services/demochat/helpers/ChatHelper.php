<?php

/**
 * Chat Helper
 *
 * - Automatically init
 *
 * - Checks pseudonyme (nickname)
 * - Adds nickname
 * - Remove connected user
 * - Get List of connected users
 * - Get Nb Msg
 * - Write a message to another user
 * - Read a message
 * - Delete a message
 * - Answer to a message
 */

namespace service\helpers;

class ChatHelper
{
    private array $connectes = [];
    private string $chat_dir = "";

    public function __construct()
    {
        $this->chat_dir = \MiniPaviFwk\helpers\ConstantHelper::getConstValueByName(
            'CHAT_DIR',
            '\\tmp\\demochat\\'
        );

        // Autoinitialise the chat directory
        if (!is_dir($this->chat_dir)) {
            mkdir($this->chat_dir, 0755, true);
        } else {
            $this->cleanMessages();
        }
    }

    public function createOtherUsersLigne00Msg(string $message): array
    {
        $uniqueIds = [];
        $messages = [];
        $connectes = $this->getConnectes();
        $g2_message = \MiniPavi\MiniPaviCli::toG2($message);
        foreach ($connectes as $connecte) {
            if ($connecte['uniqueId'] !== \MiniPavi\MiniPaviCli::$uniqueId) {
                $uniqueIds[] = $connecte['uniqueId'];
                $messages[] = $g2_message;
            }
        }
        return [$uniqueIds, $messages];
    }

    public function checkPseudonyme(string $pseudonyme): array
    {
        // @TODO AZ az 09 - space
        if (mb_strlen($pseudonyme) < 1 || mb_strlen($pseudonyme) > 16) {
            return [false, "Pseudonyme invalide (1 à 16 caractères)"];
        }

        $connectes = $this->getConnectes();
        foreach ($connectes as $connecte) {
            if ($connecte['uniqueId'] == \MiniPavi\MiniPaviCli::$uniqueId) {
                return [false, 'Utilisateur déjà connecté?!?'];
            }
            if (trim(mb_strtolower($connecte['pseudonyme'])) == trim(mb_strtolower($pseudonyme))) {
                return [false, 'Ce pseudonyme est déjà utilisé.'];
            }
        }

        return [true, ''];
    }

    public function addPseudonyme(string $pseudonyme): void
    {
        $connectes = $this->getConnectes();
        $connectes[] = [
            'uniqueId' => \MiniPavi\MiniPaviCli::$uniqueId,
            'pseudonyme' => $pseudonyme,
            'humeur' => '...',
            'timestamp' => time(),
        ];
        $filename = $this->getConnectesFilename();
        file_put_contents($filename, json_encode($connectes));
    }

    public function checkHumeur(string $humeur): array
    {
        $connecte = $this->getCurrentConnecte();
        $humeurMaxLength = 35 - mb_strlen($connecte['pseudonyme']);
        if (mb_strlen($humeur) < 1 || mb_strlen($humeur) > $humeurMaxLength) {
            return [false, "Humeur invalide (1 à $humeurMaxLength caractères)"];
        }
        return [true, null];
    }

    public function setHumeur(string $humeur): void
    {
        $connectes = $this->getConnectes();
        foreach ($connectes as $key => $connecte) {
            if ($connecte['uniqueId'] == \MiniPavi\MiniPaviCli::$uniqueId) {
                $connectes[$key]['humeur'] = $humeur;
                break;
            }
        }
        $this->setConnectes($connectes);
    }

    public function deconnecteCurrentUser(): void
    {
        $connectes = $this->getConnectes();
        $clean_connectes = [];
        foreach ($connectes as $key => $connecte) {
            if ($connecte['uniqueId'] !== \MiniPavi\MiniPaviCli::$uniqueId) {
                $clean_connectes[] = $connecte;
            }
        }
        $this->setConnectes($clean_connectes);
    }

    public function removeAllMessagesForCurrentUser(): void
    {
        $uniqueId = \MiniPavi\MiniPaviCli::$uniqueId;
        $messageFilenames = glob($this->chat_dir . "msg-*-*-$uniqueId.json");
        foreach ($messageFilenames as $messageFilename) {
            unlink($messageFilename);
        }
    }

    public function getConnectes(): array
    {
        if (empty($this->connectes)) {
            $filename = $this->getConnectesFilename();
            $this->connectes = [];
            if (file_exists($filename)) {
                $this->connectes = json_decode(file_get_contents($filename), true);
            }
        }

        return $this->filterOutTimedOutConnectes($this->connectes);
    }

    public function getConnecteById(string $uniqueId): array
    {
        $connectes = $this->getConnectes();
        foreach ($connectes as $connecte) {
            if ($connecte['uniqueId'] == $uniqueId) {
                return $connecte;
            }
        }
        return [];
    }

    public function getCurrentConnecte(): array
    {
        return $this->getConnecteById(\MiniPavi\MiniPaviCli::$uniqueId);
    }

    protected function setConnectes(array $connectes): void
    {
        $this->connectes = $connectes;
        $filename = $this->getConnectesFilename();
        file_put_contents($filename, json_encode($connectes));
    }

    public function watchdog(): void
    {
        $connectes = $this->getConnectes();
        foreach ($connectes as $key => $connecte) {
            if ($connecte['uniqueId'] == \MiniPavi\MiniPaviCli::$uniqueId) {
                $connectes[$key]['timestamp'] = time();
                break;
            }
        }
        $this->setConnectes($connectes);
    }

    public function getNbMessage(): int
    {
        $uniqueId = \MiniPavi\MiniPaviCli::$uniqueId;
        return count(glob($this->chat_dir. "msg-*-*-$uniqueId.json"));
    }

    public function getFirstMessageFilename(): ?string
    {
        $filenames = glob("*.txt"); // Fetch all .txt files
        if (empty($filenames)) {
            return null;
        }
        // msg-{TIMESTAMP}-... : always returns the oldest message
        sort($filenames);
        return $filenames[0];
    }

    public function readMessage(string $filename): array
    {
        return json_decode(file_get_contents($this->chat_dir . $filename));
    }

    public function sendMessage(string $destUniqueId, array $message, array $oldMessage = []): void
    {
        // if old messsage is not present, make it one line per message line ;)
        if (empty($oldMessage)) {
            $oldMessage = [];
            foreach ($message as $line) {
                $oldMessage[] = "";
            }
        }

        // Send Message if destUniqueId is still present
        if ($this->uniqueIdExists($destUniqueId)) {
            $srcUniqueId = \MiniPavi\MiniPaviCli::$uniqueId;
            $timestamp = time();
            $msg = [
                'srcUniqueId' => $srcUniqueId,
                'destUniqueId' => $destUniqueId,
                'oldMessage' => $oldMessage,
                'message' => $message,
                'timestamp' => $timestamp,
            ];

            $filename = $this->chat_dir . "msg-" . $timestamp . "-$srcUniqueId-$destUniqueId.json";
            file_put_contents($filename, json_encode($msg));
        }
    }

    public function readFirstMessage(): array
    {
        $destUniqueId = \MiniPavi\MiniPaviCli::$uniqueId;
        $messages = glob($this->chat_dir . "msg-*-*-$destUniqueId.json");
        if (count($messages) > 0) {
            $filename = $messages[0];
            $msg = json_decode(file_get_contents($filename), true);
            return $msg;
        }
        return [];
    }

    public function deleteMessage(array $msg): void
    {
        $timestamp = $msg['timestamp'];
        $srcUniqueId = $msg['srcUniqueId'];
        $destUniqueId = $msg['destUniqueId'];
        $filename = $this->chat_dir . "msg-$timestamp-$srcUniqueId-$destUniqueId.json";
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function answerToMessage(array $msg): void
    {
        $timestamp = $message['timestamp'];
        $destUniqueId = $message['destUniqueId'];
        $srcUniqueId = $message['srcUniqueId'];
        $filename = $this->chat_dir . "msg-$timestamp-$destUniqueId-$srcUniqueId.json";
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function uniqueIdExists(string $uniqueId): bool
    {
        return !empty($this->getConnecteById($uniqueId));
    }

    public function cleanMessages(): void
    {
        $messages = glob($this->chat_dir . "msg-*-*-*.json");
        foreach ($messages as $message) {
            preg_match('/msg-(\d+)-.*?\.json/', $message, $matches);
            $timestamp = $matches[1];
            trigger_error("message $message timestamp: $timestamp", E_USER_NOTICE);
            if (time() - $timestamp > \service\MESSAGES_TIMEOUT) {
                trigger_error("message $message delete!", E_USER_NOTICE);
                unlink($message);
            }
        }
    }

    protected function filterOutTimedOutConnectes(array $connectes): array
    {
        $activeConnectes = [];
        foreach ($connectes as $connecte) {
            if (time() - $connecte['timestamp'] < \service\CONNECTES_TIMEOUT) {
                $activeConnectes[] = $connecte;
            }
        }
        return $activeConnectes;
    }

    protected function getConnectesFilename(): string
    {
        return $this->chat_dir . "connectes.json";
    }
}
