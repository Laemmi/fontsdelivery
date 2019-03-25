<?php
/**
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @category   fontsdelivery
 * @author     Michael Lämmlein <laemmi@spacerabbit.de>
 * @copyright  ©2018 laemmi
 * @license    http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version    1.0.0
 * @since      21.05.18
 */

namespace FontDeliver;

use ArrayIterator;
use FontDeliver\Validator\FontExists;

class FontList extends ArrayIterator
{
    public static function factory($family) : FontList
    {
        $datapath = realpath(__DIR__ . '/../../data/fonts');

        $list = new self();
        foreach (explode('|', $family) as $f) {
            if (! preg_match('/^(.+)\:(.+)$/', $f, $matches)) {
                continue;
            }

            $name = $matches[1];

            if (! (new FontExists($datapath))->isValid($name)) {
                continue;
            }

            foreach (explode(',', $matches[2]) as $weight) {
                $style = 'Normal';
                if (preg_match('/^([0-9]{3})i$/', $weight, $matches2)) {
                    $style = 'Italic';
                    $weight = $matches2[1];
                }

                $weight = (int) $weight;

                switch (true) {
                    case 900 === $weight:
                        $strength = 'Black';
                        break;
                    case 800 === $weight:
                        $strength = 'ExtraBold';
                        break;
                    case 700 === $weight:
                        $strength = 'Bold';
                        break;
                    case 600 === $weight:
                        $strength = 'SemiBold';
                        break;
                    case 300 === $weight:
                        $strength = 'Light';
                        break;
                    case 200 === $weight:
                        $strength = 'ExtraLight';
                        break;
                    case 400 === $weight:
                    default:
                        $strength = 'Regular';
                        break;
                }

                $item = new Font();
                $item->setFontPath($datapath);
                $item->setName($name);
                $item->setStrength($strength);
                $item->setStyle($style);
                $item->setWeight($weight);

                $found = false;
                foreach (['woff2', 'woff'] as $type) {
                    if (is_file($item->getFontPath() . '.' . $type)) {
                        $item->addAvailableFontType($type);
                        $found = true;
                    }
                }
                if (! $found) {
                    continue;
                }

                $list->append($item);
            }
        }

        return $list;
    }
}