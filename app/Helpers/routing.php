<?php

/**
 * Compare given route with current route and return output if they match.
 *
 * @param $route
 * @param string $output
 * @return bool|string
 */
function isActiveRoute($route, $parameterName = NULL, $parameterValue = NULL, $output = " active")
{
    if(Route::currentRouteName() == $route) {
        if($parameterName != NULL && $parameterValue != NULL && Route::input($parameterName)->id != $parameterValue->id)
            return NULL;

        return $output;
    }

    return NULL;
}