<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $image
 * @property int $viewed
 * @property int $user_id
 * @property int $status
 * @property int $category_id
 *
 * @property ArticleTag[] $articleTags
 * @property Comment[] $comments
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'],'required'],
            [['title','description', 'content'], 'string'],
            [['date'], 'date','format'=>'php:Y-m-d'],
            [['date'], 'default', 'value' => date('y-m-d')],
            [['title'],'string','max'=>255],
            [['category_id'],'required']

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

    public function saveImage($filename)
    {
        $this->image=$filename;
        return $this->save(false);
    }

    public function deleteImage()
    {
        $imageUploadModel=new ImageUpload();
        $imageUploadModel->removeImage($this->image);
    }

    public function getImage()
    {
        return ($this->image) ? '/uploads/'.$this->image :  '/no-image.png';

    }

    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }


    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);
        if($category != null)
        {
            $this->link('category', $category);
            return true;
        }
    }

    public function saveTag($tags)
    {
        if (is_array($tags))
        {
            ArticleTag::deleteAll(['article_id'=>$this->id]);
            foreach($tags as $tag_id)
            {
                $tag = Tag::findOne($tag_id);
                $this->link('tags', $tag);
            }
        }
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id']);
    }

    public function getSelectedTags()
    {
        $selectedTags=$this->getTags()->select('id')->asArray()->all();
        return ArrayHelper::getColumn($selectedTags,'id');
        //обращаемся к связи как к методу (вытащить все теги статьи и выбрать их айдишники и выбрать все из них
    }

    public function getFormattedDate()
    {
        return Yii::$app->formatter->asDate($this->date);
    }

    public function saveArticle()
    {
        $this->user_id = Yii::$app->user->id;
        return $this->save();
    }

    public function getArticleComments()
    {
        return $this->getComments()->where(['status'=>1])->all();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['article_id'=>'id']);
    }


    public function singlePostCount()
    {
        $this->viewed+=1;
        return $this->save(false);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    public static function getSearchedTagsStatic($id)
    {
        $selectedTags = ArticleTag::find()->select('article_id')->where(['tag_id'=>$id])->asArray()->all();
        return ArrayHelper::getColumn($selectedTags,'article_id');
    }


}
