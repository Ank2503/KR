<?php
/**
 *  * Created by Andrey on 08.11.2019.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'],'required'],
            [['image'],'file','extensions'=>'jpg,png']
            ];
    }

    public function uploadFile(UploadedFile $file,$currentImage)
    {
        $this->image=$file;
        if($this->validate()) {
            $this->removeImage($currentImage);
            return $this->saveImage();
        }
    }

    public function getFolder()
    {
        return Yii::getAlias('@web').'uploads/';
    }

    public function Encrypt()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function removeImage($currentImage)
    {
        if($this->isExists($currentImage))
        {
            unlink($this->getFolder() . $currentImage);
        }

    }

    public function isExists($currentImage)
    {
        if (!empty($currentImage) && $currentImage != null) {
            return file_exists($this->getFolder() . $currentImage);
        }
    }

    public function saveImage()
    {
        $filename = $this->Encrypt();

        $this->image->saveAs(Yii::getAlias('@web') . 'uploads/' . $filename);

        return $filename;
    }

}