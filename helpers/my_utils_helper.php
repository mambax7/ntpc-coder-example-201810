<?php
    /**
     * 自定義輔助函式
     */

    if (! function_exists('dd')) {
        /**
         * var_dump and die
         *
         * @param      $item
         * @param bool $die   是否結束程式
         */
        function dd($item, $die = false) {
            echo '<pre>';
            var_dump($item);
            echo '</pre>';

            if ($die) die();
        }
    }

    if (! function_exists('pd')) {
        /**
         * print_r and die
         *
         * @param      $item
         * @param bool $die   是否結束程式
         */
        function pd($item, $die = false) {
            echo '<pre>';
            print_r($item);
            echo '</pre>';

            if ($die) die();
        }
    }

    if (! function_exists('starts_with')) {
        /**
         * 檢查字串是否為 指定字串 或 陣列中某字串 開頭
         *
         * @param  string  $haystack        欲檢查的字串
         * @param  string|array  $needles   開頭的 字串 或 字串陣列
         * @return bool
         */
        function starts_with($haystack, $needles)
        {
            foreach ((array) $needles as $needle) {
                if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                    return true;
                }
            }

            return false;
        }
    }

    if (! function_exists('ends_with')) {
        /**
         * 檢查字串是否為 指定字串 或 陣列中某字串 結尾
         *
         * @param  string  $haystack
         * @param  string|array  $needles
         * @return bool
         */
        function ends_with($haystack, $needles)
        {
            foreach ((array) $needles as $needle) {
                if (substr($haystack, -strlen($needle)) === (string) $needle) {
                    return true;
                }
            }

            return false;
        }
    }

    if (! function_exists('headerTo')) {
        /**
         * 重導向
         *
         * @param string $to
         *
         * @return void
         */
        function headerTo(string $to)
        {
            header("Location: {$to}");
            exit();
        }
    }
