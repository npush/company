<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/6/16
 * Time: 6:18 PM
 */

require_once 'abstract.php';
require_once 'fileUploader.php';

class Mage_Shell_ArticleImport extends Mage_Shell_Abstract{

    protected $fileUploader;

    const TITLE = 0;
    const SHORT_CONTENT = 1;
    const POST_CONTENT = 2;
    const EXT_LINK = 3;
    const VIEWS = 4;
    const CATEGORY_NAME = 5;
    const POST_IMAGE = 6;
    const POST_ID = 7;
    const POST_DATE = 8;

    /**
     * Run script
     *
     */
    public function run(){
        $this->fileUploader = new Mage_Shell_fileUploader();
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
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
        /** @var  $model Mageplaza_BetterBlog_Model_Post  */
        $model = Mage::getModel('mageplaza_betterblog/post');
        $categoryIds = array();
        //$category = Mage::getResourceModel('mageplaza_betterblog/category');
        $_postData = array(
            'post_title' => $_post[self::TITLE],
            'post_content' => $_post[self::POST_CONTENT],
            'post_excerpt' => $_post[self::SHORT_CONTENT],
            'image' => '/'.$this->fileUploader->uploadFile($_post[self::POST_IMAGE], Mage::getBaseDir('media') . '/post/image'),
            'status' => 1,
            'views' => $_post[self::VIEWS],
            'entity_id' => $_post[self::POST_ID],
            'created_at' => $_post[self::POST_DATE],
            'updated_at' => $_post[self::POST_DATE]
        );

        $model->setData($_postData);
        $model->setAttributeSetId($model->getDefaultAttributeSetId());
        foreach(explode(';',$_post[self::CATEGORY_NAME]) as $_catName){
            $categoryIds[] = Mage::getResourceModel('mageplaza_betterblog/category_collection')
                ->addFieldToFilter('name', $_catName)
                ->getFirstItem()
                ->getId();
        }
        $model->save();
        $category = Mage::getResourceModel('mageplaza_betterblog/category_collection')
            ->addFieldToFilter('entity_id', $categoryIds[0])
            ->getFirstItem();
        $categoryIds[] = $category->getParentId();
        $model->setCategoriesData($categoryIds);
        $model->getCategoryInstance()->savePostRelation($model);

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
        if (preg_match_all('/"wysiwyg\/post\/(\w+\.\w{3,4})"/', $_post, $out,PREG_SET_ORDER)) {
            foreach ($out as $_foundTypes) {
                $_img = $_foundTypes[1];
                copy(Mage::getBaseDir('media') . '/import/' . $_img, Mage::getBaseDir('media') . '/wysiwyg/post/' . $_img);
            }
            //$fileName = $this->uploadFile($_data[self::MANUAL_FILE_NAME], Mage::getBaseDir('media') . '/post/image');
        }
    }

    private function _parseArticle($_post){
        if(preg_match_all('/\[pt\:(?P<id>[0-9]+)\](?P<name>[\w\s]+)\[\/pt\]/u', $_post, $out, PREG_SET_ORDER)) {
            foreach ($out as $_foundTypes) {
                $searchString = $_foundTypes[0];
                $productType = $_foundTypes['id'];
                $linkName = $_foundTypes['name'];

                $_category = Mage::getResourceModel('catalog/category_collection')
                    ->addFieldToFilter('name', $categoryName)
                    ->getFirstItem();

                $categoryId = $_category->getId();


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