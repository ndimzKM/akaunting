<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Models\Document\Document;
use App\Utilities\Str;
use App\Traits\Currencies;
use App\Traits\Media;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use Cloneable, Currencies, HasFactory, Media;

    protected $table = 'items';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['taxes'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['item_id', 'tax_ids'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'type', 'name', 'description', 'sale_price', 'purchase_price', 'category_id', 'enabled', 'created_from', 'created_by', 'quantity', 'expense_account_id', 'sku', 'income_account_id', 'unit_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sale_price'        => 'double',
        'purchase_price'    => 'double',
        'enabled'           => 'boolean',
        'deleted_at'        => 'datetime',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    protected $sortable = ['name', 'category.name', 'description', 'sale_price', 'purchase_price', 'enabled'];

    /**
     * @var array
     */
    public $cloneable_relations = ['taxes'];

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withoutGlobalScope('App\Scopes\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Common\ItemTax');
    }

    public function document_items()
    {
        return $this->hasMany('App\Models\Document\DocumentItem');
    }

    public function bill_items()
    {
        return $this->document_items()->where('type', Document::BILL_TYPE);
    }

    public function invoice_items()
    {
        return $this->document_items()->where('type', Document::INVOICE_TYPE);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }

    public function scopeBilling($query, $billing)
    {
        return $query->where($billing . '_price', '=', null);
    }

    public function scopePriceType($query, $price_type)
    {
        return $query->whereNotNull($price_type . '_price');
    }

    public function scopeType($query, $type)
    {
        if (empty($type)) {
            return $query;
        }

        return $query->where($this->qualifyColumn('type'), $type);
    }

    /**
     * Get the item id.
     *
     * @return string
     */
    public function getItemIdAttribute()
    {
        return $this->id;
    }

    /**
     * Get the item id.
     *
     * @return string
     */
    public function getTaxIdsAttribute()
    {
        return $this->taxes()->pluck('tax_id');
    }

    /**
     * Update item quantity based on document payment
     *
     * @param Document $document
     * @param bool $pay_in_full
     * @return void
     */
    public function updateQuantityOnPayment(Document $document, bool $pay_in_full)
    {
        if (!$pay_in_full) {
            return;
        }

        $document_items = $document->items()->where('item_id', $this->id)->get();

        foreach ($document_items as $document_item) {
            if ($document->type === Document::INVOICE_TYPE) {
                // For invoices, decrease quantity when paid
                $this->quantity -= $document_item->quantity;
            } elseif ($document->type === Document::BILL_TYPE) {
                // For bills, increase quantity when paid
                $this->quantity += $document_item->quantity;
            }

            $this->save();
        }
    }

    /**
     * Scope autocomplete.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAutocomplete($query, $filter)
    {
        return $query->where(function ($query) use ($filter) {
            foreach ($filter as $key => $value) {
                $query->orWhere($key, 'LIKE', "%" . $value  . "%");
            }
        });
    }

    /**
     * Sort by category name
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function categorySortable($query, $direction)
    {
        return $query->join('categories', 'categories.id', '=', 'items.category_id')
            ->orderBy('name', $direction)
            ->select('items.*');
    }

    public function getInitialsAttribute($value)
    {
        return Str::getInitials($this->name);
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getPictureAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('picture')) {
            return $value;
        } elseif (!$this->hasMedia('picture')) {
            return false;
        }

        return $this->getMedia('picture')->last();
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('items.edit', $this->id),
            'permission' => 'update-common-items',
            'attributes' => [
                'id' => 'index-line-actions-edit-item-' . $this->id,
            ],
        ];

        $actions[] = [
            'title' => trans('general.duplicate'),
            'icon' => 'file_copy',
            'url' => route('items.duplicate', $this->id),
            'permission' => 'create-common-items',
            'attributes' => [
                'id' => 'index-line-actions-duplicate-item-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'items.destroy',
            'permission' => 'delete-common-items',
            'attributes' => [
                'id' => 'index-line-actions-delete-item-' . $this->id,
            ],
            'model' => $this,
        ];

        return $actions;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Item::new();
    }
}
