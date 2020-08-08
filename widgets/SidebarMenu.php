<?php

namespace smart\cms\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Menu;

class SidebarMenu extends Menu
{
    public $linkOptions = [];

    public $submenuOptions = [];

    protected $itemKey = 0;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            $this->prepareItems($items);
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');
            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    protected function prepareItems(&$items)
    {
        foreach ($items as $key => $item) {
            $id = $this->id . '_' . ($this->itemKey++);

            if (empty($item['items'])) {
                continue;
            }

            $active = ArrayHelper::getValue($item, 'active', true);
            $options = ArrayHelper::getValue($item, 'linkOptions', $this->linkOptions);
            $options['data-toggle'] = 'collapse';
            $options['aria-expanded'] = $active ? 'true' : 'false';
            $item['template'] = Html::a('{label}', '#' . $id, $options);

            $options = ArrayHelper::getValue($item, 'submenuOptions', $this->submenuOptions);
            $options['id'] = $id;
            Html::addCssClass($options, 'collapse');
            $tag = ArrayHelper::remove($options, 'tag', 'ul');
            $item['submenuTemplate'] = Html::tag($tag, '{items}', $options);

            $this->prepareItems($item['items']);
            $items[$key] = $item;
        }
    }
}
