<?php
defined( 'BLUDIT' ) || die( 'That did not work as expected.' );
/*
 * bs5simplyblog theme for Bludit
 *
 * functions.inc.php (bs5simplyblog)
 * Copyright 2024 Joaquim Homrighausen; all rights reserved.
 * Development sponsored by WebbPlatsen i Sverige AB, www.webbplatsen.se
 *
 * This file is part of bs5simplyblog. bs5simplyblog is free software.
 *
 * bs5simplyblog is free software: you may redistribute it and/or modify it
 * under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE v3 as published by
 * the Free Software Foundation.
 *
 * bs5simplyblog is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU AFFERO GENERAL PUBLIC LICENSE
 * v3 for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE v3
 * along with the bs5simplyblog package. If not, write to:
 *  The Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor
 *  Boston, MA  02110-1301, USA.
 */

$bs5simplyblog_locale = '';
$bs5simplyblog_timezone = '';
$bs5simplyblog_datefmt = null;

function bs5simplyblog_sortPages( $pages, $sort_on_position = true, $sort_ascending = true ) {
    if ( is_array( $pages ) && ! empty( $pages ) && count( $pages ) > 1 ) {
        $sortable = [];
        if ( $sort_on_position ) {
            foreach( $pages as $k => $v ) {
                $sortable[] = $v->position();
            }
        } else {
            foreach( $pages as $k => $v ) {
                $sortable[] = $v->dateRaw();
            }
        }
        if ( $sort_ascending ) {
            if ( $sort_on_position ) {
                asort( $sortable, SORT_NUMERIC );
            } else {
                asort( $sortable, SORT_STRING );
            }
        } else {
            if ( $sort_on_position ) {
                arsort( $sortable, SORT_NUMERIC );
            } else {
                arsort( $sortable, SORT_STRING );
            }
        }
        $new_pages = [];
        foreach( $sortable as $k => $v ) {
            $new_pages[] = $pages[$k];
        }
    }
    return( $pages );
}
function bs5simplyblog_getLocale() {
    global $site;

    if ( $site->locale() ) {
        $locales = explode( ',', $site->locale() );
        $longest_locale = '';
        foreach( $locales as $single_locale ) {
            if ( strlen( $single_locale ) > strlen( $longest_locale ) ) {
                $longest_locale = $single_locale;
            }
        }
        $our_locale = trim( $longest_locale );
        if ( empty( $our_locale ) ) {
            $our_locale = 'en_US';
        }
    } else {
        $our_locale = 'en_US';
    }
    return( $our_locale );
}

function bs5simplyblog_getPostDate( $post_time, $date_format = false ) {
    global $site;
    global $bs5simplyblog_locale;
    global $bs5simplyblog_timezone;
    global $bs5simplyblog_datefmt;
    global $themePlugin;

    if ( empty( $bs5simplyblog_locale ) ) {
        $bs5simplyblog_locale = bs5simplyblog_getLocale();
    }
    if ( empty( $bs5simplyblog_timezone ) ) {
        if ( $site->timezone() ) {
            $bs5simplyblog_timezone = $site->timezone();
        } else {
            $bs5simplyblog_timezone = 'Europe/Berlin';
        }
    }

    $return_date = '';
    if ( class_exists( 'Error' ) && class_exists( 'IntlDateFormatter' ) ) {
        if ( $bs5simplyblog_datefmt === null ) {
            try {
                if ( $date_format === false ) {
                    $date_format = IntlDateFormatter::LONG;
                }
                if ( $themePlugin ) {
                    switch( $themePlugin->dateFormat() ) {
                        case 'long':
                            $date_format = IntlDateFormatter::LONG;
                            break;
                        case 'medium':
                            $date_format = IntlDateFormatter::MEDIUM;
                            break;
                        case 'short':
                            $date_format = IntlDateFormatter::SHORT;
                            break;
                        case 'full':
                            $date_format = IntlDateFormatter::FULL;
                            break;
                    }// switch
                }
                $bs5simplyblog_datefmt = new IntlDateFormatter(
                    $bs5simplyblog_locale,
                    $date_format,
                    IntlDateFormatter::NONE,
                    $bs5simplyblog_timezone );
                if ( ! is_object( $bs5simplyblog_datefmt ) ) {
                    $bs5simplyblog_datefmt = false;
                }
            } catch( \Error $e ) {
                $bs5simplyblog_datefmt = false;
            }
        }
        if ( is_object( $bs5simplyblog_datefmt ) ) {
            try {
                $return_date = $bs5simplyblog_datefmt->format( $post_time );
                if ( $return_date === false ) {
                    $bs5simplyblog_datefmt = false;
                }
            } catch( \Error $e ) {
                $bs5simplyblog_datefmt = false;
                $return_date = '';
            }
        }
    }
    if ( empty( $return_date ) ) {
        $return_date = $post_time->format( $site->db['dateFormat'] );
    }
    return( $return_date );
}
