<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_templates\AHtmlElement;


/**
 * Definition of form controls Group of Checkboxes
 *
 * <b>Examples</b>
 * <code>
 * // render form for set 2 values, second will be empty
 * $form = new Form();
 * $checkboxes = $form->getControlFactory()->getCheckboxes('fotos', 'Choose files');
 * $checkboxes->addChild($checkboxes->getCheckbox('file1', 'File 1'));
 * $checkboxes->addChild($checkboxes->getCheckbox('file2', 'File 2', true));
 * $form->addControl($checkboxes);
 * echo $form;
 *
 * // render form for setting 5 values, first two will be set
 * $form = new Form();
 * for($i=1;$i<6;$i++) {
 *     $files[] = $form->getControlFactory()->getCheckbox('', 1, 'File '.$i);
 * }
 * $checkboxes = $form->getControlFactory()->getCheckboxes('fotos', 'Select files', ['0', 1], $files);
 * $form->addControl($checkboxes);
 * echo $form;
 * </code>
 */
class Checkboxes extends AControl
{

    public $templateLabel = '<label>%2$s</label>';
    public $templateInput = '%3$s';

    /**
     * Create group of form elements Checkbox
     * @param string $alias
     * @param string[]|bool[] $value
     * @param string|null $label
     * @param string[]|Checkbox[] $children
     */
    public function set(string $alias, iterable $value = [], ?string $label = null, array $children = array())
    {
        $this->alias = $alias;
        $this->setLabel($label);

        if (is_array($children)) {
            foreach ($children as $childValue => $childLabel) {
                if ($childLabel instanceof Checkbox) {
                    $this->addChild($childLabel, $childValue);
                } else {
                    $this->addChild($this->getCheckbox($childValue, $childLabel));
                }
            }
        }

        if (!empty($value)) {
            $this->setValues((array) $value);
        }
    }

    /**
     * Create single Checkbox element
     * @param string $value
     * @param string $label
     * @param boolean $checked
     * @param string|string[] $attributes
     * @return Checkbox
     */
    public function getCheckbox($value, $label = null, $checked = null, $attributes = array())
    {
        $checkbox = new Checkbox();
        $checkbox->set("$value", "$value", $label);
        $checkbox->addAttributes($attributes);
        $checkbox->setParent($this);
        $checkbox->setValue(strval($checked));
        return $checkbox;
    }

    /**
     * Get statuses of all children
     * @return array
     */
    public function getValues()
    {
        $array = array();
        foreach ($this->children as $alias => $child) {
            if ($child instanceof Checkbox) {
                if (!empty($child->getValue())) {
                    $array[] = $alias;
                }
            }
        }
        return $array;
    }

    /**
     * Set values to all children
     * !! UNDEFINED values will be SET too !!
     * @param string[]|array $array
     * @return AControl
     */
    public function setValues($array = array())
    {
        foreach ($array as &$val) {
            $val = "$val";
        }
        foreach ($this->children as $alias => $child) {
            if ($child instanceof Checkbox) {
                $child->setValue(in_array("$alias", $array));
            }
        }
        return $this;
    }

    /**
     * Render all children, add missing prefixes
     * @return string
     * @throws RenderException
     */
    public function renderChildren(): string
    {
        $return = '';
        foreach ($this->children as $alias => $child) {
            if ($child instanceof AHtmlElement) {
                if ($child instanceof AControl) {
                    if (!empty($this->getAttribute('name'))) {
                        $child->setAttribute('name', $this->getAttribute('name') . '[]');
                        $child->setAttribute('id', $this->getAlias() . '_' . $alias);
                    }
                }

                $return .= $this->wrapIt($child->render(), $this->wrappersChild) . "\n";
            } else {
                $return .= $this->wrapIt($child, $this->wrappersChild) . "\n";
            }
        }
        return $this->wrapIt($return, $this->wrappersChildren);
    }

}