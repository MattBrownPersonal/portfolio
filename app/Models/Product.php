<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'supplier_id',
        'name',
        'description',
        'short_description',
    ];

    public static function getFeaturedProducts($site_code)
    {
        if (is_numeric($site_code)) {
            $siteId = Deceased::where('code', $site_code)->first()->site_id;
        } else {
            $siteId = Site::where('name', str_replace('-', ' ', $site_code))->first()->id;
        }

        return self::where('site_id', $siteId)->where('featured', 1)->where('draft', 0)->get();
    }

    public static function validateInput($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|max:255',
            'POA' => 'required|boolean',
            'supplier' => 'required|integer',
            'category' => 'integer',
            'description' => 'required|string',
        ]);
    }

    public static function storeSiteMemorialisationProduct($request)
    {
        $product = new self;
        $product->supplier_id = 1;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->shortDescription;
        $product->price = $request->price;
        $product->POA = $request->POA;
        $product->memorialisation_id = $request->memorialisation;
        $product->site_id = $request->site;
        $product->sku = 'SKU'.random_int(10000, 99999);
        $product->save();

        return $product->id;
    }

    public static function storeNewSiteProduct($request)
    {
        $product = new self;
        $product->supplier_id = 1;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->shortDescription;
        $product->price = $request->price;
        $product->POA = ($request->POA == 'true') ? 1 : 0;
        if ($request->selectedCategory !== 'null') {
            $product->memorialisation_id = $request->selectedCategory;
        }
        $product->site_id = $request->site;
        $product->sku = 'SKU'.random_int(10000, 99999);
        $product->save();

        return $product->id;
    }

    /**
     * @param $id
     * @static
     */
    public static function getAllProducts($id)
    {
        return self::with('memorialisation', 'site')->with('supplier')->find($id);
    }

    /**
     * @param Request $request
     */
    public static function storeNewProduct($request)
    {
        $validator = self::validateInput($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $product = new self;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->POA = $request->POA;
        $product->supplier_id = $request->supplier;
        $product->memorialisations_id = $request->category;
        $product->description = $request->description;
        $product->memorialisation_id = $request->memorialisation;
        $product->sku = 'SKU'.random_int(10000, 99999);
        $product->save();

        return response()->json(['message' => 'Product Added Successfully'], 200);
    }

    /**
     * @param Request $request
     */
    public static function updateProduct($request, $id)
    {
        $validator = self::validateInput($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $product = self::findOrFail($id);

        if ($request->type == 'attribute_types') {
            $product->attributeTypes()->sync($request->attributeTypes);

            return response()->json(['message' => 'Product Saved Successfully'], 200);
        } elseif ($request->type == 'imageUpload') {
            $imageId = Image::storeProductImage($request);
            $product->images()->attach($imageId);

            return $image;
        }
        if ($request->existingImage !== 'true') {
            $path = Image::storeProductThumbnailImage($request);

            $product->thumbnail_image = $path;
        }

        $product->supplier_id = $request->supplier;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->shortDescription;
        $product->memorialisation_id = $request->memorialisation;
        $product->price = $request->price;
        $product->POA = $request->POA;
        $product->site_id = $request->site_id;
        if ($request->category !== 'null') {
            $product->memorialisation_id = $request->category;
        }
        $product->featured = $request->featured == false ? 0 : 1;
        $product->saved = 0;
        $product->sku = $request->sku;
        $product->save();

        return response()->json(['message' => 'Product Saved Successfully'], 200);
    }

    public static function copyProduct($request)
    {
        $oldProduct = self::findOrFail($request->product_id);

        $newProduct = self::saveProductCopy($request, $oldProduct);

        $newProductMaterial = self::saveProductMaterials($oldProduct, $newProduct);

        $productAttributes = self::saveProductAttributes($oldProduct, $newProduct);

        $oldcustomProductTextsArray = self::saveProductTexts($oldProduct, $newProduct);

        $predefinedImages = self::savePredefinedImages($oldProduct, $newProduct);

        $customImages = self::saveCustomImages($oldcustomProductTextsArray, $oldProduct, $newProduct);

        $fonts = self::saveFonts($request, $newProduct);

        $customTextColours = self::saveCustomTextColours($oldProduct, $newProduct);

        return $newProduct;
    }

    public static function saveProductCopy($request, $oldProduct)
    {
        $newProduct = new self;
        $newProduct->supplier_id = $oldProduct->supplier_id;
        $newProduct->name = $oldProduct->name;
        $newProduct->description = $oldProduct->description;
        $newProduct->price = $oldProduct->price;
        $newProduct->POA = $oldProduct->POA;
        if ($request->category_id !== 'null') {
            $newProduct->memorialisation_id = $request->category_id;
        }
        $newProduct->sku = $oldProduct->sku;
        $newProduct->site_id = $request->site_id;
        $newProduct->thumbnail_image = $oldProduct->thumbnail_image;
        $newProduct->short_description = $oldProduct->short_description;
        $newProduct->save();

        return $newProduct;
    }

    public static function saveProductMaterials($oldProduct, $newProduct)
    {
        $oldProductMaterials = Material::where('product_id', $oldProduct->id)->get();
        if (! $oldProductMaterials->isEmpty()) {
            foreach ($oldProductMaterials as $oldProductMaterial) {
                $newProductMaterial = new Material;
                $newProductMaterial->type = $oldProductMaterial->type;
                $newProductMaterial->imageurl = $oldProductMaterial->imageurl;
                $newProductMaterial->product_id = $newProduct->id;
                $newProductMaterial->price = $oldProductMaterial->price;
                $newProductMaterial['visible'] = $oldProductMaterial['visible'];
                $newProductMaterial->save();
            }

            return $newProductMaterial;
        }
    }

    public static function saveProductAttributes($oldProduct, $newProduct)
    {
        $oldAttributeTypes = AttributeTypes::where('product_id', $oldProduct->id)->with('allAttributes')->get();

        if (! $oldAttributeTypes->isEmpty()) {
            foreach ($oldAttributeTypes as $oldAttributeType) {
                $newSiteAttributeType = new AttributeTypes;
                $newSiteAttributeType->name = $oldAttributeType->name;
                $newSiteAttributeType->product_id = $newProduct->id;
                $newSiteAttributeType['visible'] = $oldAttributeType['visible'];
                $newSiteAttributeType->save();

                foreach ($oldAttributeType->allAttributes as $attribute) {
                    $newSiteAttribute = new Attributes;
                    $newSiteAttribute->attribute_types_id = $newSiteAttributeType->id;
                    $newSiteAttribute->name = $attribute->name;
                    $newSiteAttribute->price = $attribute->price;
                    $newSiteAttribute['visible'] = $attribute['visible'];
                    $newSiteAttribute->save();
                }
            }
            if (! $oldAttributeType->allAttributes->isEmpty()) {
                return [$newSiteAttributeType, $newSiteAttribute];
            } else {
                return [$newSiteAttributeType];
            }
        }
    }

    public static function saveProductTexts($oldProduct, $newProduct)
    {
        $oldcustomProductTexts = CustomProductText::where('product_id', $oldProduct->id)->get();

        $oldcustomProductTextsArray = [];

        foreach ($oldcustomProductTexts as $oldcustomProductText) {
            $newCustomProductText = new CustomProductText;
            $newCustomProductText->product_id = $newProduct->id;
            $newCustomProductText->line_types = $oldcustomProductText->line_types;
            $newCustomProductText->custom_index = $oldcustomProductText->custom_index;
            $newCustomProductText->is_custom = $oldcustomProductText->is_custom;
            $newCustomProductText->custom_message_text = $oldcustomProductText->custom_message_text;
            $newCustomProductText->removed = $oldcustomProductText->removed;
            $newCustomProductText->order_index = $oldcustomProductText->order_index;
            $newCustomProductText->save();
            $oldcustomProductTextsArray[$oldcustomProductText->id] = $newCustomProductText->id;
        }

        return $oldcustomProductTextsArray;
    }

    public static function savePredefinedImages($oldProduct, $newProduct)
    {
        $oldPredefinedImages = PredefinedImage::where('product_id', $oldProduct->id)->get();

        if (! $oldPredefinedImages->isEmpty()) {
            foreach ($oldPredefinedImages as $oldPredefinedImage) {
                $newPredefinedImage = new PredefinedImage;
                $newPredefinedImage->name = $oldPredefinedImage->name;
                $newPredefinedImage->imageurl = $oldPredefinedImage->imageurl;
                $newPredefinedImage->product_id = $newProduct->id;
                $newPredefinedImage['visible'] = $oldPredefinedImage['visible'];
                $newPredefinedImage->price = $oldPredefinedImage->price;
                $newPredefinedImage->save();
            }

            return $newPredefinedImage;
        }
    }

    public static function saveCustomImages($oldcustomProductTextsArray, $oldProduct, $newProduct)
    {
        $oldCustomImages = CustomImage::where('product_id', $oldProduct->id)->get();
        if ($oldCustomImages[0]->material) {
            $newMaterial = Material::where('product_id', $newProduct->id)->where('type', $oldCustomImages[0]->material->type)->first();
        }

        foreach ($oldCustomImages as $oldCustomImage) {
            $newCustomImage = new CustomImage;
            $newCustomImage->product_id = $newProduct->id;
            $newCustomImage->image_id = $oldCustomImage->image_id;
            if ($oldCustomImages[0]->material) {
                $newCustomImage->material_id = $newMaterial->id;
            }
            $newCustomImage->custom_image_id = $oldCustomImage->custom_image_id;
            $newCustomImage->layout = $oldCustomImage->layout;
            $newCustomImage->save();

            $oldAdditionalImage = AdditionalImage::where('custom_image_id', $oldCustomImages[0]->id)->first();

            if ($oldAdditionalImage) {
                $newAdditionalImage = new AdditionalImage;
                $newAdditionalImage->left = $oldAdditionalImage->left;
                $newAdditionalImage->top = $oldAdditionalImage->top;
                $newAdditionalImage->width = $oldAdditionalImage->width;
                $newAdditionalImage->height = $oldAdditionalImage->height;
                $newAdditionalImage->rotation = $oldAdditionalImage->rotation;
                $newAdditionalImage->type = $oldAdditionalImage->type;
                $newAdditionalImage->custom_image_id = $newCustomImage->id;
                $newAdditionalImage->save();
            }

            if ($oldCustomImages->isNotEmpty()) {
                $oldcustomTextFields = CustomTextField::where('custom_image_id', $oldCustomImages[0]->id)->get();

                foreach ($oldcustomTextFields as $oldcustomTextField) {
                    $newCustomImageTextField = new CustomTextField;
                    $newCustomImageTextField->type = $oldcustomTextField->type;
                    $newCustomImageTextField->custom_image_id = $newCustomImage->id;
                    $newCustomImageTextField->custom_product_text_id = $oldcustomProductTextsArray[$oldcustomTextField->custom_product_text_id];
                    $newCustomImageTextField->lineType = $oldcustomTextField->lineType;
                    $newCustomImageTextField->left = $oldcustomTextField->left;
                    $newCustomImageTextField->top = $oldcustomTextField->top;
                    $newCustomImageTextField->fontSize = $oldcustomTextField->fontSize;
                    $newCustomImageTextField->angle = $oldcustomTextField->angle;
                    $newCustomImageTextField->radius = $oldcustomTextField->radius;
                    $newCustomImageTextField->centerRotation = $oldcustomTextField->centerRotation;
                    $newCustomImageTextField->arc = $oldcustomTextField->arc;
                    $newCustomImageTextField->letterCount = $oldcustomTextField->letterCount;
                    $newCustomImageTextField->highlight = $oldcustomTextField->highlight;
                    $newCustomImageTextField->shadow = $oldcustomTextField->shadow;
                    $newCustomImageTextField->fill = $oldcustomTextField->fill;
                    $newCustomImageTextField->line_index = $oldcustomTextField->line_index;
                    $newCustomImageTextField->rectangleX = $oldcustomTextField->rectangleX;
                    $newCustomImageTextField->rectangleY = $oldcustomTextField->rectangleY;
                    $newCustomImageTextField->rectangleWidth = $oldcustomTextField->rectangleWidth;
                    $newCustomImageTextField->rectangleHeight = $oldcustomTextField->rectangleHeight;
                    $newCustomImageTextField->rectangleTextScale = $oldcustomTextField->rectangleTextScale;
                    $newCustomImageTextField->save();
                }
            }

            if ($oldCustomImages->isNotEmpty()) {
                $oldCustomTextPerspectives = CustomTextPerspective::where('custom_image_id', $oldCustomImages[0]->id)->get();

                foreach ($oldCustomTextPerspectives as $oldCustomTextPerspective) {
                    $newCustomImageTextPerspective = new CustomTextPerspective;
                    $newCustomImageTextPerspective->custom_image_id = $newCustomImage->id;
                    $newCustomImageTextPerspective->ax = $oldCustomTextPerspective->ax;
                    $newCustomImageTextPerspective->ay = $oldCustomTextPerspective->ay;
                    $newCustomImageTextPerspective->bx = $oldCustomTextPerspective->bx;
                    $newCustomImageTextPerspective->by = $oldCustomTextPerspective->by;
                    $newCustomImageTextPerspective->cx = $oldCustomTextPerspective->cx;
                    $newCustomImageTextPerspective->cy = $oldCustomTextPerspective->cy;
                    $newCustomImageTextPerspective->dx = $oldCustomTextPerspective->dx;
                    $newCustomImageTextPerspective->dy = $oldCustomTextPerspective->dy;
                    $newCustomImageTextPerspective->save();
                }
            }
        }
    }

    public static function saveFonts($request, $newProduct)
    {
        $oldProductWithFonts = self::where('id', $request->product_id)->with('fonts')->first();

        foreach ($oldProductWithFonts->fonts as $font) {
            $fontModel = Font::where('id', $font->id)->first();
            $fontModel->products()->attach($newProduct->id);
        }
    }

    public static function saveCustomTextColours($oldProduct, $newProduct)
    {
        $oldCustomTextColours = ProductTextColour::where('product_id', $oldProduct->id)->get();

        foreach ($oldCustomTextColours as $oldCustomTextColour) {
            $newCustomTextColours = new ProductTextColour;
            $newCustomTextColours->product_id = $newProduct->id;
            $newCustomTextColours->colour = $oldCustomTextColour->colour;
            $newCustomTextColours->name = $oldCustomTextColour->name;
            $newCustomTextColours->save();
        }

        return $oldCustomTextColours;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function memorialisation()
    {
        return $this->belongsTo(Memorialisations::class);
    }

    public function ItemOrder()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    public function customImages()
    {
        return $this->hasMany(CustomImage::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function productSites()
    {
        return $this->hasMany(ProductSite::class);
    }

    public function attributeTypes()
    {
        return $this->hasMany(AttributeTypes::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function textColours()
    {
        return $this->hasMany(ProductTextColour::class);
    }

    public function predefinedImages()
    {
        return $this->hasMany(PredefinedImage::class);
    }

    public function customProductTexts()
    {
        return $this->hasMany(CustomProductText::class);
    }

    public function fonts()
    {
        return $this->belongsToMany(Font::class);
    }
}
