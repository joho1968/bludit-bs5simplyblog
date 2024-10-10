<?php
defined( 'BLUDIT' ) || die( 'That did not work as expected.' );
/*
 * bs5simplyblog theme for Bludit
 *
 * home.php (bs5simplyblog)
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
?>

<section>
    <div class="row">
        <div class="col-12 ms-0 ps-0 ps-lg-3">
            <?php
            // Make sure there's content
            if ( empty( $content ) ) {
                echo '<div class="text-center">' .
                     '<p class="h2 text-bg-danger py-3 px-5 rounded-2 d-inline-block">' .
                     $L->get( 'no-pages-found' ) .
                     '</p></div>';
            }
            ?>

            <?php
            $page_not_found = $site->pageNotFound();
            // Show pages
            echo '<div>';
            $time_now = date_create_immutable();
            $time_now_string = $time_now->format( 'Ymd' );

            // Sort content properly on search
            if ( ! empty( $WHERE_AM_I ) && ! empty( $content) && count( $content) > 1 ) {
                if ( $WHERE_AM_I == 'search' ) {
                    $sortable = [];
                    $by_position = ( $site->orderBy() == 'position' );
                    foreach( $content as $k => $v ) {
                        if ( $by_position) {
                            $sortable[$k] = $v->position();
                        } else {
                            $sortable[$k] = $v->dateRaw();
                        }
                    }// foreach
                    // Sort according to Bludit settings
                    if ( $by_position ) {
                        asort( $sortable, SORT_NUMERIC );
                    } else {
                        arsort( $sortable, SORT_STRING );
                    }
                    // Generate new content
                    $new_content = [];
                    foreach( $sortable as $k => $v ) {
                        $new_content[$k] = $content[$k];
                    }
                    $content = $new_content;
                }
            }// $WHERE_AM_I

            foreach( $content as $post ) {
                if ( $post->isChild() ) {
                    if ( ! empty( $WHERE_AM_I ) && $WHERE_AM_I == 'home' ) {
                        // Skip sub pages on home
                        continue;
                    }
                } elseif ( $post->key() == $page_not_found ) {
                    // Skip our "Page not found page" in this context
                    continue;
                }
                Theme::plugins('pageBegin');
                // item start
                echo '<div class="mb-5 bg-body p-3">';
                echo '<div class="h1 text-truncate">' .
                     '<a class="text-decoration-none" href="' . $post->permalink() . '" title="' . $post->title() . '">' .
                     $post->title() . '</a>' .
                     '</div>';
                // Post time
                // - fetch raw date(s) from DB instead of current $page object
                $db_page = $pages->getPageDB( $post->key() );
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
                    $date_modified = $post->dateModified();
                    $date_raw = $post->dateRaw();
                }
                if ( ! empty( $date_raw ) ) {
                    $post_time = date_create_immutable( $date_raw );
                } else {
                    $post_time = false;
                }
                if ( ! $post_time ) {
                    $post_time = date_create_immutable( $date_modified );
                }
                if ( $post_time !== false ) {
                    $time_now = date_create_immutable();
                    if ( $time_now_string == $post_time->format( 'Ymd' ) ) {
                        $date_color = 'text-success';
                    } else {
                        $date_color = 'text-body-tertiary';
                    }
                    echo '<div class="small" title="' . $post_time->format( 'Y-m-d, H:i' ) . '">';
                    echo '<span class="small ' . $date_color . '">' .
                         bs5simplyblog_getPostDate( $post_time ) .
                         '</span>' .
                         '<span class="text-body-tertiary">, ' . trim( $post->readingTime() ) . '</span>';
                    echo '</div>';
                }
                // Content
                echo '<div class="mt-2 mb-2">';
                if ( ! empty( $WHERE_AM_I ) ) {
                    switch( $WHERE_AM_I ) {
                        case 'page':
                        case 'home':
                            // Only show "full post" on 'page' and 'home'
                            echo $post->contentBreak();
                            if ( $post->readMore() ) {
                                echo '<a role="button" class="btn btn-outline-success btn-sm read-more ms-0" href="' .
                                     $post->permalink() . '" role="button">' . $L->get( 'read-more' ) .
                                     '</a>';
                            }
                            break;
                        case 'search':
                            break;
                        case 'tag':
                            break;
                        case 'category':
                            break;
                    }
                }// ! empty( $WHERE_AM_I )
                echo '</div>';
                // item end
                echo '</div>';
                Theme::plugins('pageEnd');
            }// foreach

            echo '</div>';

            //Pagination
            if ( Paginator::numberOfPages() > 1 ) {
                echo '<nav aria-label="' . $L->get( 'page-navigation') . '">';
                echo '<ul class="pagination">';
                if ( Paginator::showPrev() ) {
                    echo '<li class="page-item mr-2">' .
                         '<a class="page-link" href="' . Paginator::previousPageUrl() . '" tabindex="-1" title="' . $L->get( 'previous' ) . '" aria-label="' . $L->get( 'previous' ) . '" . >' .
                         '<span aria-hidden="true">&laquo;</span>' .
                         '</a>'.
                         '</li>';
                }
                echo '<li class="page-item mr-2' . ( Paginator::currentPage() == 1 ? ' disabled':'' ) . '">' .
                     '<a class="page-link" href="' . Theme::siteUrl() . '" title="' . $L->get( 'home') . '" aria-label="' . $L->get( 'home' ) . '"><span aria-hidden="true">&#x1F3E0;</span></a>' .
                     '</li>';
                if ( Paginator::showNext() ) {
                    echo '<li class="page-item mr-2">' .
                         '<a class="page-link" href="' . Paginator::nextPageUrl() . '" tabindex="-1" title="' . $L->get( 'next' ) . '" aria-label="' . $L->get( 'next' ) . '">' .
                         '<span aria-hidden="true">&raquo;</span>' .
                         '</a>' .
                         '</li>';
                }
                echo '</ul></nav>';
            }
            ?>
        </div>
    </div>
</section>
