<?php

class ActivityLog extends Model
{
    protected static string $table = 'activity_log';

    public static function record(
        int     $userId,
        string  $action,
        ?string $referenceType = null,
        ?int    $referenceId   = null,
        ?string $note          = null
    ): void {
        $stmt = static::db()->prepare('
            INSERT INTO activity_log (user_id, action, reference_type, reference_id, ip_address, note)
            VALUES (:user_id, :action, :reference_type, :reference_id, :ip_address, :note)
        ');
        $stmt->execute([
            'user_id'        => $userId,
            'action'         => $action,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
            'ip_address'     => $_SERVER['REMOTE_ADDR'] ?? null,
            'note'           => $note,
        ]);
    }
}