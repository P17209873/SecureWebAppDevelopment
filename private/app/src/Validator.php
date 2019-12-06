<?php

namespace secureWebAppCoursework;

/**
 * Validator.php
 *
 * Class is used to validate the data that has been downloaded from EE M2M
 */
class Validator
{
    public function __construct() { }

    public function __destruct() { }

    /**
     * Validates details - compares to DETAIL_TYPES array in settings.php
     */
    public function validateDetailType($type_to_check)
    {
        $checked_detail_type = false;
        $detail_types = DETAIL_TYPES;

        if (in_array($type_to_check, $detail_types) === true)
        {
            $checked_detail_type = $type_to_check;
        }

        return $checked_detail_type;
    }

    /**
     * Filters and sanitizes any string data
     */
    public function validateDownloadedData($tainted_data)
    {
        $validated_string_data = '';

        $validated_string_data = filter_var($tainted_data, FILTER_SANITIZE_STRING);

        return $validated_string_data;
    }

    public function sanitiseString(string $string_to_sanitise): string
    {
        $sanitised_string = false;

        if (!empty($string_to_sanitise))
        {
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $sanitised_string;
    }
}