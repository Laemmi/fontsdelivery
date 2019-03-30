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
 * @copyright  ©2019 laemmi
 * @license    http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version    1.0.0
 * @since      2019-03-28
 */

namespace FontDeliver\Filter;

use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;

class StrengthToWeight implements FilterInterface
{
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     *
     * @return int
     */
    public function filter($value)
    {
        $strength = (string) $value;

        switch (true) {
            case 'Black' === $strength:
                $weight = 900;
                break;
            case 'ExtraBold' === $strength:
                $weight = 800;
                break;
            case 'Bold' === $strength:
                $weight = 700;
                break;
            case 'SemiBold' === $strength:
                $weight = 600;
                break;
            case 'Light' === $strength:
                $weight = 300;
                break;
            case 'ExtraLight' === $strength:
                $weight = 200;
                break;
            case 'Regular' === $strength:
            default:
                $weight = 400;
                break;
        }

        return $weight;
    }
}