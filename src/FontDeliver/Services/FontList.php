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

namespace FontDeliver\Services;

use ArrayIterator;
use FontDeliver\Font;
use FontDeliver\Validator\FontExists;
use FontDeliver\Validator;
use FontDeliver\Filter;

class FontList extends ArrayIterator
{
    /**
     * @var array
     */
    private array $environment;

    /**
     * @param array $environment
     */
    public function __construct(array $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param string $family
     * @return $this
     */
    public function __invoke(string $family): self
    {
        $datapath = $this->environment['paths']['fonts'];

        $fontValidator = new Validator\FontStrength($this->environment['fontweights']);
        $fontFilter    = new Filter\FontStrength($this->environment['fontweights']);

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

                if (! $fontValidator->isValid($weight)) {
                    continue;
                }

                $item = new Font();
                $item->setFontPath($datapath);
                $item->setName($name);
                $item->setStyle($style);
                $item->setWeight((int) $weight);

                // Check has font files
                foreach ($fontFilter->filter($weight) as $strength) {
                    $item->setStrength($strength);
                    $found_type = false;
                    foreach (['woff2', 'woff'] as $type) {
                        if (is_file($item->getFontPath() . '.' . $type)) {
                            $item->addAvailableFontType($type);
                            $found_type = true;
                        }
                    }
                    if ($found_type) {
                        $this->append($item);
                        continue 2;
                    }
                }
            }
        }

        return $this;
    }
}
