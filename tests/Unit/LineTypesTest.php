<?php

namespace Tests\Unit;

use App\Models\CustomImage;
use App\Models\CustomProductText;
use App\Models\Image;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineTypesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Warning: This test will request an image BLOB be saved via S3.
     * S3 during tests will be localstack, but will still take 20+ seconds
     * As this is more than 75% of the total test running time, it is suggested that storage is abstracted.
     * TODO:: remove actual storage being require/used-by this test
     *
     * @group testImageCanBeStored
     *
     * @return void
     */
    public function testImageCanBeStored()
    {
        $customImage = CustomImage::factory()->create();
        $data = json_decode($this->request($customImage));

        $response = (new Image())->storeImage($data);

        $this->assertTrue($response['baseImageId'] > 0);
    }

    /**
     * @group testImageCanBeAssignedToProduct
     *
     * @return void
     */
    public function testImageCanBeAssignedToProduct()
    {
        $customImage = CustomImage::factory()->create();
        $image = Image::factory()->create();

        $data = json_decode($this->request($customImage));
        $response = (new Image())->updateCustomImage($data, $image->id, $customImage->id);

        $this->assertEquals($customImage->id, $response->id);
    }

    /**
     * @group testAdditionalImageCanBeStored
     *
     * @return void
     */
    public function testAdditionalImageCanBeStored()
    {
        $customImage = CustomImage::factory()->create();
        $data = json_decode($this->request($customImage));

        $response = (new Image())->updateAdditionalImage($data, json_decode($customImage));

        $this->assertEquals(1, $response->id);
    }

    /**
     * @group testPerspectivesCanBeStored
     *
     * @return void
     */
    public function testPerspectivesCanBeStored()
    {
        $customImage = CustomImage::factory()->create();
        $data = json_decode($this->request($customImage));

        $response = (new Image())->updatePerspectives($data, json_decode($customImage));

        $this->assertEquals(1, $response->id);
    }

    /**
     * @group testCustomTextFieldsCanBeStored
     *
     * @return void
     */
    public function testCustomTextFieldsCanBeStored()
    {
        $customImage = CustomImage::factory()->create();
        $data = json_decode($this->request($customImage));

        $response = (new Image())->updateCustomTextFields($data, json_decode($customImage));

        $this->assertEquals(1, $response->id);
    }

    private function request($customImage)
    {
        $material = Material::factory()->create();
        $product = Product::factory()->create();
        $customProductText = CustomProductText::factory()->create();
        $request = (object) [
            'file' => 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAABhGlDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1Iw0AcxV9bpSIVFTuIOGSoThZERTtqFYpQIdQKrTqYXPoFTRqSFBdHwbXg4Mdi1cHFWVcHV0EQ/ABxdXFSdJES/5cUWsR4cNyPd/ced+8Af73MVLNjHFA1y0gl4kImuyoEXxFAH/oRw4zETH1OFJPwHF/38PH1LsqzvM/9OXqUnMkAn0A8y3TDIt4gnt60dM77xGFWlBTic+Ixgy5I/Mh12eU3zgWH/TwzbKRT88RhYqHQxnIbs6KhEk8RRxRVo3x/xmWF8xZntVxlzXvyF4Zy2soy12kOI4FFLEGEABlVlFCGhSitGikmUrQf9/APOX6RXDK5SmDkWEAFKiTHD/4Hv7s185MTblIoDnS+2PbHCBDcBRo12/4+tu3GCRB4Bq60lr9SB2KfpNdaWuQI6N0GLq5bmrwHXO4Ag0+6ZEiOFKDpz+eB9zP6piwwcAt0r7m9Nfdx+gCkqavkDXBwCIwWKHvd491d7b39e6bZ3w/gp3LTqY/OYwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cDEBIAB1HqTmgAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAADElEQVQI12NgYGAAAAAEAAEnNCcKAAAAAElFTkSuQmCC',
            'customPath'=>  null,
            'baseImage'=>  'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAABhGlDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1Iw0AcxV9bpSIVFTuIOGSoThZERTtqFYpQIdQKrTqYXPoFTRqSFBdHwbXg4Mdi1cHFWVcHV0EQ/ABxdXFSdJES/5cUWsR4cNyPd/ced+8Af73MVLNjHFA1y0gl4kImuyoEXxFAH/oRw4zETH1OFJPwHF/38PH1LsqzvM/9OXqUnMkAn0A8y3TDIt4gnt60dM77xGFWlBTic+Ixgy5I/Mh12eU3zgWH/TwzbKRT88RhYqHQxnIbs6KhEk8RRxRVo3x/xmWF8xZntVxlzXvyF4Zy2soy12kOI4FFLEGEABlVlFCGhSitGikmUrQf9/APOX6RXDK5SmDkWEAFKiTHD/4Hv7s185MTblIoDnS+2PbHCBDcBRo12/4+tu3GCRB4Bq60lr9SB2KfpNdaWuQI6N0GLq5bmrwHXO4Ag0+6ZEiOFKDpz+eB9zP6piwwcAt0r7m9Nfdx+gCkqavkDXBwCIwWKHvd491d7b39e6bZ3w/gp3LTqY/OYwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cDEBIAB1HqTmgAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAADElEQVQI12NgYGAAAAAEAAEnNCcKAAAAAElFTkSuQmCC',
            'custom_image_id' => $customImage->id,
            'productId' => $product->id,
            'material_id' => $material->id,
            'customImageType' => 'none',
            'shapeDetails' => '{
                "x":100,
                "y":100,
                "height":100,
                "width":100,
                "rotation":90,
                "type":"ecllipse",
                "custom_image_id":3
            }',
            'handles' => '[
                {
                    "x":100,
                    "y":100
                },
                {
                    "x":100,
                    "y":100
                },
                {
                    "x":100,
                    "y":100
                },
                {
                    "x":100,
                    "y":100
                }
            ]',
            'inputs' => '[{
                "type": "fullname",
                "custom_image_id": "2",
                "lineType": "straight",
                "textCanvasLeft": "350",
                "textCanvasTop": "290",
                "fontSize": "20",
                "angle": "0",
                "radius": "200",
                "centerRotation": "-90",
                "arc": "90",
                "letterCount": "25",
                "rectangle": { "x": 150, "y": 100, "width": "400", "height" : "50"},
                "textScale": "1",
                "lineIndex": "0",
                "customProductTextId": "'.$customProductText->id.'"
            }]',
        ];

        return json_encode($request);
    }
}
