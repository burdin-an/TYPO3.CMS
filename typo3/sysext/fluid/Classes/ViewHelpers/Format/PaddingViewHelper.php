<?php
namespace TYPO3\CMS\Fluid\ViewHelpers\Format;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Formats a string using PHPs str_pad function.
 * @see http://www.php.net/manual/en/function.str_pad.php
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <f:format.padding padLength="10">TYPO3</f:format.padding>
 * </code>
 * <output>
 * TYPO3     (note the trailing whitespace)
 * <output>
 *
 * <code title="Specify padding string">
 * <f:format.padding padLength="10" padString="-=">TYPO3</f:format.padding>
 * </code>
 * <output>
 * TYPO3-=-=-
 * </output>
 *
 * <code title="Specify padding type">
 * <f:format.padding padLength="10" padString="-" padType="both">TYPO3</f:format.padding>
 * </code>
 * <output>
 * --TYPO3---
 * </output>
 *
 * @api
 */
class PaddingViewHelper extends AbstractViewHelper
{
    /**
     * Output is escaped already. We must not escape children, to avoid double encoding.
     *
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * Pad a string to a certain length with another string
     *
     * @param int $padLength Length of the resulting string. If the value of pad_length is negative or less than the length of the input string, no padding takes place.
     * @param string $padString The padding string
     * @param string $padType Append the padding at this site (Possible values: right,left,both. Default: right)
     * @param string $value string to format
     * @return string The formatted value
     * @api
     */
    public function render($padLength, $padString = ' ', $padType = 'right', $value = null)
    {
        return static::renderStatic(
            array(
                'padLength' => $padLength,
                'padString' => $padString,
                'padType' => $padType,
                'value' => $value
            ),
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * Applies str_pad() on the specified value.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $value = $arguments['value'];
        if ($value === null) {
            $value = $renderChildrenClosure();
        }
        $padTypes = array(
            'left' => STR_PAD_LEFT,
            'right' => STR_PAD_RIGHT,
            'both' => STR_PAD_BOTH
        );
        $padType = $arguments['padType'];
        if (!isset($padTypes[$padType])) {
            $padType = 'right';
        }

        return str_pad($value, $arguments['padLength'], $arguments['padString'], $padTypes[$padType]);
    }
}
