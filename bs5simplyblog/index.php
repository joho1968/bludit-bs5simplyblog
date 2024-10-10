<?php
defined( 'BLUDIT' ) || die( 'That did not work as expected.' );
/*
 * bs5simplyblog theme for Bludit
 *
 * index.php (bs5simplyblog)
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
echo '<!doctype html>' . "\n";
echo '<html lang="' . $language->currentLanguageShortVersion() . '" data-bs-theme="auto">' . "\n";
$bsThemeAuto = true;
?>
<head>
<meta charset="<?php if ( defined('CHARSET') && ! empty( CHARSET ) ) { echo CHARSET; } else { echo 'UTF-8'; } ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
    if ( $WHERE_AM_I == 'page' ) {
        $page_title = $page->title();
        if ( empty( $page_title ) ) {
            $page_title = $site->title();
        } else {
            $page_title .= ' | ' . $site->title();
        }
    } else {
        $page_title = $site->title();
    }
    echo '<title>' . $page_title . '</title>' . "\n";
    if ( $WHERE_AM_I == 'page' ) {
        if ( $page && $page->description() && ! empty( $page->description() ) ) {
            $page_description = $page->description();
        } else {
            $page_description = $site->description();
        }
    } else {
        $page_description = $site->description();
    }
    echo '<meta name="description" content="' . $page_description . '">' . "\n";
    echo Theme::favicon( 'res/img/favicon.png' );
    echo '<link rel="stylesheet" type="text/css" href="' . DOMAIN_THEME . 'res/css/bootstrap.min.css">' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="' . DOMAIN_THEME . 'css/bs5simplyblog.css">' . "\n";
    Theme::plugins( 'siteHead' );
?>
<?php
    if ( $bsThemeAuto ) {
?>
    <script>
        (() => {
            'use strict';
            const colorMode = window.matchMedia("(prefers-color-scheme: dark)").matches ?
                "dark" :
                "light";
            document.querySelector("html").setAttribute("data-bs-theme", colorMode);
        })();
    </script>
<?php
    }
?>
</head>

<?php
require_once( THEME_DIR_PHP . 'functions.inc.php' );

// Try to sort strings properly if we can
if ( class_exists( 'Collator' ) ) {
    $site_locale = bs5simplyblog_getLocale();
    $collator = new Collator( $site_locale );
    if ( is_object( $collator ) ) {
        $locale = $collator->getLocale( Locale::VALID_LOCALE );
        if ( ! empty( $locale ) && $locale != 'root' && $locale != $site_locale ) {
            if ( function_exists( 'locale_set_default' ) ) {
                locale_set_default( $locale );
            }
        }
    }
} else {
    $collator = false;
}
function sortMenuChildren( $a, $b ) {
    global $collator;
    if ( $a->position() === $b->position() ) {
        if ( is_object( $collator) ) {
            $cmp = $collator->compare( $a->title(), $b->title() );
            if ( $cmp === false ) {
                $cmp = strcmp( $a->title(), $b->title() );
            }
            return( $cmp );
        }
        return( strcmp( $a->title(), $b->title() ) );
    }
    return ( $a->position() - $b->position() );
}

// Build static page content navigation
$menu_html = '';
$is_first = true;
$page_not_found = $site->pageNotFound();
// $doc_pages = buildStaticPages();
$bs5simplyblog_navigation = array();
$bs5simplyblog_navcount = 0;
$bs5simplyblog_nav_active = -1;

if ( ! empty( $staticContent ) ) {
    foreach( $staticContent as $sp ) {
        if ( $sp->key() == $page_not_found ) {
            continue;
        }
        /*
        if ( ! $sp->hasChildren() ) {
            continue;
        }
        */
        if ( ! $sp->isChild() ) {
            if ( $is_first ) {
                $menu_html .= '<nav>';
                $is_first = false;
            }
            $item_title = $sp->title();
            if ( empty( $item_title ) ) {
                $item_title = $L->get( 'no-title' );
            }
            if ( $sp->key() == $url->slug() ) {
                $is_active = ' active-nav';
                $is_active_aria = ' aria-current="page"';
            } else {
                $is_active = '';
                $is_active_aria = '';
            }
            $menu_html .= '<div class="mb-2 ms-2 bs5simplyblog-menu-item">' .
                          '<a class="' . $is_active . '" href="' . $sp->permaLink() . '">' . $item_title . '</a>' .
                          '</div>';
            $is_first_child = true;

            // Sort children based on position and title (if position is same)
            $children = array();
            foreach( $sp->children() as $child ) {
                $children[] = $child;
            }
            usort( $children, 'sortMenuChildren' );
            $child_count = 0;
            foreach( $children as $child ) {
                if ( $is_first_child ) {
                    $menu_html .= '<ul class="flex-column bs5simplyblog-menu">';
                    $is_first_child = false;
                }
                if ( $child->key() == $url->slug() ) {
                    $is_active = ' active-nav';
                    $is_active_aria = ' aria-current="page"';
                    $bs5simplyblog_navigation[$bs5simplyblog_navcount] = array( 'url' => $child->permaLink(), 'active' => true );
                    $bs5simplyblog_nav_active = $bs5simplyblog_navcount;
                } else {
                    $is_active = '';
                    $is_active_aria = '';
                    $bs5simplyblog_navigation[$bs5simplyblog_navcount] = array( 'url' => $child->permaLink() );
                }
                $menu_html .= '<li class="ms-x bs5simplyblog-menu-item">';
                $menu_html .= '<a' . $is_active_aria . ' class="' . $is_active . '" title="' . $child->title() . '" href="' . $child->permalink() . '">' . $child->title() . '</a>';
                $menu_html .= '</li>'. "\n";
                $bs5simplyblog_navcount++;
            }// foreach
            if ( ! $is_first_child ) {
                $menu_html .= '</ul>';
            }
        }
    }// foreach
    if ( ! $is_first) {
        $menu_html .= '</nav>' . "\n";
    }
    // Bludit sidebar
    if ( ! ob_start() ) {
        error_log( basename( __FILE__ ) . ': ob_start() failed' );
    }
    Theme::plugins('siteSidebar');
    $site_sidebar = ob_get_clean();
    if ( ! empty( $site_sidebar ) ) {
        $menu_html .= '<div class="bs5offcanvas-section">' . $site_sidebar . '</div>';
    }
    // Social networks
    if ( ! empty( Theme::socialNetworks() ) ) {
        $menu_html .= '<div class="ms-2 mb-2 bs5offcanvas-section">' .
                      '<div class="h5">' . $L->get( 'Social media' ) . '</div>' .
                      '<div class="d-inline-flex flex-row ms-3 flex-wrap">';
        foreach( Theme::socialNetworks() as $key => $label ) {
            $menu_html .= '<div class="p-1">'.
                          '<a class="text-decoration-none" href="' . $site->{$key}() . '" target="_blank">' .
                          '<img class="img-thumbnail bg-light bs5socialmedia-icon" loading="lazy" src="' . DOMAIN_THEME . 'res/img/' . $key . '.png" alt="' . $label . '" title="' . $site->{$key}() . '" />' .
                          '</a>' .
                          '</div>';
        }
        $menu_html .= '</div></div>';
    }
    // RSS
    if ( Theme::rssUrl() ) {
        $menu_html .= '<div class="ms-2 bs5offcanvas-section"><div class="h5">RSS</div>';
        $menu_html .= '<a class="text-decoration-none ms-3" href="' .
                      Theme::rssUrl() . '" target="_blank" role="button" title="' . Theme::rssUrl() . '">' .
                      '<img class="img-thumbnail bs5socialmedia-icon" loading="lazy" src="' . DOMAIN_THEME . 'res/img/rss.png' . '" alt="RSS" />' .
                      '</a>';
        $menu_html .= '</div>';
    }
    // Site footer
    $footer = $site->footer();
    if ( ! empty( $footer ) ) {
        $menu_html .= '<div class="p-2 text-center text-secondary bs5simplyblog-footer">' . $footer . '</div>';
    }
    $hideCredits = false;
    if ( $themePlugin && $themePlugin->hideCredits() ) {
        $hideCredits = true;
    }
    if ( ! $hideCredits ) {
        $menu_html .= '<div class="p-2 mt-5 text-center text-secondary opacity-50" style="font-size: 0.75em !important" title="Powered by BS5 Simply Blog and Bludit">' .
                      'Powered by BS5 Simply Blog and Bludit' .
                      '</div>';
    }
}
?>


<body class="bg-body">
    <script>
        (() => {
            'use strict';

            var scrollTimer = null;
            var backToTop = null;

            // Set theme to the user's preferred color scheme
            function updateBootstrapTheme() {
                const colorMode = window.matchMedia("(prefers-color-scheme: dark)").matches ?
                    "dark" :
                    "light";
                document.querySelector("html").setAttribute("data-bs-theme", colorMode);
            }
            function documentSetup() {
                // Update theme when the preferred scheme changes
                <?php
                if ( $bsThemeAuto ) {
                ?>
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateBootstrapTheme);
                <?php
                }
                ?>
                // Figure out light/dark mode
                let colorMode = window.matchMedia("(prefers-color-scheme: dark)").matches ?
                    "dark" :
                    "light";
                const offCanvas = document.getElementById('offcanvasMenu');
                offCanvas.addEventListener('shown.bs.offcanvas', event => {
                    document.getElementsByClassName("offcanvas-scroll")[0].scrollIntoView({ behavior: "instant", block: "center", inline: "nearest" });
                });
                const contentDiv = document.getElementById('content-area-inner');
                backToTop = document.getElementById('showbacktotop');
                <?php
                // HighlightJS
                ?>
                if (contentDiv) {
                  hljs.highlightAll();
                  const blocks = contentDiv.querySelectorAll('pre code.hljs');
                  Array.prototype.forEach.call(blocks, function(block) {
                    var language = block.result.language;
                    block.insertAdjacentHTML('afterbegin', `<label>${language}</label>`);
                  });
                }
                <?php
                if ( $WHERE_AM_I == 'page' ) {
                ?>
                let btotop = document.getElementById('backtotop');
                if (btotop) {
                  btotop.addEventListener('click', function(ev) {
                    ev.preventDefault();
                    window.scroll({
                      top: 0,
                      left: 0,
                      behavior: 'instant',
                    });
                  });
                  if (window.scrollY>0) {
                    if (backToTop) {
                        backToTop.classList.remove('d-none');
                    }
                  }
                  window.addEventListener('scroll', function(e) {
                    if (scrollTimer != null) {
                      window.clearTimeout(scrollTimer);
                    }
                    scrollTimer = setTimeout(() => {
                      if (this.scrollY>0) {
                          if (backToTop) {
                              backToTop.classList.remove('d-none');
                          }
                      } else {
                          if (backToTop) {
                              backToTop.classList.add('d-none');
                          }
                      }
                    }, 1000);
                  });
                }
                <?php
                }// page
                ?>
                // Some more BS initialization
                const popoverTriggerList = document.querySelectorAll('[data-bs-toggle=\"popover\"]');
                const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            }
            if (document.readyState === 'complete' ||
                    (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
                documentSetup();
            } else {
                document.addEventListener('DOMContentLoaded', documentSetup);
            }
        })();
    </script>

    <div class="container">
        <div class="row sticky-top">
            <div class="col-12 bg-body border-bottom border-secondary-subtle">
                <div class="row">
                    <div class="navbar col-12 m-0 mt-1 p-0 px-1" role="navigation">
                        <h1 class="site-header m-0 p-0 w-75 text-truncate">
                            <a class="navbar-brand fs-3 align-middle" href="<?php echo $site->url(); ?>" title="<?php echo $site->title(); ?>">
                            <?php echo $site->title(); ?>
                        </a></h1>
                        <button class="navbar-toggler d-md-none ms-2 me-1 px-2 pt-1 pb-2"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu"
                                aria-expanded="false"
                                aria-controls="offcanvasMenu"
                                aria-label="<?php echo $L->get( 'Navigation' ); ?>">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
                <?php
                $site_slogan = $site->slogan();
                if ( ! empty( $site_slogan ) ) {
                ?>
                <div class="row">
                    <div class="col-12 m-0 p-0 px-1">
                    <div class="text-body-tertiary site-slogan"><?= $site_slogan ?></div>
                    </div>
                </div>
                <?php
                }
                ?>

                <?php
                global $categories;

                $first_category = true;
                if ( ! empty( $categories->db ) ) {
                    foreach( $categories->db as $k => $v ) {
                        if ( empty( $v['name'] ) ) {
                            // No "name" or title for this category, skip
                            continue;
                        }
                        if ( empty( $v['list'] ) ) {
                            // No posts in this category, skip
                            continue;
                        }
                        if ( $first_category ) {
                            echo '<div class="row">';
                            echo '<div class="col-12 d-flex flex-wrap w-75 justify-content-center mx-auto">';
                            $first_category = false;
                        }
                        echo '<div class="m-2">';
                        echo '<a class="text-truncate category-button text-body-secondary" href="/category/' . urlencode( $k ) . '" title="' . htmlentities( $v['description'] ) . '">' . htmlentities( $v['name'] ) . '</a>';
                        echo '</div>';
                    }
                    if ( ! $first_category ) {
                        echo '</div></div>';
                    }
                }
                ?>
            </div>
        </div>
        <?php
        if ( $WHERE_AM_I == 'page' && ! $page->isStatic() && ! $url->notFound() && $page->type() !== 'published' && $page->type() !== 'sticky' ) {
            Theme::plugins('pageBegin');
            echo '<div class="row">';
            echo '<div class="col-12 my-5 text-center">';
            echo '<div class="text-bg-danger fs-3 p-3 my-5 rounded-2 d-inline-block">' .
                 $L->get( 'this-post-is-not-available-at-the-moment' ) .
                 '</div></div></div>';
            Theme::plugins('pageEnd');
            } else {
        ?>
            <div class="row gx-md-5">
                <div class="col-12 col-md-8 col-lg-8 col-xl-8 col-xxl-9 bg-body page-content mt-3" id="content-area">
                    <div class="mt-3 p-1" id="content-area-inner">
                        <?php
                        if ( $WHERE_AM_I == 'page' ) {
                            require_once( THEME_DIR_PHP . 'page.php' );
                        } else {
                            require_once( THEME_DIR_PHP . 'home.php' );
                        }
                        ?>
                    </div>
                </div>
                <div class="d-none d-md-block col-md-4 col-lg-4 col-xl-4 col-xxl-3 p-2 bg-body mt-3 px-2">
                    <?php
                    echo $menu_html;
                    ?>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <div class="offcanvas offcanvas-end d-md-none bg-body bs5simplyblog-notransition" tabindex="-1" id="offcanvasMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel"><?php echo $L->get('navigation'); ?></h5>
            <button type="button" class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="<?php echo $L->get('close'); ?>"></button>
        </div>
        <div class="offcanvas-body menu-area px-3">
            <?php
            echo preg_replace( '/' . ' active-nav' . '/', ' active-nav offcanvas-scroll', $menu_html );
            ?>
        </div>
    </div>


    <?php
    echo '<script src="' . DOMAIN_THEME . 'res/js/bootstrap.bundle.min.js" defer></script>';
    echo '<script src="' . DOMAIN_THEME . 'res/js/highlight.min.js" defer></script>';
    Theme::plugins( 'siteBodyEnd' );
    ?>
</body>
</html>
