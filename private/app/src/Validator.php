<?php

namespace SecureWebAppCoursework;

/**
 * Validator.php
 *
 * Class is used to validate the data that has been downloaded from EE M2M, as well as any user input
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
        var_dump($tainted_data);

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

    public function validateMessage($tainted_message)
    {
        $valid = false;

        $switches_valid = false;
        $fan_valid = false;
        $temp_valid = false;
        $key_valid = false;


        if (isset($tainted_message['Switches']['switch1']) && isset($tainted_message['Switches']['switch2'])
            && isset($tainted_message['Switches']['switch3']) && isset($tainted_message['Switches']['switch4']))
        {
            $number_valid_switches = 0;
            foreach ($tainted_message['Switches'] as $switch)
            {
                if ($switch == 'on' || $switch == 'off')
                {
                    $number_valid_switches += 1;
                }
            }
            if ($number_valid_switches == 4)
            {
                $switches_valid = true;
            }
        }

        if (isset($tainted_message['Fan']))
        {
            if ($tainted_message['Fan'] == 'forward' || $tainted_message['Fan'] == 'reverse')
            {
                $fan_valid = true;
            }
        }

        if (isset($tainted_message['Temperature']))
        {

            if (intval($tainted_message['Temperature']) >= 20 && intval($tainted_message['Temperature']) <= 50)
            {
                $temp_valid = true;
            }
        }

        if (isset($tainted_message['Keypad']))
        {

            if (intval($tainted_message['Keypad']) >= 0 && intval($tainted_message['Keypad']) <= 9)
            {
                $key_valid = true;
            }
        }

        if ($switches_valid && $fan_valid && $temp_valid && $key_valid)
        {
            $valid = true;
        }
        return $valid;
    }
}