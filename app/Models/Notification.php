<?php

class Notification extends Model
{
    protected static string $table = 'notifications';

    // Send a notification to a specific user
    public static function send(
        int     $toUserId,
        string  $type,
        string  $message,
        ?int    $fromUserId    = null,
        ?string $referenceType = null,
        ?int    $referenceId   = null
    ): void {
        $stmt = static::db()->prepare('
            INSERT INTO notifications (to_user_id, from_user_id, type, message, reference_type, reference_id)
            VALUES (:to, :from, :type, :message, :ref_type, :ref_id)
        ');
        $stmt->execute([
            'to'       => $toUserId,
            'from'     => $fromUserId,
            'type'     => $type,
            'message'  => $message,
            'ref_type' => $referenceType,
            'ref_id'   => $referenceId,
        ]);
    }

    // Notify all managers, admins and super admins (used by stock keeper)
    public static function notifyManagement(
        string $type,
        string $message,
        ?int   $fromUserId    = null,
        ?string $referenceType = null,
        ?int   $referenceId   = null
    ): void {
        $stmt = static::db()->prepare("
            SELECT id FROM users
            WHERE role IN ('super_admin','admin','manager') AND active = 1
        ");
        $stmt->execute();
        $recipients = $stmt->fetchAll();

        foreach ($recipients as $recipient) {
            static::send(
                $recipient['id'],
                $type,
                $message,
                $fromUserId,
                $referenceType,
                $referenceId
            );
        }
    }

    // Get unread notifications for a user
    public static function unreadFor(int $userId): array
    {
        $stmt = static::db()->prepare('
            SELECT n.*, u.name AS from_name
            FROM notifications n
            LEFT JOIN users u ON u.id = n.from_user_id
            WHERE n.to_user_id = ? AND n.is_read = 0
            ORDER BY n.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Count unread
    public static function unreadCount(int $userId): int
    {
        $stmt = static::db()->prepare('
            SELECT COUNT(*) FROM notifications WHERE to_user_id = ? AND is_read = 0
        ');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    // Mark all as read for a user
    public static function markAllRead(int $userId): void
    {
        $stmt = static::db()->prepare('UPDATE notifications SET is_read = 1 WHERE to_user_id = ?');
        $stmt->execute([$userId]);
    }
}