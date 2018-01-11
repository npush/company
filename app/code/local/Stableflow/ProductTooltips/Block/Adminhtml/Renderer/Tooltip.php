<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/17/17
 * Time: 3:47 PM
 */
class Stableflow_ProductTooltips_Block_Adminhtml_Renderer_Tooltip extends Varien_Data_Form_Element_Checkboxes
{
    /**
     * Prepare value list
     *
     * @return array
     */
    protected function _prepareValues() {
        $options = array();
        $values  = array();

        if ($this->getValues()) {
            if (!is_array($this->getValues())) {
                $options = array($this->getValues());
            }
            else {
                $options = $this->getValues();
            }
        }
        elseif ($this->getOptions() && is_array($this->getOptions())) {
            $options = $this->getOptions();
        }
        foreach ($options as $option) {
            $values[] = array(
                'label' => $option['label'],
                'value' => $option['value'],
                'image' => Mage::getBaseUrl('media'). '/tooltips'. $option['image'],
            );
        }

        return $values;
    }

    /**
     * Retrieve HTML
     *
     * @return string
     */
    public function getElementHtml()
    {
        $values = $this->_prepareValues();

        if (!$values) {
            return '';
        }

        $html  = '<ul class="checkboxes">';
        foreach ($values as $value) {
            $html.= $this->_optionToHtml($value);
        }
        $html .= '</ul>'
            . $this->getAfterElementHtml();

        return $html;
    }

    protected function _optionToHtml($option)
    {
        $id = $this->getHtmlId().'_'.$this->_escape($option['value']);

        $html = '<li><input id="'.$id.'"';
        foreach ($this->getHtmlAttributes() as $attribute) {
            if ($value = $this->getDataUsingMethod($attribute, $option['value'])) {
                $html .= ' '.$attribute.'="'.$value.'"';
            }
        }
        $html .= ' value="'.$option['value'].'" />'
            . ' <label for="'.$id.'">' . $option['label'] . '</label> <img src="'.$option['image'].'" width="75" alt="альтернативный текст"></li>'
            . "\n";
        return $html;
    }
}