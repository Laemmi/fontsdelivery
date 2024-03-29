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
 * @version    1.0.7
 * @since      2019-03-28
 */

namespace FontDeliver\Filter;

use Zend\Filter\FilterInterface;

class FontWeight implements FilterInterface
{
    /**
     * @var int
     */
    private int $default = 400;

    /**
     * @var array
     */
    private array $fontWeights;

    /**
     * @param array $fontWeights
     */
    public function __construct(array $fontWeights)
    {
        $this->fontWeights = $fontWeights;
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     *
     * @return mixed
     */
    public function filter($value)
    {
        if (isset($this->fontWeights[$value])) {
            return $this->fontWeights[$value];
        }

        return $this->default;
    }
}
