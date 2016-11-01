<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/6/16
 * Time: 6:18 PM
 */

require_once 'abstract.php';

class Mage_Shell_ArticleImport extends Mage_Shell_Abstract{

    const TITLE = 0;
    const SHORT_CONTENT = 1;
    const POST_CONTENT = 2;
    const EXT_LINK = 3;
    const VIEWS = 4;
    const CATEGORY_NAME = 5;
    const POST_IMAGE = 6;

    /**
     * Run script
     *
     */
    public function run(){
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
            $file = fopen($path, 'r');
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {
                    $this->createPost($data);
                    //$this->createCategory($data[1]);
                    //print_r($this->_parseArticle($data[2]));
                }
                fclose($file);
            }
        } else {

            echo $this->usageHelp();
        }
    }

    public function createPost($_post){
        $model = Mage::getModel('mageplaza_betterblog/post');
        $_postData = array(
            'post_title' => $_post[self::TITLE],
            'post_content' => $_post[self::POST_CONTENT],
            'post_excerpt' => $_post[self::SHORT_CONTENT],
            'image' => $this->uploadFile($_post[self::POST_IMAGE], Mage::getBaseDir('media') . '/post/image'),
            'status' => 1,
            'views' => $_post[self::VIEWS]
        );

        $model->setData($_postData);
        $model->setAttributeSetId($model->getDefaultAttributeSetId());
        foreach(explode(';',$_post[5]) as $_catName){
            $categoryId[] = Mage::getResourceModel('mageplaza_betterblog/category_collection')
                ->addFieldToFilter('name', $_catName)
                ->getFirstItem()
                ->getId();
        }
        $categoryId[] = 3;
        $model->setCategoriesData($categoryId);
        $model->save();
        $this->_savePostImage($_post[self::POST_CONTENT]);
    }

    /**
     * Import categories
     */

    public function createCategory($_cat){
        $category = Mage::getResourceModel('mageplaza_betterblog/category_collection')
            ->addFieldToFilter('name', $_cat)
            ->getFirstItem();
        if(!$categoryId = $category->getId()) {
            $data = array(
                /*'name' => $_cat['title'],
                'url_key' => $_cat['identifier'],
                'meta_keywords' => $_cat['meta_keywords'],
                'meta_description' => $_cat['meta_description'],*/
                'name' => $_cat,
                'status' => 1,
                'parent_id' => 3,
                //'level' => 2,
            );
            $category = Mage::getModel('mageplaza_betterblog/category');
            $category->setData($data);
            $category->setPath('1/3' . $category->getPath());
            $category->save();
            $categoryId = $category->getId();
        }
        return $categoryId;
    }

    private function _savePostImage($_post){
        preg_match('/"wysiwyg\/(\w+\.\w{3,4})"/', $_post, $imageName);
        foreach($imageName as $_img){
            copy(Mage::getBaseDir('media') . '/import/' . $_img, Mage::getBaseDir('media').'/wysiwyg/' . $_img);
        }
        //$fileName = $this->uploadFile($_data[self::MANUAL_FILE_NAME], Mage::getBaseDir('media') . '/post/image');
    }

    private function _parseArticle($_post){
        if(preg_match_all('/\[pt\:(?P<id>[0-9]+)\](?P<name>[\w\s]+)\[\/pt\]/u', $_post, $out, PREG_SET_ORDER)) {
            foreach ($out as $_foundTypes) {
                $searchString = $_foundTypes[0];
                $productType = $_foundTypes['id'];
                $linkName = $_foundTypes['name'];
                $_post = str_replace($searchString, $link, $_post);
            }
        }
        if(preg_match_all('/\[img:https?:\/\/?[\w\.]+\.[a-z]{2,6}\.?(?P<image>\/[\w\.]*)*\/?\]/u', $_post, $out, PREG_SET_ORDER)) {
            foreach($out as $_foundImage) {
                $replaseString = $_foundImage[0];
                $image = Mage::getBaseUrl('media') . ltrim($_foundImage['image'],'/');
                $_post = str_replace($replaseString, $image, $_post);
            }
        }
        return $out;
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f attributeSetImport.php -- --file <csv_file>
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_ArticleImport();
$shell->run();