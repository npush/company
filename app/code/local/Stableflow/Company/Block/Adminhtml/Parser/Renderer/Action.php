<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/24/17
 * Time: 1:03 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Renderer_Action
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders column
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $options = $this->getColumn()->getOptions();
        $actionAttributes = new Varien_Object();

        $actionCaption = '';
        $this->_transformOptionData($options, $actionCaption, $row);

        $actionAttributes->setData($options);
        $out = '<button ' . $actionAttributes->serialize() . '>' . $actionCaption . '</button>';

        return $out;
    }

    /**
     * Prepares action data for html render
     *
     * @param array $options
     * @param string $actionCaption
     * @param Varien_Object $row
     * @return Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
     */
    protected function _transformOptionData(&$options, &$actionCaption, Varien_Object $row)
    {
        foreach ( $options as $attribute => $value ) {
//            if(isset($options[$attribute]) && !is_array($options[$attribute])) {
//                $this->getColumn()->setFormat($options[$attribute]);
//                $action[$attribute] = parent::render($row);
//            } else {
//                $this->getColumn()->setFormat(null);
//            }

            switch ($attribute) {
                case 'caption':
                    $actionCaption = $options['caption'];
                    unset($options['caption']);
                    break;

                case 'url':
                    if(is_array($options['url'])) {
                        $params = array($options['field']=>$this->_getValue($row));
                        if(isset($options['url']['params'])) {
                            $params = array_merge($options['url']['params'], $params);
                        }
                        $options['href'] = $this->getUrl($options['url']['base'], $params);
                        unset($options['field']);
                    } else {
                        $options['href'] = $options['url'];
                    }
                    unset($options['url']);
                    break;
//'popWin(this.href,\'_blank\',\'width=800,height=700,resizable=1,scrollbars=1\');return false;';
                case 'window':
                    $options['onclick'] = $options['window']."(". $row->getId() ."); return false;";
                    unset($options['window']);
                    break;

            }
        }
        return $this;
    }
}