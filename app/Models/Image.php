<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as ImageInt;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'imageurl',
    ];

    const PADDING = 15;

    /**
     * @return object
     */
    public static function setFontOptions()
    {
        $fontOptions = function ($font) {
            $font->file(5);
            $font->size(30);
            $font->color('#0000');
            $font->align('center');
            $font->valign('top');
        };

        return $fontOptions;
    }

    public static function getCustomImage($id)
    {
        return CustomImage::where('product_id', $id)->with('customTextFields', 'image', 'additionalImage', 'customImagePerspectiveDetails')->get();
    }

    public static function getCustomImageDetails($id)
    {
        return CustomImage::where('product_id', $id)->with('customTextFields', 'image', 'additionalImage', 'customImagePerspectiveDetails', 'material')->get();
    }

    //TODO: review if this code is ever hit. Appears only Product Thumbnail Image is currently in use
    public static function storeProductImage($request)
    {
        $path = self::storePublicFileToDisk('uploadedimages', $request->file('file'));

        $image = new self;
        $image->imageurl = $path;
        $image->save();

        return $image->id;
    }

    public static function storeMaterialImage($request)
    {
        $path = self::storePublicFileToDisk('uploadedimages', $request->file('image'));

        return $path;
    }

    public static function storePredefinedImage($request)
    {
        $path = self::storePublicFileToDisk('uploadedimages', $request->file('image'));

        return $path;
    }

    //TODO: review what the product thumbnail functionality requires (resizing etc)
    public static function storeProductThumbnailImage($request)
    {
        $path = self::storePublicFileToDisk('uploadedimages/productThumbnails', $request->file('image'));

        $image = new self;
        $image->imageurl = $path;
        $image->save();

        return $path;
    }

    // These relate to category images
    public static function storeMemorialisationSiteImage($request)
    {
        $path = self::storePublicFileToDisk('uploadedimages/memorialisationImages', $request->file('image'));

        $image = new self;
        $image->imageurl = $path;
        $image->save();

        return $path;
    }

    /**
     * @param Request $request
     */
    public static function storeCustomImage($request)
    {
        $storedImage = self::storeImage($request);

        $customImage = self::updateCustomImage($request, $storedImage['baseImageId'], $storedImage['customImageId']);

        $additionalImage = self::updateAdditionalImage($request, $customImage);

        $perspectives = self::updatePerspectives($request, $customImage);

        $customTextFields = self::updateCustomTextFields($request, $customImage);

        return $customImage;
    }

    public static function storeImage($request)
    {
        $encodedImage = str_replace('data:image/png;base64,', '', $request->file);
        $image = ImageInt::make(base64_decode($encodedImage));
        $filenameUpdated = time().'.png';
        $folder = 'storeCustomImage';
        $storagePath = $folder.'/'.$filenameUpdated;
        Storage::disk('s3')->put($storagePath, (string) $image->encode('png'), 'public');

        $customImage = self::updateOrCreate(
            ['id' => $request->customPath],
            [
                'imageurl' => Storage::disk('s3')->url($storagePath),
            ]
        );

        $customImageId = $customImage->id;

        if ($request->baseImage) {
            $baseEncodedImage = str_replace('data:image/png;base64,', '', $request->baseImage);
            $baseImage = ImageInt::make(base64_decode($baseEncodedImage));
            $filenameUpdated = time().'base.png';
            $folder = 'storeCustomImage-Base';
            $storagePathBase = $folder.'/'.$filenameUpdated;
            Storage::disk('s3')->put($storagePathBase, (string) $baseImage->encode('png'), 'public');

            $baseImage = new self;
            $baseImage->imageurl = Storage::disk('s3')->url($storagePathBase);
            $baseImage->save();

            $baseImageId = $baseImage->id;
        } else {
            $baseImageId = $request->basePath;
            $customImageId = $request->customPath;
        }

        return ['baseImageId' => $baseImageId, 'customImageId' => $customImageId];
    }

    public static function updateCustomImage($request, $baseImageId, $customImageId)
    {
        $customImage = CustomImage::updateOrCreate(
            ['id' => $request->custom_image_id],
            [
                'product_id' => $request->productId,
                'image_id' => $baseImageId,
                'custom_image_id' => $customImageId,
                'material_id' => $request->material_id,
                'layout' => $request->customImageType,
            ]
        );

        return $customImage;
    }

    public static function updateAdditionalImage($request, $customImage)
    {
        $shape = json_decode($request->shapeDetails);
        if ($shape) {
            $additionalImage = AdditionalImage::updateOrCreate(
                ['custom_image_id' => $request->custom_image_id],
                [
                    'left' => $shape->x,
                    'top' => $shape->y,
                    'height' => $shape->height,
                    'width' => $shape->width,
                    'rotation' => $shape->rotation,
                    'type' => $shape->type,
                    'custom_image_id' => $customImage->id,
                ]
            );

            return $additionalImage;
        }
    }

    public static function updatePerspectives($request, $customImage)
    {
        $handles = json_decode($request->handles);
        if ($handles) {
            $perspectiveText = CustomTextPerspective::updateOrCreate(
                ['custom_image_id' => $request->custom_image_id],
                [
                    'custom_image_id' => $customImage->id,
                    'ax' => $handles[0]->x,
                    'ay' => $handles[0]->y,
                    'bx' => $handles[1]->x,
                    'by' => $handles[1]->y,
                    'cx' => $handles[2]->x,
                    'cy' => $handles[2]->y,
                    'dx' => $handles[3]->x,
                    'dy' => $handles[3]->y,
                ]
            );

            return $perspectiveText;
        }
    }

    public static function updateCustomTextFields($request, $customImage)
    {
        $line = CustomTextField::where('custom_image_id', ($request->custom_image_id));
        $line->delete();

        if (json_decode($request->inputs)) {
            foreach (json_decode($request->inputs) as $input) {
                if ($input) {
                    $customTextField = CustomTextField::updateOrCreate(
                        ['custom_image_id' => $request->custom_image_id, 'type' => $input->type],
                        [
                            'type' => $input->type,
                            'custom_image_id' => $customImage->id,
                            'lineType' => $input->lineType,
                            'left' => $input->textCanvasLeft,
                            'top' => $input->textCanvasTop,
                            'fontSize' => $input->fontSize,
                            'angle' => $input->angle,
                            'radius' => $input->radius,
                            'centerRotation' => $input->centerRotation,
                            'arc' => $input->arc,
                            'letterCount' => $input->letterCount,
                            'rectangleX' => $input->rectangle->x,
                            'rectangleY' => $input->rectangle->y,
                            'rectangleWidth' => $input->rectangle->width,
                            'rectangleHeight' => $input->rectangle->height,
                            'rectangleTextScale' => $input->textScale,
                            'line_index' => $input->lineIndex,
                            'custom_product_text_id' => $input->customProductTextId,
                        ]
                    );
                }
            }

            return $customTextField;
        }
    }

    public static function updateArticle($request, $id)
    {
        $snippetLength = Settings::getSetting('bereavement_snippet_length');
        if ($snippetLength == null) {
            $snippetLength = 40; // default if not supplied by app settings
        }
        $truncatedArticle = \Illuminate\Support\Str::words(strip_tags($request->article), $snippetLength);
        if ($id == null) {
            $article = new Article;
        } else {
            $article = Article::find($id);
        }
        $article->title = $request->title;
        $article->description = ($request->has('blurb') && ! empty($request->blurb)) ? $request->blurb : $truncatedArticle;
        $article->article = $request->article;

        if ($request->hasFile('image')) {
            $path = self::storePublicFileToDisk('uploadedimages/articleImages', $request->file('image'));

            $image = new self;
            $image->imageurl = $path;
            $image->save();

            $article->image = $path;
        }
        $article->url = $request->url;
        $article->save();

        return response()->json(['message' => 'Article Updated Successfully'], 200);
    }

    public static function storeSiteLogo($request)
    {
        if ($request->hasFile('logo_file')) {
            $path = self::storePublicFileToDisk('uploadedimages/sitelogos', $request->file('logo_file'));

            $image = new self;
            $image->imageurl = $path;
            $image->save();

            return $image->id;
        }
    }

    public static function storeBase64Image($productImage)
    {
        $folder = 'uploadedimages';

        return self::storeBase64ImageInFolder($productImage, $folder);
    }

    public static function storeBase64ImageInFolder($base64Image, $folder)
    {
        $encodedImage = str_replace('data:image/png;base64,', '', $base64Image);
        $image = ImageInt::make(base64_decode($encodedImage));
        $filenameUpdated = time().'.png';
        $storagePath = $folder.'/'.$filenameUpdated;
        Storage::disk('s3')->put($storagePath, (string) $image->encode('png'), 'public');

        $imageToSave = new self;
        $imageToSave->imageurl = Storage::disk('s3')->url($storagePath);
        $imageToSave->save();

        return $imageToSave;
    }

    public function product()
    {
        $this->belongsTo(ProductSite::class);
    }

    public function itemOrder()
    {
        $this->belongsTo(ItemOrder::class);
    }

    public function customImage()
    {
        return $this->hasOne(CustomImage::class);
    }

    public function siteStyle()
    {
        $this->hasOne(SiteStyles::class);
    }

    private static function storePublicFileToDisk($folder, $file)
    {
        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filenameUpdated = $filename.time().'.'.$extension;
        $path = Storage::disk('s3')->putFileAs($folder, $file, $filenameUpdated, 'public');
        $url = Storage::disk('s3')->url($path);

        return $url;
    }
}
