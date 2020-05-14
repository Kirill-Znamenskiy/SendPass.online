<?php

namespace App\Custom;


class CustomUrlGenerator extends \Illuminate\Routing\UrlGenerator
{

    /**
     * @inheritDoc
     */
    public function format($root, $path, $route = null)
    {
        $path = '/'.trim($path, '/');

        if ($this->formatHostUsing) {
            $root = call_user_func($this->formatHostUsing, $root, $route);
        }

        if ($this->formatPathUsing) {
            $path = call_user_func($this->formatPathUsing, $path, $route);
        }

        //return trim($root.$path, '/');
        return $root.$path;
    }
}
