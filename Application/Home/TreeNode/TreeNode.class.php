<?php
namespace TreeNode;
class TreeNode{
    public $id;
    public $text;
    public $children;
    /**
     * @return $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    
}

?>