<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use backend\assets\AppAsset;
use common\models\Config;

AppAsset::register($this);
$this->registerMetaTag(array("name"=>"viewport", "content"=>"width=device-width, initial-scale=1, user-scalable=no, minimal-ui"));

$route = Yii::$app->requestedAction->uniqueId;
$accountType = Yii::$app->user->identity->Type;

$menu = [
    [
        'label' => '系统',
        'url' => '#',
        'items' => [
            'home' => ['label' => '系统信息', 'url' => ['site/index'], 'active' => in_array($route, ['site/index'])],
        ]
    ],
];
if ($accountType == 'web') {
    // 正常用户登录
    $menu = [
        [
            'label' => '系统',
            'url' => '#',
            'items' => [
                'home' => ['label' => '系统信息', 'url' => ['site/index'], 'active' => in_array($route, ['site/index'])],
                'usersuper' => ['label' => '用户管理', 'url' => ['usersuper/index'], 'active' => in_array($route, ['usersuper/index', 'usersuper/create-account', 'usersuper/update-account'])],
                'wxskeysuper' => ['label' => '微信Skey', 'url' => ['wxskeysuper/index'], 'active' => in_array($route, ['wxskeysuper/index'])],
                'recyclesuper' => ['label' => '回收站', 'url' => ['recyclesuper/index'], 'active' => in_array($route, ['recyclesuper/index', 'recyclesuper/view-recycle'])],
            ]
        ],
        [
            'label' => '账单',
            'url' => '#',
            'items' => [
                'consumptionsuper' => ['label' => '个人消费', 'url' => ['consumptionsuper/index'], 'active' => in_array($route, ['consumptionsuper/index', 'consumptionsuper/add-consumption', 'consumptionsuper/update-consumption', 'consumptionsuper/summary-month' ,'consumptionsuper/summary-rate'])],
                'estoversparentssuper' => ['label' => '赡养父母', 'url' => ['estoversparentssuper/index'], 'active' => in_array($route, ['estoversparentssuper/index', 'estoversparentssuper/add-estovers', 'estoversparentssuper/update-estovers'])],
                'incomesuper' => ['label' => '收入', 'url' => ['incomesuper/index'], 'active' => in_array($route, ['incomesuper/index', 'incomesuper/add-income', 'incomesuper/update-income'])],
                'donationsuper' => ['label' => '捐款', 'url' => ['donationsuper/index'], 'active' => in_array($route, ['donationsuper/index', 'donationsuper/add-donation', 'donationsuper/update-donation'])],
            ]
        ],
        [
            'label' => '笔记',
            'url' => '#',
            'items' => [
                'notesuper' => ['label' => '记事本', 'url' => ['notesuper/index'], 'active' => in_array($route, ['notesuper/index', 'notesuper/add-note', 'notesuper/view-note', 'notesuper/update-note'])],
                'diarysuper' => ['label' => '日记', 'url' => ['diarysuper/index'], 'active' => in_array($route, ['diarysuper/index', 'diarysuper/add-diary', 'diarysuper/view-diary', 'diarysuper/update-diary'])],
                'booksuper' => ['label' => '读书', 'url' => ['booksuper/index'], 'active' => in_array($route, ['booksuper/index', 'booksuper/add-book', 'booksuper/view-book', 'booksuper/update-book'])],
                'passwordsuper' => ['label' => '密码', 'url' => ['passwordsuper/index'], 'active' => in_array($route, ['passwordsuper/index', 'passwordsuper/add-password', 'passwordsuper/view-password', 'passwordsuper/update-password'])],
            ]
        ],
        [
            'label' => '数据',
            'url' => '#',
            'items' => [
                'pedometersuper' => ['label' => '计步器', 'url' => ['pedometersuper/index'], 'active' => in_array($route, ['pedometersuper/index', 'pedometersuper/add-pedometer', 'pedometersuper/update-pedometer'])],
                // 'weathersuper' => ['label' => '气象站', 'url' => ['weathersuper/index'], 'active' => in_array($route, ['weathersuper/index', 'weathersuper/add-weather', 'weathersuper/update-weather'])],
            ]
        ],
        [
            'label' => '控制',
            'url' => '#',
            'items' => [
                'configsuper' => ['label' => '配置', 'url' => ['configsuper/index'], 'active' => in_array($route, ['configsuper/index', 'configsuper/add-config', 'configsuper/update-config'])],
            ]
        ],
    ];
} else if ($accountType == 'heritage') {
    $config = Config::find()
        ->where(['sConfigKey' => 'LAST_ACCESS'])
        ->one();
    if ($config->tConfigValue == 'DANGER') {
        // 危险状况，提供基础信息
        $menu = [
            [
                'label' => '系统',
                'url' => '#',
                'items' => [
                    'home' => ['label' => '系统信息', 'url' => ['site/index'], 'active' => in_array($route, ['site/index'])],
                ]
            ],
            [
                'label' => '数据',
                'url' => '#',
                'items' => [
                    'consumption' => ['label' => '个人消费', 'url' => ['heritage/consumption'], 'active' => in_array($route, ['heritage/consumption'])],
                    // 'diarysuper' => ['label' => '日记', 'url' => ['diarysuper/index'], 'active' => in_array($route, ['diarysuper/index', 'diarysuper/add-diary', 'diarysuper/view-diary', 'diarysuper/update-diary'])],
                    // 'booksuper' => ['label' => '读书', 'url' => ['booksuper/index'], 'active' => in_array($route, ['booksuper/index', 'booksuper/add-book', 'booksuper/view-book', 'booksuper/update-book'])],
                    // 'pedometersuper' => ['label' => '计步器', 'url' => ['pedometersuper/index'], 'active' => in_array($route, ['pedometersuper/index', 'pedometersuper/add-pedometer', 'pedometersuper/update-pedometer'])],
                ]
            ],
        ];
    } else if ($config->tConfigValue == 'DEAD') {
        // 已经可以展示所有遗产了
        $menu = [
            [
                'label' => '系统',
                'url' => '#',
                'items' => [
                    'home' => ['label' => '系统信息', 'url' => ['site/index'], 'active' => in_array($route, ['site/index'])],
                    'recyclesuper' => ['label' => '回收站', 'url' => ['recyclesuper/index'], 'active' => in_array($route, ['recyclesuper/index', 'recyclesuper/view-recycle'])],
                ]
            ],
            [
                'label' => '遗产',
                'url' => '#',
                'items' => [
                    'consumption' => ['label' => '个人消费', 'url' => ['heritage/consumption'], 'active' => in_array($route, ['heritage/consumption'])],
                    // 'estoversparentssuper' => ['label' => '赡养父母', 'url' => ['estoversparentssuper/index'], 'active' => in_array($route, ['estoversparentssuper/index', 'estoversparentssuper/add-estovers', 'estoversparentssuper/update-estovers'])],
                    // 'incomesuper' => ['label' => '收入', 'url' => ['incomesuper/index'], 'active' => in_array($route, ['incomesuper/index', 'incomesuper/add-income', 'incomesuper/update-income'])],
                    // 'notesuper' => ['label' => '记事本', 'url' => ['notesuper/index'], 'active' => in_array($route, ['notesuper/index', 'notesuper/add-note', 'notesuper/view-note', 'notesuper/update-note'])],
                    // 'diarysuper' => ['label' => '日记', 'url' => ['diarysuper/index'], 'active' => in_array($route, ['diarysuper/index', 'diarysuper/add-diary', 'diarysuper/view-diary', 'diarysuper/update-diary'])],
                    // 'booksuper' => ['label' => '读书', 'url' => ['booksuper/index'], 'active' => in_array($route, ['booksuper/index', 'booksuper/add-book', 'booksuper/view-book', 'booksuper/update-book'])],
                    // 'passwordsuper' => ['label' => '密码', 'url' => ['passwordsuper/index'], 'active' => in_array($route, ['passwordsuper/index', 'passwordsuper/add-password', 'passwordsuper/view-password', 'passwordsuper/update-password'])],
                    // 'pedometersuper' => ['label' => '计步器', 'url' => ['pedometersuper/index'], 'active' => in_array($route, ['pedometersuper/index', 'pedometersuper/add-pedometer', 'pedometersuper/update-pedometer'])],
                ]
            ],
        ];
    } 
}

?>

<?php $this->beginContent('@app/views/layouts/base.php'); ?>
    <div id="wrapper">
        <nav class="sidebar">
            <?= Html::a('<img src="/img/aura.png" class="logo-rotation">', Yii::$app->homeUrl, ['class' => 'sidebar-logo']) ?>
            <?= Menu::widget([
                'encodeLabels' => false,
                'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                'options' => [
                    'class' => 'nav',
                    'id' => 'side-menu'
                ],
                'items' => $menu
            ]) ?>
        </nav>

        <div id='page-wrapper'>
            <?= $content ?>
        </div>
    </div>
<?php $this->endContent() ?>