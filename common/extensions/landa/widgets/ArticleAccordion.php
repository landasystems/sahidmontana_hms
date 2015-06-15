<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleAccordion
 *
 * @author landa
 */
class ArticleAccordion extends CWidget{
    public $article_category_id;
    
    //put your code here
    public function run(){
        $oArticles = Article::model()->findAll('article_category_id=:article_category_id', array(':article_category_id'=>$this->article_category_id));
        foreach ($oArticles as $oArticle){
            echo '<span class="acc-trigger active"><a href="#"><strong>'.$oArticle->title.'</strong></a></span>
                <div class="acc-container">
                    <div class="content">
                        <p>'.$oArticle->content.'</p>
                    </div>
                </div>';
        }   
    }
}

?>
