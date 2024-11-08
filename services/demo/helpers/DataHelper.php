<?php

/**
 * Data Helper to provide data for the "dynamic" part of the demo
 *
 * We are using static data to simplify the demo.
 */

namespace service\helpers;

// The "data source"
require_once __DIR__ . '/../datas/data.php';

class DataHelper
{
    private const MONTHS_NAMES = [
        'janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin',
        'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'decembre',
    ];

    public static function getSearchOptions(): array
    {
        return ['author' => 'Auteur', 'date' => 'Date'];
    }

    public static function getAllArticlesIds(): array
    {
        $ids = [];
        foreach (DEMO_DATA['articles'] as $id => $article) {
            $ids[] = $id;
        }
        return $ids;
    }

    public static function getCriterias(string $criteria_type): array
    {
        if ($criteria_type === 'author') {
            return DEMO_DATA['authors'];
        } elseif ($criteria_type === 'date') {
            // Find the Dates
            $dates = [];
            foreach (DEMO_DATA['articles'] as $article) {
                $dates[$article['date']] = self::dateToFrench($article['date']);
            }
            return $dates;
        }
        return [];
    }

    public static function getArticlesIdsByCriteria(string $criteria_type, string $criteria): array
    {
        $field_name = ($criteria_type === 'author') ? 'author_id' : 'date';
        $ids = [];
        foreach (DEMO_DATA['articles'] as $article_id => $article) {
            if ($criteria == $article[$field_name]) {
                $ids[] = $article_id;
            }
        }
        return $ids;
    }

    public static function getAuthorNameById(int $id): string
    {
        return DEMO_DATA['authors'][$id];
    }

    public static function getArticleById(int $id): array
    {
        return DEMO_DATA['articles'][$id];
    }

    public static function dateToFrench(string $date): string
    {
        $day = (int) substr($date, 8, 2);
        if ($day == 1) {
            $day = '1er';
        }
        $month = substr($date, 5, 2);
        $year = substr($date, 0, 4);
        return $day . ' ' . self::MONTHS_NAMES[$month - 1] . ' ' . $year;
    }
}
