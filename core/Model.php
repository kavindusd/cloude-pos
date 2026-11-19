<?php

class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    // We'll build out query methods in a later step.
    // For now, expose the PDO instance for raw queries.

    protected static function db(): PDO
    {
        return Database::getInstance()->getConnection();
    }
}
