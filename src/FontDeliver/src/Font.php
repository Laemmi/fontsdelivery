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

    private $path = '';

    // Light, Regular, SemiBold, Bold, ExtraBold, Black
    private $strength = '';

    // Normal, Italic
    private $style = '';

    private $weight = '';

    private $availablefonttypes = [];

    public function setFontPath(string $value)
    {
        $this->path = $value;
    }

    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function setStrength(string $value)
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

    public function addAvailableFontType(string $value)
    {
        $this->availablefonttypes[] = $value;
    }

    public function getFontPath() : string
    {
        $arr = [
            $this->path,
            $this->getFontName(),
            $this->getFontFileName()
        ];

        return implode(DIRECTORY_SEPARATOR, $arr);
    }

    public function getFontName() : string
    {
        return str_replace(' ', '', $this->getName());
    }

    public function getFontFileName() : string
    {
        $style    = 'Normal' === $this->getStyle() ? '' : $this->getStyle();
        $strength = 'Regular' === $this->getStrength() && 'Italic' === $style ? '' : $this->getStrength();
        return $this->getFontName() . '-' . $strength . $style;
    }

    public function getFontUrl() : string
    {
        return '/font/' . $this->getFontFileName();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getStrength() : string
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
        $t = [];
        foreach ($this->availablefonttypes as $val) {
            $t[] = sprintf('url(\'%1$s.%2$s\') format(\'%2$s\')', $this->getFontUrl(), $val);
        }
        return implode(', ', $t);

//        $t = 'local(\'%1$s %2$s %3$s\'), local(\'%4$s-%2$s%3$s\'), url(\'%5$s\') format(\'woff2\')';
//        return sprintf(implode(', ', $t),
//            $this->getName(),
//            $this->getStrength(),
//            $this->getStyle(),
//            $this->getFontName(),
//            $this->getFontUrl()
//            );
    }
}