<?php

namespace App\Models;

use App\Core\Model;
use App\Config\Database;

class Semester extends Model
{
    protected static string $table = 'semesters';

    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM semesters ORDER BY sort_order");
    }

    public static function allWithCounts(): array
    {
        return Database::fetchAll(
            "SELECT s.*, 
                    (SELECT COUNT(*) FROM courses c WHERE c.semester_id = s.id) as courses_count,
                    (SELECT COUNT(*) FROM files f JOIN courses c ON c.id = f.course_id WHERE c.semester_id = s.id) as files_count
             FROM semesters s 
             ORDER BY s.sort_order ASC, s.id ASC"
        );
    }

    private static ?array $cacheActive = null;

    public static function getActive(): array
    {
        if (self::$cacheActive !== null) {
            return self::$cacheActive;
        }
        self::$cacheActive = Database::fetchAll("SELECT * FROM semesters ORDER BY sort_order");
        return self::$cacheActive;
    }

    public static function getActiveSorted(): array
    {
        return static::getActive();
    }
}

