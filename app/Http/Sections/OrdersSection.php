<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;

/**
 * Class OrdersSection
 *
 * @property \App\Models\Order $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class OrdersSection extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation()->setPriority(100)->setIcon('fa fa-folder');
        $this->title = __('Заказы');
        $this->creating = function ($config, Model $model) {
            return false;
        };
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::custom('Заказ', function (Model $model) {
                $orderData = "Заказ №$model->id <br/>";
                $orderInfo = json_decode($model->order_info);
                $cart = unserialize($orderInfo->cart);

                foreach ($cart as $productID => $count) {
                    $product = Product::find($productID);

                    if ($product) {
                        $price = $product->price * $count;
                        $orderData .= "$product->name $product->price руб. X $count ($price руб.) <br/>";
                    }
                }

                //$orderInfo .= "Клиент: $clientData";
                return '<button class="btn btn-success order-info-show" type="button" data-info="' . $orderData . '"><i class="fa fa-eye"></i></button>';
            }),
            AdminColumn::custom('Клиент', function (Model $model) {
                    return $model->userData();
            }),

            AdminColumn::text('created_at', 'Created / updated', 'updated_at')
                ->setWidth('160px')
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('updated_at', $direction);
                })
                ->setSearchable(false)
            ,
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover')
        ;

        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $form = AdminForm::card()->addBody([
            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('name', 'Name')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::datetime('created_at')
                    ->setVisible(true)
                    ->setReadonly(false)
                ,
                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4')->addColumn([
                AdminFormElement::text('id', 'ID')->setReadonly(true),
                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8'),
        ]);

        $form->getButtons()->setButtons([
            'save'  => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create'  => new SaveAndCreate(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        //return $this->onEdit(null, $payload);
    }

    public function isEditable(Model $model) {
        return false;
    }

    public function isCreatable() {
        return false;
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return false;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
