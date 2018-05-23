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

class FontList extends ArrayIterator
{
    public static function factory($family) : FontList
    {
        $list = new self();
        foreach (explode('|', $family) as $f) {
            if (! preg_match('/^(.+)\:(.+)$/', $f, $matches)) {
                continue;
            }

            $name = $matches[1];

            foreach (explode(',', $matches[2]) as $weight) {

                $style = 'Normal';
                if (preg_match('/^([0-9]{3})i$/', $weight, $matches2)) {
                    $style = 'Italic';
                    $weight = $matches2[1];
                }

                $weight = (int) $weight;

                switch (true) {
                    case 900 === $weight:
                        $strenght = 'Black';
                        break;
                    case 800 === $weight:
                        $strenght = 'ExtraBold';
                        break;
                    case 700 === $weight:
                        $strenght = 'Bold';
                        break;
                    case 600 === $weight:
                        $strenght = 'SemiBold';
                        break;
                    case 300 === $weight:
                        $strenght = 'Light';
                        break;
                    case 200 === $weight:
                        $strenght = 'ExtraLight';
                        break;
                    case 400 === $weight:
                    default:
                        $strenght = 'Regular';
                        break;
                }

                $item = new Font();
                $item->setName($name);
                $item->setStrenght($strenght);
                $item->setStyle($style);
                $item->setWeight($weight);

                $list->append($item);
            }
        }

        return $list;
    }
}