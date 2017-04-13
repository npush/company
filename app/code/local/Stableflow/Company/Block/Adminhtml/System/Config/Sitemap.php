<?php


class Stableflow_Company_Block_Adminhtml_System_Config_Sitemap extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = $this->getUrl('adminhtml/company_company/generateSitemap');

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable')
            ->setLabel(Mage::helper('company')->__('Generate Companies Sitemap'))
            ->setOnClick("return conformation();")
            ->toHtml()
        ;

        $html .= "<p class='note'>";
        $html .= "<span style='color:#E02525;'>";
        $html .= Mage::helper('company')->__(
            "IT IS INTEGRATED WITH MAGENTO DEFAULT SITEMAP. <br>Generate sitemap and save to ".Mage::getBaseUrl(true) ."sitemap/sitemap_companies.xml. "
        );
        $html .= "</span>";
        $html .= "</p>";
        $html .= "
            <script type='text/javascript'>
                function conformation (){
                    if (confirm('" . $this->__('Are you sure?') . "')) {
                        var url ='{$url}';
                        new Ajax.Request(url, {
                            parameters: {
                                         form_key: FORM_KEY,
                                         },
                            evalScripts: true,
                            onSuccess: function(transport) {
//                                if(transport.responseText =='success'){
                                 location.reload();
//                                }
                            }
                        });
                    }
                }
            </script>
        ";
        return $html;
    }
}