<?php

namespace smart\cms;

use Yii;
use yii\base\InvalidConfigException;
// use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\BaseManager;
use smart\base\BackendModule;
use smart\cms\components\User;

class Module extends BackendModule
{
    const VERSION = 'v1 Alpha';

    /**
     * @inheritdoc
     */
    public $layout = 'main';

    /**
     * @var array Config that appling to backend modules
     */
    public $modulesConfig = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->checkConfig();
        $this->setApplicationSettings();
        $this->checkPasswordChange();
        // $this->prepareModules();

        $this->makeMenus();
    }

    /**
     * @inheritdoc
     */
    public static function moduleName()
    {
        return 'cms';
    }

    /**
     * Check application configuration
     * @return void
     */
    private function checkConfig()
    {
        // Auth manager
        $auth = Yii::$app->getAuthManager();
        if (!($auth instanceof BaseManager)) {
            throw new InvalidConfigException('You should to configure "authManager" application component inherited from ' . BaseManager::className() . ' class.');
        }

        // User application component
        $user = Yii::$app->getUser();
        if (!($user instanceof User)) {
            throw new InvalidConfigException('You should to set "user" application component inherited from ' . User::className() . ' class.');
        }
    }

    /**
     * Change application settings
     * @return void
     */
    private function setApplicationSettings()
    {
        $app = Yii::$app;

        // Application home url
        $app->homeUrl = ['/' . $this->id . '/default/index'];

        // Remove asset bundles
        $app->assetManager->bundles['yii\bootstrap4\BootstrapAsset'] = false;
        $app->assetManager->bundles['yii\bootstrap4\BootstrapPluginAsset'] = false;

        // Error page
        $app->errorHandler->errorAction = '/' . $this->id . '/default/error';

        // Login and password change
        $user = Yii::$app->getUser();
        $user->loginUrl = ['/' . $this->id . '/user/login'];
        if ($user->hasProperty('passwordChangeUrl')) {
            $user->passwordChangeUrl = ['/' . $this->id . '/user/password'];
        }
    }

    /**
     * Checking if user needs to change password
     * @return void
     */
    private function checkPasswordChange()
    {
        // Do not check for password change, logout pages and if user is guest
        $user = Yii::$app->getUser();
        $route = '/' . Yii::$app->getRequest()->resolve()[0];
        // Guest
        if ($user->getIsGuest()) {
            return;
        }
        // Password change page
        if ($route == $user->passwordChangeUrl[0]) {
            return;
        }
        // Logout page
        if ($route == '/' . $this->id . '/user/logout') {
            return;
        }

        // Check
        if ($user->getIdentity()->passwordChange && $user->passwordChangeRequired()) {
            Yii::$app->end();
        }
    }

    // /**
    //  * Check modules, that may be used in CMS
    //  * @return void
    //  */
    // protected function prepareModules()
    // {
    //     $modules = [];

    //     //add custom modules
    //     $modules = array_merge($modules, $this->customModules);

    //     //add exists modules
    //     foreach (require(__DIR__ . '/config/modules.php') as $id => $module) {
    //         if (class_exists($module)) {
    //             $modules[$id] = array_merge(['class' => $module], ArrayHelper::getValue($this->modulesConfig, $id, []));
    //         }
    //     }

    //     //apply
    //     $this->modules = $modules;

    //     //init user module
    //     if ($this->getModule('user') === null) {
    //         throw new InvalidConfigException('Module `user` not found.');
    //     }

    //     //init other modules to prepare data
    //     foreach (array_keys($modules) as $name) {
    //         $this->getModule($name);
    //     }
    // }

    /**
     * Building menus
     * @return void
     */
    protected function makeMenus()
    {
        // Header menu
        Yii::$app->params['menu-header'] = [
            [
                'label' => '<i class="fas fa-globe"></i><span class="d-none d-sm-inline"> ' . Html::encode(Yii::t('cms', 'Site')) . '</span>',
                'encode' => false,
                'url' => '/',
            ],
        ];
        $user = Yii::$app->getUser();
        if (!$user->getIsGuest()) {
            $username = Html::encode($user->getUsername());
            Yii::$app->params['menu-header'][] = [
                'label' => '<i class="fas fa-user"></i><span class="d-none d-sm-inline"> ' . $username . '</span>',
                'encode' => false,
                'dropdownOptions' => ['class' => 'dropdown-menu-right'],
                'items' => [
                    ['label' => Yii::t('cms', 'Settings'), 'url' => ['/' . $this->id . '/user/settings']],
                    ['label' => Yii::t('cms', 'Change password'), 'url' => ['/' . $this->id . '/user/password']],
                    '<div class="dropdown-divider"></div>',
                    ['label' => Yii::t('cms', 'Logout'), 'url' => ['/' . $this->id . '/user/logout']],
                ],
            ];
        }

        // Modules menu
        $modulesMenu = [];
        foreach ($this->modules as $module) {
            if ($module instanceof BackendModule) {
                $module->menu($modulesMenu);
            }
        }
        Yii::$app->params['menu-modules'] = $this->normalizeItems($modulesMenu);
    }

    /**
     * Normalize menu items (url route)
     * @param array $items 
     * @return array
     */
    protected function normalizeItems($items)
    {
        //base route
        $base = '/' . $this->id;

        //process items
        foreach ($items as $key => $item) {
            //url
            $route = ArrayHelper::getValue($item, ['url', 0]);
            if ($route !== null) {
                if ($route[0] != '/') {
                    $route = '/' . $route;
                }
                $item['url'][0] = $base . $route;
            }
            //normolize children items
            if (isset($item['items'])) {
                $item['items'] = $this->normalizeItems($item['items']);
            }
            //set normalized item
            $items[$key] = $item;
        }
        return $items;
    }
}
