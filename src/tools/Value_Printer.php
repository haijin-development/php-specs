<?php

namespace Haijin\Specs;

class Value_Printer {
    static function print_string_of($value)
    {
        if( is_string( $value ) ) {
            return "\"" . (string) $value . "\"";
        }

        if( $value === null ) {
            return "null";
        }

        if( $value === true ) {
            return "true";
        }

        if( $value === false ) {
            return "false";
        }

        if( is_object( $value ) ) {
            return "a " . get_class( $value );
        }

        if( is_array($value) ) {
            $print_strings = [];

            foreach( $value as $key => $value) {
                $print_string =
                    self::print_string_of( $key ) . " => " . self::print_string_of( $value );

                $print_strings[] = $print_string;
                
            }

            return "[" . join( ", ", $print_strings ) . "]";
        }

        return (string) $value;
    }

}
