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

class Font
{
    private $name = '';

    // Light, Regular, SemiBold, Bold, ExtraBold, Black
    private $strength = '';

    // Normal, Italic
    private $style = '';

    private $weight = '';


    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function setStrenght(string $value)
    {
        $this->strength = $value;
    }

    public function setStyle(string $value)
    {
        $this->style = $value;
    }

    public function setWeight(int $value)
    {
        $this->weight = $value;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getStrenght() : string
    {
        return $this->strength;
    }

    public function getWeight() : int
    {
        return $this->weight;
    }

    public function getStyle() : string
    {
        return $this->style;
    }



    public function getCssStyle() : string
    {
        return strtolower($this->style);
    }

    public function getCssSrc()
    {
        $t = 'local(\'%1$s %2$s %3$s\'), local(\'%4$s-%2$s%3$s\'), url(XXX.woff2) format(\'woff2\')';

        return sprintf($t,
            $this->getName(),
            $this->getStrenght(),
            $this->getStyle(),
            str_replace(' ', '', $this->getName())
            );
    }
}