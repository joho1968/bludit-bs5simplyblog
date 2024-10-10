<?php
defined( 'BLUDIT' ) || die( 'That did not work as expected.' );
/*
 * bs5simplyblog theme for Bludit
 *
 * page.php (bs5simplyblog)
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

Theme::plugins('pageBegin');
?>
<section>
    <?php
    // Title
    if ( ! $url->notFound() ) {
        echo '<h1>' . $page->title() . '</h1>';
    }
    // Cover image
    if ( $page->coverImage() ) {
        echo '<div>';
        echo '<img class="bs5simplyblog-cover-img p-2 mx-auto" loading="lazy" src="' . $page->coverImage() . '" />';
        echo '</div>';
    }
    // Time
    if ( ! $url->notFound() ) {
        $show_date = true;
        if ( $page->type() === 'static' ) {
            $show_date = false;
        }
        if ( $show_date ) {
            // Fetch raw date(s) from DB instead of current @page object
            $db_page = $pages->getPageDB( $page->key() );
            if ( is_array( $db_page ) ) {
                if ( ! empty( $db_page['dateModified'] ) ) {
                    $date_modified = $db_page['dateModified'];
                } else {
                    $date_modified = '';
                }
                if ( ! empty( $db_page['date'] ) ) {
                    $date_raw = $db_page['date'];
                } else {
                    $date_raw = '';
                }
            } else {
                $date_modified = $page->dateModified();
                $date_raw = $page->dateRaw();
            }
            if ( ! empty( $date_raw ) ) {
                $post_time = date_create_immutable( $date_raw );
            } else {
                $post_time = false;
            }
            if ( ! $post_time && ! empty( $date_modified) ) {
                $post_time = date_create_immutable( $date_modified );
            }
            if ( $post_time ) {
                $time_now = date_create_immutable();
                if ( $time_now->format( 'Ymd' ) == $post_time->format( 'Ymd' ) ) {
                    $date_color = 'text-success';
                } else {
                    $date_color = 'text-body-tertiary';
                }
                echo '<div class="py-2 small text-start" title="' . $post_time->format( 'Y-m-d, H:i' ) . '">' .
                     '<span class="' . $date_color . ' small">' .
                     bs5simplyblog_getPostDate( $post_time ) .
                     '</span>' .
                     '<span class="text-body-tertiary">, ' . trim( $page->readingTime() ) . '</span>' .
                     '</div>';
            }
        }// $showDate
    }
    // Content
    echo '<div class="bs5simplyblog-page-content mt-3 mb-5">' .
         $page->content() .
         '</div>';
    // Check tags
    $post_tags = $page->tags( true );
    if ( ! empty( $post_tags ) ) {
        echo '<div class="bs5simplyblog-page-content-tags mb-3">';
        foreach( $post_tags as $tag_key => $tag_name ) {
            echo '<a class="badge text-decoration-none me-2 post-tag" href="' .
                 DOMAIN_TAGS . $tag_key . '">' .
                 $tag_name .
                 '</a>';
        }
        echo '</div>';
    }
    ?>
    <?php
    // Previous and Next post
    if ( ! $url->notFound() ) {
        if ( $page->type() == 'published' || $page->type() == 'static' ) {
            if ( ! $page->isChild() ) {
                // Check for children to display, directly below title
                $children = $page->children();
                if ( ! empty( $children ) ) {
                    if ( $page->type() == 'static' ) {
                        // Always sort children (ascending) of static pages by position
                        $children = bs5simplyblog_sortPages( $children, true, true );
                    } else {
                        // Otherwise, sort children (ascending) based on Bludit settings
                        $children = bs5simplyblog_sortPages( $children, ( $site->orderBy() == 'position' ), true );
                    }
                    echo '<div class="bs5simplyblog-children mt-5 mb-2 p-1">';
                    echo '<div class="small text-body-secondary">' . $L->get( 'see-also' ) . '</div>';
                    echo '<ul class="p-2 mb-0">';
                    foreach( $children as $child ) {
                        echo '<li class="small text-truncate">' .
                             '&raquo;&nbsp;<a class="nav-link d-inline" href="' . $child->permalink() . '" title="' . $child->title() . '">' .
                             $child->title() . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
            } else {
                $parent_key = $page->parent();
                if ( $parent_key !== false ) {
                    $parent_page = new Page( $parent_key );
                    echo '<div class="bs5simplyblog-children mt-5 mb-2 p-1">';
                    echo '<div class="small text-body-secondary">' . $L->get( 'see-also' ) . '</div>';
                    echo '<ul class="p-2 mb-0">';
                    echo '<li class="small text-truncate">' .
                         '&raquo;&nbsp;<a class="nav-link d-inline" href="' . $parent_page->permalink() . '" title="' . $parent_page->title() . '">' .
                         $parent_page->title() . '</a></li>';
                    $children = $parent_page->children();
                    if ( ! empty( $children ) ) {
                        if ( $page->type() == 'static' ) {
                            // Always sort children (ascending) of static pages by position
                            $children = bs5simplyblog_sortPages( $children, true, true );
                        } else {
                            // Otherwise, sort children (ascending) based on Bludit settings
                            $children = bs5simplyblog_sortPages( $children, ( $site->orderBy() == 'position' ), true );
                        }
                        foreach( $children as $child ) {
                            if ( $child->key() == $page->key() ) {
                                continue;
                            }
                            echo '<li class="small text-truncate">' .
                                 '&raquo;&nbsp;<a class="nav-link d-inline" href="' . $child->permalink() . '" title="' . $child->title() . '">' .
                                 $child->title() . '</a></li>';
                        }
                    }
                    echo '</ul>';
                    echo '</div>';
                }
            }
            if ( $page->type() == 'published' ) {
                // Get all published pages
                // $all_pages = $pages->getList( 1, -1 );
                $all_pages = [];
                foreach( $pages->db as $k => $v ) {
                    if ( $v['type'] == 'published' ) {
                        $all_pages[] = $k;
                    }
                }
                $position = 0;
                $previous_page = false;
                $next_page = false;
                $max_pages = count( $all_pages );
                // Loop through pages
                if ( $max_pages > 0 ) {
                    for ( $page_counter = 0; $page_counter < $max_pages; $page_counter++ ) {
                        if ( $page->key() == $all_pages[$page_counter] ) {
                            if ( $page_counter > 0 ) {
                                $check_page = $page_counter - 1;
                                while( $check_page >= 0 ) {
                                    // Skip child pages while going backward
                                    $one_page = new Page( $all_pages[$check_page] );
                                    if ( $one_page->isChild() ) {
                                        $check_page--;
                                        continue;
                                    } else {
                                        // We found non child previous page :-)
                                        $previous_page = $check_page;
                                        break;
                                    }
                                }// while
                            }
                            if ( ( $page_counter + 1 ) < ( $max_pages ) ) {
                                $check_page = $page_counter + 1;
                                while ( $check_page <= $max_pages ) {
                                    // Skip child pages while going forward
                                    $one_page = new Page( $all_pages[$check_page] );
                                    if ( $one_page->isChild() ) {
                                        $check_page++;
                                        continue;
                                    } else {
                                        // We found non child previous page :-)
                                        $next_page = $check_page;
                                        break;
                                    }
                                }// while
                            }
                            break;
                        }
                    }// for
                }

                if ( $previous_page !== false || $next_page !== false ) {
                    echo '<div class="bs5simplyblog-nextprevpage col-12 mt-5 py-3" aria-label="' . $L->get( 'post-navigation' ) . '">';
                    echo '<div class="row"><div class="col-12 mt-3 bs5simplyblog-nextprevpages">';
                    echo '<div class="row">';
                }
                if ( $previous_page !== false ) {
                    try {
                        $page_prev = new Page( $all_pages[$previous_page] );
                        if ( $page_prev->type() == 'published' ) {
                            echo '<div class="col-12 mt-1 text-nowrap text-truncate">' .
                                 '<span class="me-1" aria-hidden="true" role="img" title="' . $L->get( 'previous' ) . '">&#8592;</span>' .
                                 '<a class="nav-link d-inline" aria-label="' . $L->get( 'previous-post' ) . '" href="' . $page_prev->permalink( true ) . '" title="' . $page_prev->title() . '">' . $page_prev->title() . '</a></div>';
                        }
                    } catch( \Throwable $e ) {
                        error_log( basename( __FILE__ ) . ': Exception "' . $e->getMessage() . '"' );
                    }
                }
                if ( $next_page !== false ) {
                    try {
                        $page_next = new Page( $all_pages[$next_page] );
                        if ( $page_next->type() == 'published' ) {
                            echo '<div class="col-12 mt-1 text-nowrap text-truncate">' .
                                 '<span class="me-1" aria-hidden="true" role="img" title="' . $L->get( 'next' ) . '">&#8594;</span>' .
                                 '<a class="nav-link d-inline" aria-label="' . $L->get( 'next-post' ) . '" href="' . $page_next->permalink( true ) . '" title="' . $page_next->title() . '">' . $page_next->title() . '</a></div>';
                        }
                    } catch( \Throwable $e ) {
                        error_log( basename( __FILE__ ) . ': Exception "' . $e->getMessage() . '"' );
                    }
                }
                if ( $previous_page !== false || $next_page !== false ) {
                    echo '</div></div></div></div>';
                }
            }// ! static
        }
    }
    ?>
    <div id="showbacktotop" class="mt-5 text-start d-none">
        <button id="backtotop" role="button"
                class="btn btn-outline-secondary"
                title="<?php echo $L->get( 'back-to-top' ); ?>">&#9650;</button>
    </div>
</section>

<?php Theme::plugins('pageEnd'); ?>
