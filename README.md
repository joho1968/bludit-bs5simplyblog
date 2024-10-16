[![Software License](https://img.shields.io/badge/License-AGPLv3-green.svg?style=flat-square)](LICENSE) [![Bludit 3.15.x](https://img.shields.io/badge/Bludit-3.15.x-blue.svg?style=flat-square)](https://bludit.com) [![Bludit 3.16.x](https://img.shields.io/badge/Bludit-3.16.x-blue.svg?style=flat-square)](https://bludit.com)

# BS5 Simply Blog Theme for Bludit

BS5 Simply Blog is a Bootstrap 5 blog theme for Bludit 3.15.x and 3.16.x. Later 3.x versions may work.

## Description

BS5 Simply Blog is a Bootstrap 5.3.x blog theme for Bludit. It serves Bootstrap locally and does not use Bludit's included Bootstrap.

The intended use case is a clean and functional blog.

The theme is responsive, multi-device friendly, and supports light and dark mode automatically. It should also play nicely with screen readers (ARIA) and similar devices.

* automatic detection of light and dark mode, as configured in the browser
* **sidebar**; sidebar content will be displayed on, well, the sidebar and the off-canvas menu
* **social media links**; social media links will be displayed on the sidebar and the off-canvas menu
* **RSS feed**; the RSS link will be displayed on the sidebar and the off-canvas menu
* **static pages** and **static sub pages**; displayed on the sidebar and the off-canvas menu
* **regular pages** and **sticky pages** are displayed in the "home" context
* **homepage** can be set as the start of your site, or empty for a blog like starting point
* **page description**, **site slogan**, and **site description**
* **pagenotfound**
* **footer**, if configured in Bludit, will be displayed on the sidebar and the off-canvas menu
* browsing by tag and category
* **searching for content**; the search box will be displayed on the sidebar and the off-canvas menu
* **date format** automatically take the configured locale into account
* **highlight.js** is used for "syntax hilighting"
* no icon kit is used, instead the theme uses Emoji where needed

_The theme contains no tracking code of any kind, nor does it load any external resources_

## Demo

You can see this theme in action on [bludit-bs5simplyblog.joho.se](https://bludit-bs5simplyblog.joho.se)

## Requirements

Bludit version 3.15.x or 3.16.x

## Installation

1. Download the latest release from the repository or GitHub
2. Extract the zip file into a folder, such as `tmp`
3. Upload the `bs5simplyblog` folder to your web server or hosting and put it in the `bl-themes` folder where Bludit is installed
4. Go your Bludit admin page
5. Klick on Themes and activate the `bs5simplyblog` theme

## Other things I've created for Bludit

* [BS5Docs](https://bludit-bs5docs.joho.se), a fully featured Bootstrap 5 documentation theme for Bludit
* [BS5Plain](https://bludit-bs5plain.joho.se), a simplistic and clean Bootstrap 5 blog theme for Bludit
* [Chuck Norris Quotes Plugin](https://github.com/joho1968/bludit-chucknorrisquotes), a Chuck Norris Quotes Plugin for Bludit

## Changelog

### 1.0.1 (2024-10-16)
* Corrected the missing call to the `siteBodyBegin` hook.

### 1.0.0 (2024-10-10)
* Initial release

## Other notes

This theme has only been tested with PHP 8.1.x, but should work with other versions too. If you find an issue with your specific PHP version, please let me know and I will look into it.

## License

Please see [LICENSE](LICENSE) for a full copy of AGPLv3.

Copyright 2024 [Joaquim Homrighausen](https://github.com/joho1968); all rights reserved.

This file is part of bs5simplyblog. bs5simplyblog is free software.

bs5simplyblog is free software: you may redistribute it and/or modify it  under
the terms of the GNU AFFERO GENERAL PUBLIC LICENSE v3 as published by the
Free Software Foundation.

bs5simplyblog is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU AFFERO GENERAL PUBLIC LICENSE
v3 for more details.

You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE v3
along with the bs5simplyblog package. If not, write to:
```
The Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor
Boston, MA  02110-1301, USA.
```

## Credits

### Logos and graphics

* RSS icon https://commons.wikimedia.org/wiki/File:Antu_application-atom%2Bxml.svg
* Mastodon icon https://commons.wikimedia.org/wiki/File:Mastodon_Logotype_(Simple).svg
* Twitter / X icon https://commons.wikimedia.org/wiki/File:Twitter_new_X_logo.png
* Facebook icon: https://commons.wikimedia.org/wiki/File:Facebook_Logo_2023.png
* Instagram icon: https://commons.wikimedia.org/wiki/File:Instagram_logo_2022.svg
* Xing icon: https://commons.wikimedia.org/wiki/File:Ionicons_logo-xing.svg
* LinkedIn icon: https://commons.wikimedia.org/wiki/File:LinkedIn_icon.svg
* VK icon: https://commons.wikimedia.org/wiki/File:B%26W_Vk_icon.png
* Telegram Icon: https://commons.wikimedia.org/wiki/File:Telegram_logo_icon.svg
* CodePen icon: CodePen
* GitHub icon: GitHub
* GitLab icon: GitLab

### Other credits

* Bootstrap for the [Bootstrap 5](https://getboostrap.com) framework :blush:
* Kudos to [Diego Najar](https://github.com/dignajar) for [Bludit](https://bludit.com) :blush:
* Kudos to [Ivan Sagalaev](https://github.com/isagalaev) for [highlight.js](https://highlightjs.org) :blush:

The bs5simplyblog theme was written by Joaquim Homrighausen while converting :coffee: into code.

The bs5simplyblog theme is sponsored by [WebbPlatsen i Sverige AB](https://webbplatsen.se), Sweden :sweden:

Commercial support and customizations for this theme is available from WebbPlatsen i Sverige AB.

If you find this Bludit add-on useful, feel free to donate, review it, and or spread the word :blush:

If there is something you feel to be missing from this Bludit add-on, or if you have found a problem with the code or a feature, please do not hesitate to reach out to bluditcode@webbplatsen.se.
