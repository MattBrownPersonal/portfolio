<?php

namespace App\Models;

use App\Mail\CustomerOrderConfirmation;
use App\Mail\NewOrderEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    use HasFactory;

    const ENQUIRYFROMSHARE = 'Enquiry - from share with crem';

    const ENQUIRYFROMCONTACTUS = 'Enquiry - from contact us';

    const ORDER = 'Order';

    const PRODUCT = 'product';

    const ATTRIBUTE = 'attribute';

    const FONTCOLOUR = 'fontColour';

    const MATERIAL = 'material';

    const FONTFACE = 'fontFace';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'deceased_id',
        'order_date',
        'status',
    ];

    public static function saveOrder($request, $type)
    {
        DB::beginTransaction();

        try {
            $order = new self;
            $order->deceased_id = $request->id;
            $order->order_date = $request->order_date;
            $order->site_id = $request->site_id;
            $order->status_id = Status::UNFULFILLED;
            $order->order_number = self::generateOrderNumber();
            $order->name = $request->name;
            $order->email = $request->customerEmail;
            $order->phone_number = $request->contactNumber;
            $order->message = $request->message;
            $order->type = $type;
            $order->save();
        } catch(\Exception $e) {
            DB::rollback();

            return $e->getMessage();
        }
        DB::commit();

        return $order;
    }

    public static function saveOrderItem($request, $order)
    {
        $product = json_decode($request->product, true);
        $productDetails = json_decode($request->productDetails, true);
        $lines = json_decode($request->customerText, true);

        if ($request->productImage !== 'null') {
            $savedImage = Image::storeBase64Image($request->productImage);
        } else {
            $savedImage = null;
        }

        DB::beginTransaction();

        try {
            $orderItem = new ItemOrder;
            $orderItem->order_id = $order->id;
            $orderItem->price = $product['price'];
            $orderItem->image_url = $savedImage === null ? null : $savedImage->imageurl;
            $orderItem->status_id = Status::UNFULFILLED;
            $orderItem->item_type = self::PRODUCT;
            $orderItem->product_name = $product['name'];
            $orderItem->supplier_name = $product['supplier']['name'];
            $orderItem->attribute_type = self::PRODUCT;
            $orderItem->attribute_name = $product['name'];
            $orderItem->product_id = $product['id'];
            $orderItem->save();
            if ($lines) {
                foreach ($lines as $key => $line) {
                    $attributeLine = new Line;
                    $attributeLine->line_number = ($key + 1);
                    $attributeLine->text = $line['text'];
                    $attributeLine->item_order_id = $orderItem->id;
                    $attributeLine->save();
                }
            }
        } catch(\Exception $e) {
            DB::rollback();

            return $e->getMessage();
        }
        DB::commit();
        foreach ($productDetails as $attribute_type) {
            $orderItem = new ItemOrder;
            $orderItem->order_id = $order->id;
            if (array_key_exists('price', $attribute_type)) {
                $orderItem->price = $attribute_type['price'];
            }
            $orderItem->status_id = Status::UNFULFILLED;
            if (array_key_exists('item_type', $attribute_type)) {
                $orderItem->item_type = $attribute_type['item_type'];
            } else {
                $orderItem->item_type = self::ATTRIBUTE;
            }

            $orderItem->attribute_type = $attribute_type['type_name'];
            $orderItem->attribute_name = $attribute_type['name'];
            $orderItem->save();
        }

        return $orderItem;
    }

    public static function saveOrderItemFromContact($request, $order)
    {
        DB::beginTransaction();

        try {
            $orderItem = new ItemOrder;
            $orderItem->order_id = $order->id;
            $orderItem->status_id = Status::UNFULFILLED;
            $orderItem->save();
        } catch(\Exception $e) {
            DB::rollback();

            return $e->getMessage();
        }
        DB::commit();

        return $orderItem;
    }

    public static function saveOrderItemFromVenueContact($request, $order)
    {
        $product = json_decode($request->product, true);
        $lines = json_decode($request->lines, true);
        $savedImage = null;

        try {
            if ($request->editedImage !== 'null') {
                $savedImage = Image::storeBase64Image($request->editedImage);
            }
        } catch(\Exception $e) {
            error_log('Image NOT saved! '.$e->getMessage());
        }

        DB::beginTransaction();

        try {
            $orderItem = new ItemOrder;
            $orderItem->order_id = $order->id;
            $orderItem->price = $product['price'];
            $orderItem->item_type = self::PRODUCT;
            $orderItem->product_name = $product['name'];
            $orderItem->product_id = $product['id'];
            if ($savedImage) {
                $orderItem->image_url = $savedImage->imageurl;
            }
            $orderItem->status_id = Status::UNFULFILLED;
            $orderItem->save();

            foreach ($lines as $key => $line) {
                $attributeLine = new Line;
                $attributeLine->line_number = ($key + 1);
                $attributeLine->text = $line['text'];
                $attributeLine->item_order_id = $orderItem->id;
                $attributeLine->save();
            }
        } catch(\Exception $e) {
            DB::rollback();

            return $e->getMessage();
        }
        DB::commit();

        foreach (json_decode($request->chosenProductSpecs, true) as $attribute_type) {
            $orderItem = new ItemOrder;
            $orderItem->order_id = $order->id;
            if (array_key_exists('price', $attribute_type)) {
                $orderItem->price = $attribute_type['price'];
            }
            $orderItem->status_id = Status::UNFULFILLED;
            if (array_key_exists('item_type', $attribute_type)) {
                $orderItem->item_type = $attribute_type['item_type'];
            } else {
                $orderItem->item_type = self::ATTRIBUTE;
            }
            if (array_key_exists('type_name', $attribute_type)) {
                $orderItem->attribute_type = $attribute_type['type_name'];
                $orderItem->attribute_name = $attribute_type['name'];
            }
            $orderItem->save();
        }

        return $orderItem;
    }

    public static function sendOrderEmail($request, $order, $orderItem)
    {
        $emailItems = [];
        $emailItems['items'] = $orderItem;
        $emailItems['attributes'] = $orderItem->attributes;
        $emailItems['order_id'] = $order->id;
        $emailItems['order_date'] = $request->order_date;
        $emailItems['site_name'] = Site::where('id', $request['site_id'])->pluck('name')->first();
        $emailItems['site_id'] = $request['site_id'];

        $site = Site::find($emailItems['site_id']);

        self::sendOrderNotificationEmail($emailItems, $site);
        self::sendCustomerOrderEamil($request, $order, $site);
    }

    public static function sendOrderNotificationEmail($emailItems, $site)
    {
        if ((! empty($site->primary_contact_name) && ! empty($site->primary_contact_email))) {
            Mail::to($site->primary_contact_email)->send(new NewOrderEmail($site->primary_contact_name, $emailItems));
        } else {
            error_log('Order notificiation not sent to crematorium, both primary contact name and primary contact email must be set'.':Order:'.$emailItems['order_id']);
        }
        $siteUsers = Site::find($site->id)->users()->distinct('email')->get();
        foreach ($siteUsers as $user) {
            if ($user->email_notify_on_new_order == 1) {
                Mail::to($user->email)->send(new NewOrderEmail($user->firstname, $emailItems));
            }
        }
    }

    public static function sendCustomerOrderEamil($request, $order, $site)
    {
        Mail::to($order->email)->send(new CustomerOrderConfirmation($request, $site, $order->order_number));
    }

    public static function updateCustomerDetails($request, $id)
    {
        $order = self::find($id);
        $order->name = $request->name;
        $order->email = $request->email;
        $order->phone_number = $request->phone_number;
        $order->save();

        return $order;
    }

    public static function updateDeceasedDetails($request)
    {
        $deceased = Deceased::find($request->id);
        $deceased->firstname = $request->firstName;
        $deceased->middlename = $request->middleName;
        $deceased->lastname = $request->lastName;
        $deceased->save();

        return $deceased;
    }

    public static function updateProductDetails($request, $id)
    {
        $order = ItemOrder::where('order_id', $id)->where('item_type', '!=', 'product')->where('attribute_type', '!=', 'Predefined Image');
        $order->delete();

        $product = json_decode($request->product, true);

        $supplier = Supplier::find($product['supplier_id']);

        $existingProduct = ItemOrder::updateOrCreate(
            ['order_id' => $id, 'item_type' => 'product'],
            [
                'order_id' => $id,
                'price' => $product['price'],
                'image_url' => null,
                'status_id' => Status::UNFULFILLED,
                'item_type' => self::PRODUCT,
                'product_name' => $product['name'],
                'supplier_name' => $supplier->name,
                'attribute_type' => self::PRODUCT,
                'attribute_name' => $product['name'],
            ]);

        return $existingProduct;
    }

    public static function updateItemOrders($request, $id)
    {
        $order = ItemOrder::where('order_id', $id)->where('item_type', '!=', 'product')->where('attribute_type', '!=', 'Predefined Image');
        $order->delete();

        foreach ($request->request as $key => $value) {
            if ($value !== 'null') {
                switch ($key) {
                    case 'selectedMaterial':
                        $orderItem = new ItemOrder;
                        $material = json_decode($request->selectedMaterial);
                        $orderItem->item_type = self::MATERIAL;
                        $orderItem->price = $material->price;
                        $orderItem->attribute_type = 'Material';
                        $orderItem->attribute_name = $material->type;
                        $orderItem->order_id = $id;
                        $orderItem->status_id = Status::UNFULFILLED;
                        $orderItem->save();
                        break;
                    case 'selectedFontColour':
                        $orderItem = new ItemOrder;
                        $textColour = json_decode($request->selectedFontColour);
                        $orderItem->item_type = self::FONTCOLOUR;
                        $orderItem->attribute_type = 'Font Colour';
                        $orderItem->attribute_name = $textColour->name;
                        $orderItem->order_id = $id;
                        $orderItem->status_id = Status::UNFULFILLED;
                        $orderItem->save();
                        break;
                    case 'selectedFont':
                        $orderItem = new ItemOrder;
                        $font = json_decode($request->selectedFont);
                        $orderItem->item_type = self::FONTFACE;
                        $orderItem->attribute_type = 'Font Face';
                        $orderItem->attribute_name = $font->name;
                        $orderItem->order_id = $id;
                        $orderItem->status_id = Status::UNFULFILLED;
                        $orderItem->save();
                        break;
                    case 'selectedAttributes':
                        $attributes = json_decode($request->selectedAttributes);
                        foreach ($attributes as $attribute) {
                            $orderItem = new ItemOrder;
                            $orderItem->item_type = self::ATTRIBUTE;
                            $orderItem->attribute_type = $attribute->type;
                            $orderItem->attribute_name = $attribute->name;
                            $orderItem->order_id = $id;
                            $orderItem->status_id = Status::UNFULFILLED;
                            $orderItem->save();
                        }
                        break;
                }
            }
        }
    }

    public static function fetchProductAttributes($product, $site)
    {
        $siteProduct = Product::where('id', $product)->where('site_id', $site)->with('site', 'supplier', 'memorialisation', 'images', 'customImages', 'customImages.material', 'customImages.image', 'attributeTypes', 'attributeTypes.attributes', 'materials', 'predefinedImages', 'customImages.customImage', 'customProductTexts', 'customImages.customTextFields', 'fonts', 'textColours')->first();

        return $siteProduct;
    }

    public static function generateOrderNumber()
    {
        $number = mt_rand(1000000, 9999999);

        if (self::orderNumberExists($number)) {
            return self::generateOrderNumber();
        }

        return $number;
    }

    public static function orderNumberExists($number)
    {
        return self::where('order_number', $number)->exists();
    }

    public function deceased()
    {
        return $this->belongsTo(Deceased::class);
    }

    public function noteorder()
    {
        return $this->hasMany(NoteOrder::class);
    }

    public function itemorders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, ItemOrder::class, 'product_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
