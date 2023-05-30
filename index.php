
<?php

  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);

  class YGGwave {

    public static function getSignals() {

      $result = [];

      if ($signals = file_get_contents('SIGNALS/YGGDRASIL.md')) {

        foreach (explode(PHP_EOL, $signals) as $signal) {

          if (preg_match('/\[(.*?)\]\((.*?)\)/ui', $signal, $data)) {

            if (!empty($data[1]) && !empty($data[2])) {

              if ($host = parse_url($data[2], PHP_URL_HOST)) {

                $hash = crc32($host);

                $result[] = sprintf('<div style="top:%s%%;left:%s%%">%s</div>',
                                    self::_getSignalPosition($hash),
                                    self::_getSignalPosition($hash),
                                    sprintf('<a target="_blank"
                                                href="%s"
                                                title="%s">%s</a>', htmlspecialchars($data[2]),
                                                                    htmlentities($data[1]),
                                                                    sprintf('<img src="/yggo/file.php?type=identicon&query=%s"
                                                                                  alt="%s"
                                                                                  style="%s" />', urlencode($host),
                                                                                                  htmlentities($data[1]),
                                                                                                  sprintf('background:#%s', substr(dechex($hash), 0, 6)))));
              }
            }
          }
        }
      }

      return $result;
    }

    private static function _getSignalPosition($hash, $padding = 20) {

      $variant = str_split($hash, 2);

      $version = 0;

      do {

        if (!isset($variant[$version])) return rand($padding, 100 - $padding); // :)

        $position = $variant[$version++];

      } while ($position < $padding || $position > 100 - $padding);

      return $position;
    }
  }

?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>YGGwave ~ The Radio Hub</title>
    <meta name="description" content="Open Source, Javascript-less Radio Hub" />
    <meta name="keywords" content="web, radio, hub, stream, yggdrasil, js-less, open source" />
    <meta charset="utf-8" />
    <style>

      @font-face {
        font-family: 'ThasadithRegular';
        src: url('./font/AlumniSansPinstripe-Regular.woff2');
      }

      @font-face {
        font-family: 'ThasadithRegular';
        src: url('./fonts/Thasadith/Thasadith-Regular.eot');
        src: url('./fonts/Thasadith/Thasadith-Regular.eot?#iefix') format('embedded-opentype'),
             url('./fonts/Thasadith/Thasadith-Regular.woff2') format('woff2'),
             url('./fonts/Thasadith/Thasadith-Regular.woff') format('woff'),
             url('./fonts/Thasadith/Thasadith-Regular.ttf') format('truetype');
      }

      * {
        border: 0;
        margin: 0;
        padding: 0;
        color: #fff;
        text-decoration: none;
        font-family: ThasadithRegular, Sans-serif;
        font-weight: lighter;
        -moz-transition: all .5s ease-in;
        -o-transition: all .5s ease-in;
      }

      main {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-image: linear-gradient(to right top, #041b41, #003e68, #00658d, #008eac, #2ab8c6);
        padding: 36px;
      }

      main > h1 > sup > a, main > h1 > sup > a:active, main > h1 > sup > a:visited {
        opacity:0.8;
      }

      main p {
        margin-left: 6px;
      }

      main > div > a, main > div > a:active, main > div > a:visited {
        display: block;
        padding: 4px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 1px rgba(255, 255, 255, 0.36) solid;
      }

      main > div > a > img {
        border-radius: 50%;
        border: 2px rgba(255, 255, 255, 0.68) solid;
        padding: 2px
      }

      main > div {
        position: absolute;
        opacity: 0.8;
      }

      main > div:hover {
        opacity: 1;
      }

    </style>
  </head>
  <body>
    <main>
      <h1>YGGwave<sup><a href="https://github.com/YGGverse/YGGwave/tree/main/SIGNALS" style="color:#<?php echo substr(dechex(crc32(microtime())), 0, 6) ?>">~</a></sup></h1>
      <p>the radio hub</p>
      <?php foreach (YGGwave::getSignals() as $signal) { ?>
        <?php echo $signal ?>
      <?php } ?>
    </main>
  </body>
</html>