<?php

namespace App\AdminElements;

use SleepingOwl\Admin\Display\Column\Image as ParentImageColumn;

class CustomImageExplorer extends ParentImageColumn {
    protected $view = 'column.searchable_image';

    public function toArray()
    {
        $value = $this->getModelValue();
        $id = $this->getModel()->id;
        if ($this->asset && $value) {
            $value = $this->asset.$value;
        }
        if (! empty($value) && (strpos($value, '://') === false)) {
            $value = asset($value);
        }

        return parent::toArray() + [
            'value' => $value,
            'lazy' => $this->getLazyLoad(),
            'imageWidth' => $this->getImageWidth(),
            'id' => $id,
        ];
    }
}
