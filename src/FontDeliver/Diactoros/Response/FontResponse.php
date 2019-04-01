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

namespace FontDeliver\Diactoros\Response;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Response\InjectContentTypeTrait;
use DateTime;
use DateTimeZone;
use DateInterval;

class FontResponse extends Response
{
    use InjectContentTypeTrait;

    /**
     * Create an HTML response.
     *
     * Produces an HTML response with a Content-Type of text/html and a default
     * status of 200.
     *
     * @param string|StreamInterface $html HTML or stream for the message body.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @throws InvalidArgumentException if $html is neither a string or stream.
     */
    public function __construct($font, $status = 200, array $headers = [])
    {
        $format = 'D, d M Y H:i:s \G\M\T';
        $now = new DateTime();

        $expire = new DateTime();
        $expire->setTimezone(new DateTimeZone('UTC'));
        $expire->add(new DateInterval('P1Y'));

        $lastmodified = new DateTime();
        $lastmodified->setTimezone(new DateTimeZone('UTC'));
        $lastmodified->setTimestamp(getlastmod());

        $headers['Access-Control-Allow-Origin'] = '*';
        $headers['Cache-Control']               = sprintf('max-age=%d, public', $expire->getTimestamp() - $now->getTimestamp());
        $headers['Expires']                     = $expire->format($format);
        $headers['Last-Modified']               = $lastmodified->format($format);

        parent::__construct(
            new Stream($font, 'r'),
            $status,
            $this->injectContentType(sprintf('font/%s', (new \SplFileInfo($font))->getExtension()), $headers)
        );
    }
}
