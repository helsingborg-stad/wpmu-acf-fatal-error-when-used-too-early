<?php

/**
 * Plugin Name: WPMU ACF Fatal Error When Used Too Early
 * Description: Throws a descriptive exception when an ACF field is accessed before WordPress has initialized.
 * Version: 0.2.0
 * Author:      Helsingborgs stad
 */

namespace WPMUAcfFatalErrorWhenUsedTooEarly;

use Exception;

/**
 * Detects Advanced Custom Fields values accessed before the init action.
 */
class WPMUAcfFatalErrorWhenUsedTooEarly
{
    /**
     * Registers the ACF validation hook.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('acf/get_invalid_field_value', [$this, 'throwExceptionForEarlyFieldAccess'], 10, 2);
    }

    /**
     * Throws an exception when get_field() is called before the init action.
     *
     * @return void
     *
     * @throws Exception When an ACF field value is accessed too early.
     */
    public function throwExceptionForEarlyFieldAccess()
    {
        if (did_action('init')) {
            return;
        }

        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $trace) {
            if (($trace['function'] ?? '') !== 'get_field') {
                continue;
            }

            $function = $trace['function'];
            $file     = $trace['file'] ?? '[internal function]';
            $line     = $trace['line'] ?? 'N/A';

            throw new Exception(
                "Invalid ACF field value detected: {$function} in {$file} on line {$line}."
            );
        }
    }
}

new WPMUAcfFatalErrorWhenUsedTooEarly();
