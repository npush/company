<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/17/17
 * Time: 3:47 PM
 */
class Stableflow_AdditionalCodes_Block_Adminhtml_Renderer_Manufnumber extends Varien_Data_Form_Element_Text
{
    public function getAfterElementHtml()
    {
        $html = parent::getAfterElementHtml();
        return $html."  <script>
        				$('".$this->getHtmlId()."').disable();
        				</script>";
    }

    public function getElementHtml()
    {
        $html = '<input id="'.$this->getHtmlId().'" name="'.$this->getName()
            .'" value="'.$this->getEscapedValue().'" '.$this->serialize($this->getHtmlAttributes()).'/>'."\n";
        $html.= $this->getAfterElementHtml();
        return $html;
    }

    public function getEscapedValue($index=null)
    {
        $value = $this->getValue($index);

        return implode(', ', $value);

        if ($filter = $this->getValueFilter()) {
            $value = $filter->filter($value);
        }
        return $this->_escape($value);
    }

}