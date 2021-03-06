<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_forms\Interfaces\IMultiValue;


/**
 * Definition of form controls Group of Files
 *
 * <b>Examples</b>
 * <code>
 * // render form for upload 5 files
 * $form = new Form();
 * $form->addFiles('fotos', 'Select files', 5);
 * echo $form;
 *
 * // render form for upload 5 files with defined labels
 * $labels = array('file 1','file 2','file 3','file 4','file 5');
 * $form = new Form();
 * $form->addFiles('fotos', $labels, 5)->setLabel('Select files');
 * echo $form;
 *
 * // render form for upload 5 files with defined labels
 * $form = new Form();
 * for($i=1;$i<6;$i++) {
 *     $files[] = new Controls\File(null, null, 'File '.$i);
 * }
 * $form->addFiles('fotos', 'Select files', $files);
 * echo $form;
 * </code>
 */
class Files extends AControl implements IMultiValue
{
    protected $templateLabel = '<label>%2$s</label>';
    protected $templateInput = '%3$s';

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
     * @param string $key
     * @param string|string[] $attributes
     */
    public function addFile(string $key, string $label = '', $attributes = [])
    {
        $formFile = new File();
        $formFile->set($key, $label)->setAttributes($attributes);
        $this->addChild($formFile);
    }

    public function setValues(array $array = []): void
    {
        foreach ($this->children as $child) {
            if ($child instanceof File) {
                $key = $child->getKey();
                if (isset($array[$key])) {
                    $child->setValue($array[$key]);
                }
            }
        }
    }

    public function getValues(): array
    {
        $result = [];
        foreach ($this->children as $child) {
            if ($child instanceof File) {
                $result[$child->getKey()] = $child->getFile();
            }
        }
        return $result;
    }

    /**
     * Render all sub-controls and wrap it all
     * @return string
     * @throws RenderException
     */
    public function renderChildren(): string
    {
        $return = '';
        foreach ($this->children as $alias => $child) {
            if ($child instanceof AControl) {
                $child->setAttribute('id', $this->getAlias() . '_' . $alias);
            }

            $return .= $this->wrapIt($child->render(), $this->wrappersChild) . PHP_EOL;
        }
        return $this->wrapIt($return, $this->wrappersChildren);
    }
}
