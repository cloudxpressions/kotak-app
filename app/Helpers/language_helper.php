<?php

if (!function_exists('current_language')) {
    function current_language()
    {
        $app = \App\Core\App::getInstance();
        return $app->get('language')->getCurrentLanguage();
    }
}

if (!function_exists('translate')) {
    function translate($entityType, $entityId, $fields = ['title', 'content'])
    {
        $app = \App\Core\App::getInstance();
        return $app->get('language')->translate($entityType, $entityId, $fields);
    }
}

if (!function_exists('get_all_languages')) {
    function get_all_languages()
    {
        $app = \App\Core\App::getInstance();
        return $app->get('language')->getAllLanguages();
    }
}