<?php

namespace common\models;


/**
 * This is the model class for table "{{%log}}".
 *
 * @property string $uLogID
 * @property string $uUSN
 * @property string $Category
 * @property string $Verbs
 * @property string $uItemID
 * @property string $tData
 * @property string $uCreatedTime
 * @property string $uLastAccountID
 */

class Recycle extends ActiveRecord
{
    const CATEGORY_CONSUMPTION        = 'Consumption';
    const CATEGORY_CONSUMPTIONBULK    = 'ConsumptionBulk';
    const CATEGORY_ESTOVERSPARENTS    = 'EstoversParents';
    const CATEGORY_INCOME             = 'Income';
    const CATEGORY_DONATION           = 'Donation';
    const CATEGORY_NOTE               = 'Note';
    const CATEGORY_DIARY              = 'Diary';
    const CATEGORY_BOOK               = 'Book';
    const CATEGORY_PASSWORD           = 'Password';
    const CATEGORY_PEDOMETER          = 'Pedometer';
    private static $_categoryList;


    const VERBS_ADD     = 'add';
    const VERBS_UPDATE  = 'update';
    const VERBS_DELETE  = 'delete';
    private static $_verbsList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            parent::timestampBehavior()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Category', 'Verbs', 'uItemID', 'tData'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uLogID' => 'U Log ID',
            'uUSN' => '版本号',
            'Category' => '类型',
            'Verbs' => '操作',
            'uItemID' => '操作对象',
            'tData' => '操作内容',
            'sCreatedTime' => '操作时间',
            'uLastAccountID' => '操作者的帐号id',
            'dateRange' => '操作时间',
        ];
    }

    /**
     * Writes log
     *
     * @param string $category
     * @param string $verbs
     * @param int $id
     * @param mixed $tData
     * @return boolean
     */
    public static function write($category, $verbs, $id = '', $tData)
    {
        $log = new static();
        // TODO：生成USN
        $log->Category = $category;
        $log->Verbs = $verbs;
        $log->uItemID = $id;
        $log->tData = is_string($tData) ? $tData : VarDumper::export($tData);

        return $log->insert(false);
    }

    /**
     * @param string $category
     * @param ActiveRecord $model
     * @param boolean $fullAttributes
     * @return boolean
     */
    public static function createModel($category, ActiveRecord $model)
    {
        return static::write($category, static::VERBS_ADD, $model->getUniqueId(), $model->toArray());
    }

    /**
     * @param string $category
     * @param ActiveRecord $model
     * @param boolean $fullAttributes
     * @return boolean
     */
    public static function updateModel($category, ActiveRecord $model)
    {
        if (empty($model->getLastChangedAttributes())) {
            return false;
        }
        $tData = $model->getLastSavedAttributes();

        return static::write($category, static::VERBS_UPDATE, $model->getUniqueId(), $tData);
    }

    /**
     * @param string $category
     * @param ActiveRecord $model
     * @param boolean $fullAttributes
     * @return boolean
     */
    public static function deleteModel($category, ActiveRecord $model)
    {
        return static::write($category, static::VERBS_DELETE, $model->getUniqueId(), $model->toArray());
    }

    /**
     * @inheritdoc
     */
    public static function getCategoryList()
    {
        if (self::$_categoryList === null) {
            self::$_categoryList = [
                self::CATEGORY_CONSUMPTION       => '个人消费',
                self::CATEGORY_CONSUMPTIONBULK   => '大宗支出',
                self::CATEGORY_ESTOVERSPARENTS   => '赡养父母',
                self::CATEGORY_INCOME            => '收入',
                self::CATEGORY_DONATION          => '捐款',
                self::CATEGORY_NOTE              => '记事本',
                self::CATEGORY_DIARY             => '日记',
                self::CATEGORY_BOOK              => '读书',
                self::CATEGORY_PASSWORD          => '密码',
                self::CATEGORY_PEDOMETER         => '计步器',
            ];
        }

        return self::$_categoryList;
    }

    public function getCategoryMsg()
    {
        $list = static::getCategoryList();

        return $list[$this->Category] ?? null;
    }

    /**
     * @inheritdoc
     */
    public static function getVerbsList()
    {
        if (self::$_verbsList === null) {
            self::$_verbsList = [
                self::VERBS_ADD      => '新增',
                self::VERBS_UPDATE   => '修改',
                self::VERBS_DELETE   => '删除',
            ];
        }

        return self::$_verbsList;
    }

    public function getVerbsMsg()
    {
        $list = static::getVerbsList();

        return $list[$this->Verbs] ?? null;
    }
}