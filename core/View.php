<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): string
    {
        $file = static::viewPath($view);

        if (!file_exists($file)) {
            throw new \RuntimeException("View '{$view}' not found at path: {$file}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return ob_get_clean();
    }

    protected static function viewPath(string $view): string
    {
        $view = str_replace('.', DIRECTORY_SEPARATOR, $view);

        $views = ConfigManager::get('paths.views');

        return rtrim($views, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $view . '.php';
    }
}
