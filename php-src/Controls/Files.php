<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_rules\Interfaces;
use kalanis\kw_rules\Rules;
use kalanis\kw_templates\HtmlElement\IHtmlElement;


/**
 * Trida definice formularoveho prvku Files skupina File inputu
 *
 * <b>Priklady pouziti</b>
 * <code>
 * // vyrenderuje form pro nahrani 5ti souboru
 * $form = Factory::Form();
 * $form->addFiles('fotky', 'Vyberte soubory', 5);
 * echo $form;
 *
 * // vyrenderuje form pro nahrani 5ti souboru s definovanymi labely
 * $labels = array('Soubor 1','Soubor 2','Soubor 3','Soubor 4','Soubor 5');
 * $form = Factory::Form();
 * $form->addFiles('fotky', $labels, 5)->label('Vyberte soubory');
 * echo $form;
 *
 * // vyrenderuje form pro nahrani 5ti souboru s definovanymi labely
 * $form = Factory::Form();
 * for($i=1;$i<6;$i++) {
 *     $files[] = new Form_Controls_File(null, null, 'Soubor '.$i);
 * }
 * $form->addFiles('fotky', 'Vyberte soubory', $files);
 * echo $form;
 * </code>
 */
class Files extends AControl
{
    protected $templateLabel = '<label>%2$s</label>';
    protected $templateInput = '%3$s';

    protected function whichFactory(): Interfaces\IRuleFactory
    {
        return new Rules\File\Factory();
    }

    public function set(string $alias, iterable $items = [], string $label = '', $attributes = []): self
    {
        $this->alias = $alias;
        $this->setLabel($label);
        foreach ($items as $key => $item) {
            $al = is_numeric($key) ? sprintf('%s[]', $alias) : sprintf('%s[%s]', $alias, strval($key));
            $this->addFile($al, $item, $attributes);
        }
        return $this;
    }

    /**
     * Add File input
     * @param string $label
     * @param string $alias
     * @param string|string[] $attributes
     */
    public function addFile(string $alias, string $label = '', $attributes = [])
    {
        $formFile = new File();
        $formFile->set($alias, $label)->setAttributes($attributes);
        $formFile->setParent($this);
        $this->addChild($formFile);
    }

//    /**
//     * Set values to children, !! Undefined values will be filed too !!
//     * @param array $array
//     * @return Files
//     * @throws \kalanis\kw_forms\Exceptions\EntryException
//     */
//    public function setValues($array = array()) {
//        foreach ($this->children as $alias => $child) {
//            if ($child instanceof File) {
//                $value = array();
//                $value['name'] = isset($array['name'][$alias]) ? $array['name'][$alias] : '';
//                $value['type'] = isset($array['type'][$alias]) ? $array['type'][$alias] : '';
//                $value['tmp_name'] = isset($array['tmp_name'][$alias]) ? $array['tmp_name'][$alias] : '';
//                $value['error'] = isset($array['error'][$alias]) ? $array['error'][$alias] : UPLOAD_ERR_NO_FILE;
//                $value['size'] = isset($array['size'][$alias]) ? $array['size'][$alias] : 0;
//                $child->setValue($value);
//            }
//        }
//        return $this;
//    }

    /**
     * Render all sub-controls and wrap it all
     * @return string
     * @throws \kalanis\kw_forms\Exceptions\RenderException
     */
    public function renderChildren()
    {
        $return = '';
        foreach ($this->children as $alias => $child) {
            if ($child instanceof IHtmlElement) {
                if ($child instanceof AControl) {
                    $child->setAttribute('name', $child->getKey());
                    $child->setAttribute('id', $this->getAlias() . '_' . $alias);
                }

                $return .= $this->wrapIt($child->render(), $this->wrappersChild) . "\n";
            } else {
                $return .= $this->wrapIt($child, $this->wrappersChild) . "\n";
            }
        }
        return $this->wrapIt($return, $this->wrappersChildren);
    }

    public function validate(Interfaces\IValidate $entry): bool
    {
        $valid = true;
        foreach ($this->children as $child) {
            if ($child instanceof AControl) {
                $child->removeRules();
                $child->addRules($this->getRules());
                $valid &= $child->validate($child);
            }
        }

        return $valid;
    }
}
