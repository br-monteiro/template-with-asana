<?php
namespace App\Helpers;

class AsanaTask
{

    private static $task;

    public static function setProperties(\stdClass $task)
    {
        self::$task = $task;
    }

    public static function getDescription(): string
    {
        return self::$task->notes ?? '';
    }

    public static function getTags(): array
    {
        $value = self::$task->tags ?? [];
        return self::extractName($value);
    }

    public static function getFollowers(): array
    {
        $value = self::$task->followers ?? [];
        return self::extractName($value);
    }

    private static function extractName(array $arrTarget): array
    {
        $arrResult = [];
        foreach ($arrTarget as $value) {
            $arrResult[] = $value->name;
        }
        asort($arrResult);
        return $arrResult;
    }
}
