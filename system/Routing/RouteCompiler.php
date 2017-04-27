<?php

namespace Mini\Routing;

use Mini\Support\Arr;

use DomainException;
use LogicException;


class RouteCompiler
{
    /**
     * The maximum supported length of a PCRE subpattern name
     * http://pcre.org/current/doc/html/pcre2pattern.html#SEC16.
     *
     * @internal
     */
    const VARIABLE_MAXIMUM_LENGTH = 32;


    /**
     * Compile an URI pattern to a valid regex and return it.
     *
     * @param  string   $pattern
     * @param  array    $parameters
     * @return string
     *
     * @throw \LogicException
     */
    public static function compile($pattern, $parameters = array())
    {
        $pattern = '/' .ltrim($pattern, '/');

        //
        $params = array();

        $optionals = 0;

        $result = preg_replace_callback('#/{(\w+)(?:(\?))?}#i', function ($matches) use ($pattern, $parameters, &$params, &$optionals)
        {
            $param = $matches[1];

            // Check if the parameter is unique.
            if (in_array($param, $params)) {
                $message = "Route pattern [$pattern] cannot reference parameter name [$param] more than once.";

                throw new LogicException($message);
            }

            // Check the parameter length.
            $maxLength = self::VARIABLE_MAXIMUM_LENGTH;

            if (strlen($param) > $maxLength) {
                $message = "Parameter name [$param] cannot be longer than $maxLength characters in route pattern [$pattern].";

                throw new DomainException($message);
            }

            // Check if the parameter is optional.
            if (isset($matches[2]) && ($matches[2] === '?')) {
                $prefix = '(?:';

                $optionals++;
            } else if ($optionals > 0) {
                $message = "Route pattern [$pattern] cannot reference parameter [$param] after one or more optionals.";

                throw new LogicException($message);
            } else {
                $prefix = '';
            }

            array_push($params, $param);

            //
            $regex = Arr::get($parameters, $param, '[^/]+');

            return sprintf('%s/(?P<%s>%s)', $prefix, $param, $regex);

        }, $pattern);

        if ($optionals > 0) {
            $result .= str_repeat(')?', $optionals);
        }

        return $result;
    }
}
