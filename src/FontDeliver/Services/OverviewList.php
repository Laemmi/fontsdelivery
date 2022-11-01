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
 * @since      2019-03-26
 */

namespace FontDeliver\Services;

use ArrayIterator;
use FontDeliver\Font;
use FontDeliver\FontGroup;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;
use FontDeliver\Validator;
use FontDeliver\FilterIterator;
use FontDeliver\Filter;

class OverviewList extends ArrayIterator
{
    /**
     * OverviewList constructor.
     *
     * @param array $environment
     */
    public function __construct(array $environment)
    {
        $directory = new RecursiveDirectoryIterator($environment['paths']['fonts'], FilesystemIterator::SKIP_DOTS);

        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth(1);

        FilterIterator\FontFiles::$FILTERS = $environment['fonttypes'];
        $iterator = new FilterIterator\FontFiles($iterator);

        $fontGroup = new FontGroup();

        $fontValidator = new Validator\FontWeight($environment['fontweights']);
        $fontFilter = new Filter\FontWeight($environment['fontweights']);

        /**
         * @var $it \SplFileInfo
         */
        foreach ($iterator as $it) {
            if ($it->isDir()) {
                $fontGroup = new FontGroup();
                $fontGroup->setName($it->getFilename());
                $this->append($fontGroup);
            } else {
                if (! preg_match('/^([a-zA-Z- ]+)\-([a-zA-Z]+)\.([0-9a-zA-Z]{3,5})$/', $it->getBasename(), $matches)) {
                    continue;
                }

                $name     = $matches[1];
                $strength = $matches[2];
                $fonttype = $matches[3];

                $style = 'Normal';
                if (preg_match('/(.*)(Italic)$/', $strength, $matches2)) {
                    $style = 'Italic';
                    $strength = $matches2[1] ? $matches2[1] : 'Regular';
                }

                if (! $fontValidator->isValid($strength)) {
                    continue;
                }

                $key = $name . $strength . $style;

                if ($fontGroup->offsetExists($key)) {
                    $file = $fontGroup->offsetGet($key);
                    $file->addAvailableFontType($fonttype);
                    continue;
                }

                $file = new Font();
                $file->setFontPath($it->getPath());
                $file->setName($name);
                $file->setStrength($strength);
                $file->setStyle($style);
                $file->setWeight($fontFilter->filter($strength));
                $file->addAvailableFontType($fonttype);

                $fontGroup->append($file);
            }
        }

        $this->uasort(function ($a, $b) {
            return strnatcmp($a->getName(), $b->getName());
        });

        foreach ($this as $fontGroup) {
            $fontGroup->uasort(function ($a, $b) {
                $result = strnatcmp($a->getName(), $b->getName());
                if (0 !== $result) {
                    return $result;
                }

                $result = strnatcmp($a->getWeight(), $b->getWeight());
                if (0 !== $result) {
                    return $result;
                }

                $result = strnatcmp($b->getStyle(), $a->getStyle());
                if (0 !== $result) {
                    return $result;
                }

                return 0;
            });
        }
    }

    /**
     * @return string
     */
    public function getUrlPart() : string
    {
        $arr = [];
        foreach ($this->getArrayCopy() as $val) {
            $arr[] = $val->getUrlPart();
        }

        return implode('|', $arr);
    }
}