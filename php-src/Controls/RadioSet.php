<?php

namespace kalanis\kw_forms\Controls;


/**
 * Trida definice formularoveho prvku RadioSet
 *
 * <b>Pouziti:</b>
 * <code>
 * $radios[] = new Form_Controls_Radio(0, 'yes', 'NO');
 * $radios[] = new Form_Controls_Radio(1, 'no', 'YES');
 *
 * $radio = new Form_Controls_RadioSet('alias', 1, null, $radios);
 * echo $radio->value() // no
 *
 * $form->addRadioSet('alias')->addOption(111, 'yes')->addOption(222, 'no');
 *
 */
class RadioSet extends AControl
{
    protected $templateInput = '%3$s';

    /**
     * Vytvori element formularoveho prvku RadioSet wrapper pro jednotlive radio inputy
     * @param string $alias
     * @param mixed $value
     * @param mixed $label
     * @param array $children
     */
    public function set(string $alias, $value = null, string $label = '', array $children = array())
    {
        if (!empty($children)) {
            foreach ($children as $key => $child) {
                if ($child instanceof Radio) {
                    $this->addChild($child);
                } else if (is_string($child)) {
                    $this->addOption($key, $child);
                }
            }
        }
        $this->setEntry($alias, $value, $label);
    }

    /**
     * Prida radio option do setu
     * @param string $value
     * @param string $label
     * @param boolean $selected
     */
    public function addOption($value, $label, $selected = false)
    {
        $this->addChild(new Radio($value, $value, $label, $selected));

        return $this;
    }

    /**
     * Prida radio option do setu
     * @param string $value
     * @param string $label
     * @param boolean $selected
     */
    public function addRadio($value, $label, $selected = false)
    {
        return $this->addOption($value, $label, $selected);
    }

    /**
     * Nastavi potomkum objektu $checked, !!NEdefinovane hodnoty NEbudou vynechany!!
     * @param array $array
     * @return $this
     */
    public function setValues($array = array())
    {
        $value = (string) Other::getFirst($array);
        foreach ($this->children as $alias => $child) {
            if (($child instanceof Radio)) {
                if ("$alias" == "$value") {
                    $child->checked(true);
                } else {
                    $child->checked(false);
                }
            }
        }
        return $this;
    }

    /**
     * Vraci hodnotu zvoleneho radia ze setu
     * @return string
     */
    public function getValues()
    {
        foreach ($this->children as $value => $child) {
            if ($child instanceof Radio) {
                if ($child->checked()) {
                    return $value;
                }
            }
        }
        return false;
    }

}