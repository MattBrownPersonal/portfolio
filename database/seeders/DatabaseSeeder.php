<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Attributes;
use App\Models\AttributeTypes;
use App\Models\CustomImage;
use App\Models\CustomProductText;
use App\Models\CustomTextField;
use App\Models\Deceased;
use App\Models\Image;
use App\Models\Material;
use App\Models\MemorialisationSite;
use App\Models\PredefinedImage;
use App\Models\Role;
use App\Models\Settings;
use App\Models\Site;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $westerlySiteId = 145;

        $roles = [
            ['id' => 1, 'name' => 'superuser'],
            ['id' => 2, 'name' => 'staff'],
            ['id' => 3, 'name' => 'siteadmin'],
            ['id' => 4, 'name' => 'sitestaff'],
        ];

        $admin = [
            'firstname' => 'Admin',
            'lastname' => 'Person',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $vivediaStaff = [
            'firstname' => 'Vivedia',
            'lastname' => 'Staff',
            'email' => 'staff@vivedia.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $siteAdmin = [
            'firstname' => 'Site',
            'lastname' => 'Admin',
            'email' => 'admin@site.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $siteStaff = [
            'firstname' => 'Site',
            'lastname' => 'Staff',
            'email' => 'staff@site.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $colchesterUser = [
            'firstname' => 'Penny',
            'lastname' => 'Colchester',
            'email' => 'penny@colchester.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $westerleighUser = [
            'firstname' => 'Admin',
            'lastname' => 'westerleigh',
            'email' => 'admin@westerleigh.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $robDavisAdmin = [
            'firstname' => 'Rob',
            'lastname' => 'Davis',
            'email' => 'rob.davis@thecurve.io',
            'email_verified_at' => now(),
            'password' => '$2y$10$iS9phweR.Szb59cotzQXLOL0t3v7yxSxB5S.YD9sX6fYZN.9QhXqK',
            'remember_token' => Str::random(10),
        ];

        $settings = [
            ['id' => 1, 'key' => 'videdia_email', 'value' => 'info@vivedia.com', 'site_id' => null],
            ['id' => 2, 'key' => 'new_vivedia_staff_email_subject_text', 'value' => 'Welcome', 'site_id' => null],
            ['id' => 3, 'key' => 'new_vivedia_staff_email_body', 'value' => 'You are receiving this email as you have been added as a new member of staff for Vivedia. Please click the below button to set your password', 'site_id' => null],
            ['id' => 4, 'key' => 'applicant_code_email_subject_text', 'value' => 'Your personalised shop', 'site_id' => null],
            ['id' => 5, 'key' => 'applicant_code_email_body', 'value' => 'You have received this email as a way to access your personalised shop. Plesae click the below button to begin', 'site_id' => null],
            ['id' => 6, 'key' => 'applicant_query_subject_text', 'value' => 'New Query', 'site_id' => null],
            ['id' => 7, 'key' => 'applicant_query_email_body', 'value' => 'You have received a new query.', 'site_id' => null],
            ['id' => 8, 'key' => 'homepage_remember_copy', 'value' => 'A customised tribute is a thoughtful and caring way to celebrate the life of your loved one and create a place of commemoration for family and friends.', 'site_id' => null],
            ['id' => 9, 'key' => 'homepage_bereavement_copy', 'value' => 'Coping with grief can take many forms. These articles cover a range of bereavement topics and provide details of grief and bereavement support groups and organisations.', 'site_id' => null],
            ['id' => 10, 'key' => 'welcome_copy', 'value' => 'Please enter your privacy key for bereavement support and memorial options.', 'site_id' => null],
            ['id' => 11, 'key' => 'bereavement_landing_copy', 'value' => 'Coping with grief can take many forms. These articles cover a range of bereavement topics and provide details of grief and bereavement support groups and organisations.', 'site_id' => null],
            ['id' => 12, 'key' => 'category_copy', 'value' => 'There are many ways of remembering a loved one. Choose a memorial and select meaningful words to personalise your tribute.', 'site_id' => null],
            ['id' => 13, 'key' => 'buy_now_copy', 'value' => "When you're happy with your personalised memorial design, simply place your order. Once received, we will be in touch as soon as possible. Alternatively, if you would like to confirm the exact placement of your memorial personalisation or if you have any special requests, you can call us on after placing your order.", 'site_id' => null],
            ['id' => 14, 'key' => 'share_friends_family', 'value' => 'You can email or download a copy of your bespoke memorial to share with friends and family.', 'site_id' => null],
            ['id' => 15, 'key' => 'share_team', 'value' => 'Share a copy of your personalised memorial and one of our team will be in touch to discuss your design in more detail. Or, if you want to discuss additional personalisation options, please contact us on ', 'site_id' => null],
            ['id' => 16, 'key' => 'email_greeting', 'value' => 'Hi friend,', 'site_id' => null],
            ['id' => 17, 'key' => 'email_content', 'value' => "We know how difficult the loss of a loved one can be. So, as part of our service, we've written a range of bereavement articles to support you. We've also created a dedicated space for you to personalise a lasting memorial. This can be an important part of the grieving process. Not only will your tribute help you remember your loved one, but it will create a place for friends and family to visit and reminisce. You can access the support articles and view your dedicated memorial page here. Your personalised memorial page will be accessible at any time during the next three years. Simply keep hold of this email until you're ready. Or contact us at a later date.", 'site_id' => null],
            ['id' => 18, 'key' => 'buy_now_text', 'value' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorem perspiciatis, officiis consequuntur maiores quos temporibus similique aliquam', 'site_id' => $westerlySiteId],
            ['id' => 19, 'key' => 'share_text', 'value' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorem perspiciatis, officiis consequuntur maiores quos temporibus similique aliquam', 'site_id' => $westerlySiteId],
            ['id' => 20, 'key' => 'next_steps', 'value' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorem perspiciatis, officiis consequuntur maiores quos temporibus similique aliquam', 'site_id' => $westerlySiteId],
        ];
        $statuses = [
            ['id' => 1, 'status' => 'Unfulfilled'],
            ['id' => 2, 'status' => 'Processing'],
            ['id' => 3, 'status' => 'Fulfilled'],
            ['id' => 4, 'status' => 'Cancelled'],
        ];

        $attributeTypes = [
            ['id' => 1, 'name' => 'Size', 'product_id' => 16],
            ['id' => 2, 'name' => 'Fixing Type', 'product_id' => 16],
        ];

        $attributes = [
            ['id' => 1, 'attribute_types_id' => 1, 'name' => 'Big', 'price' => 50],
            ['id' => 2, 'attribute_types_id' => 1, 'name' => 'Bigger', 'price' => 60],
            ['id' => 3, 'attribute_types_id' => 1, 'name' => 'Biggest', 'price' => 70],
            ['id' => 4, 'attribute_types_id' => 2, 'name' => 'Brass', 'price' => 60],
            ['id' => 5, 'attribute_types_id' => 2, 'name' => 'Iron', 'price' => 70],
            ['id' => 6, 'attribute_types_id' => 2, 'name' => 'Steel', 'price' => 80],
        ];

        $images = [
            ['id' => 1, 'imageurl' => '/uploadedimages/grey-stone.jpg'],
            ['id' => 2, 'imageurl' => '/uploadedimages/1654790026base.png'],
            ['id' => 3, 'imageurl' => '/uploadedimages/sitelogos/cremlogo.png'],
        ];

        $customImages = [
            ['id' => 1, 'product_id' => 16, 'image_id' => 1, 'material_id' => 1, 'custom_image_id' => 1, 'layout' => 'custom'],
        ];

        $customTextPerspectives = [
            ['id' => 1, 'custom_image_id' => 1, 'ax' => 174, 'ay' => 227, 'bx' => 491, 'by' => 136, 'cx' => 281, 'cy' => 388, 'dx' => 605, 'dy' => 285],
        ];

        $memorialisationSites = [
            ['id' => 1, 'memorialisation_id' => 2, 'site_id' => $westerlySiteId, 'image' => '/memorialisation/urn.jpg', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse elit elit, commodo a diam eu, rhoncus efficitur libero. Aliquam interdum bibendum posuere. Pellentesque vel felis quis neque tempus convallis in sit amet lectus. Phasellus eu arcu lobortis, viverra elit vel, porta metus. '],
            ['id' => 2, 'memorialisation_id' => 3, 'site_id' => $westerlySiteId, 'image' => '/memorialisation/book.jpg', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse elit elit, commodo a diam eu, rhoncus efficitur libero. Aliquam interdum bibendum posuere. Pellentesque vel felis quis neque tempus convallis in sit amet lectus. Phasellus eu arcu lobortis, viverra elit vel, porta metus. '],
            ['id' => 3, 'memorialisation_id' => 4, 'site_id' => $westerlySiteId, 'image' => '/memorialisation/garden.jpg', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse elit elit, commodo a diam eu, rhoncus efficitur libero. Aliquam interdum bibendum posuere. Pellentesque vel felis quis neque tempus convallis in sit amet lectus. Phasellus eu arcu lobortis, viverra elit vel, porta metus. '],
        ];

        $customProductTexts = [
            ['id' => 1, 'product_id' => 16, 'line_types' => 'fullname'],
            ['id' => 2, 'product_id' => 16, 'line_types' => 'date of birth'],
            ['id' => 3, 'product_id' => 16, 'line_types' => 'date of death'],
            ['id' => 4, 'product_id' => 16, 'line_types' => 'custom'],
        ];

        $customTextFields = [
            ['id' => 1, 'type' => 'fullname', 'custom_image_id' => 1, 'custom_product_text_id' => 1, 'lineType' => 'straight', 'left' => 350, 'top' => 174, 'fontSize' => 20, 'angle' => 0, 'radius' => 1000, 'centerRotation' => 0, 'arc' => 2,
                'rectangleX' => 215,
                'rectangleY' => 50,
                'rectangleWidth' => 230,
                'rectangleHeight' => 30,
                'rectangleTextScale' => 1,
                'letterCount' => 25, ],
            ['id' => 2, 'type' => 'date of birth', 'custom_image_id' => 1, 'custom_product_text_id' => 2, 'lineType' => 'straight', 'left' => 350, 'top' => 290, 'fontSize' => 20, 'angle' => 0, 'radius' => 1000, 'centerRotation' => 0, 'arc' => 2,
                'rectangleX' => 215,
                'rectangleY' => 85,
                'rectangleWidth' => 230,
                'rectangleHeight' => 30,
                'rectangleTextScale' => 1,
                'letterCount' => 25, ],
            ['id' => 3, 'type' => 'date of death', 'custom_image_id' => 1, 'custom_product_text_id' => 3, 'lineType' => 'straight', 'left' => 350, 'top' => 290, 'fontSize' => 20, 'angle' => 0, 'radius' => 1000, 'centerRotation' => 0, 'arc' => 2,
                'rectangleX' => 215,
                'rectangleY' => 110,
                'rectangleWidth' => 230,
                'rectangleHeight' => 30,
                'rectangleTextScale' => 1,
                'letterCount' => 25, ],
            ['id' => 4, 'type' => 'custom', 'custom_image_id' => 1, 'custom_product_text_id' => 4, 'lineType' => 'straight', 'left' => 350, 'top' => 255, 'fontSize' => 20, 'angle' => 0, 'radius' => 1000, 'centerRotation' => 0, 'arc' => 2,
                'rectangleX' => 215,
                'rectangleY' => 150,
                'rectangleWidth' => 230,
                'rectangleHeight' => 30,
                'rectangleTextScale' => 1,
                'letterCount' => 25, ],
        ];

        $materials = [
            ['id' => 1, 'type' => 'Brushed Silver', 'imageurl' => '/memorialisation/brushsilver.jpg', 'product_id' => 16],
            ['id' => 2, 'type' => 'Dark Grey Stone', 'imageurl' => '/memorialisation/darkgreystone.jpg', 'product_id' => 16],
            ['id' => 3, 'type' => 'Gold', 'imageurl' => '/memorialisation/gold.jpg', 'product_id' => 16],
            ['id' => 4, 'type' => 'Granite', 'imageurl' => '/memorialisation/granite.jpg', 'product_id' => 16],
        ];

        $articles = [
            ['id' => 1, 'title' => 'Suspendisse iaculis enim at pellentesque euismod. 1', 'image' => '/memorialisation/granite.jpg', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse dapibus placerat turpis, et mollis mi elementum non. Vestibulum et ullamcorper lectus. Duis imperdiet turpis diam, quis malesuada odio ultricies at. Mauris congue nibh a eros faucibus condimentum. Donec tempus leo justo, ut mauris.', 'article' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tortor libero, tincidunt ac ornare ac, vehicula id ligula. Vestibulum arcu lectus, laoreet sed nulla sit amet, eleifend bibendum mi. Nam dignissim non enim quis consequat. Suspendisse potenti. Suspendisse vulputate a nisi id viverra. Aliquam iaculis erat orci, sit amet commodo nunc dignissim porta. In hac habitasse platea dictumst. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur metus lectus, varius non pretium ac, volutpat nec dui. Aenean sagittis pellentesque est, quis mollis purus vehicula quis. Sed scelerisque ultrices sapien, ac rhoncus quam condimentum vitae. Vestibulum nisl tortor, consectetur eu nunc sit amet, efficitur viverra arcu. Duis magna mauris, sodales eu cursus eu, blandit vel dui. Maecenas eget ipsum leo. Suspendisse eu tortor in tellus finibus tincidunt ut vel nunc. Nam luctus eros eget consectetur consectetur.'],
            ['id' => 2, 'title' => 'Suspendisse iaculis enim at pellentesque euismod. 2', 'image' => '/memorialisation/granite.jpg', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse dapibus placerat turpis, et mollis mi elementum non. Vestibulum et ullamcorper lectus. Duis imperdiet turpis diam, quis malesuada odio ultricies at. Mauris congue nibh a eros faucibus condimentum. Donec tempus leo justo, ut mauris.', 'article' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tortor libero, tincidunt ac ornare ac, vehicula id ligula. Vestibulum arcu lectus, laoreet sed nulla sit amet, eleifend bibendum mi. Nam dignissim non enim quis consequat. Suspendisse potenti. Suspendisse vulputate a nisi id viverra. Aliquam iaculis erat orci, sit amet commodo nunc dignissim porta. In hac habitasse platea dictumst. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur metus lectus, varius non pretium ac, volutpat nec dui. Aenean sagittis pellentesque est, quis mollis purus vehicula quis. Sed scelerisque ultrices sapien, ac rhoncus quam condimentum vitae. Vestibulum nisl tortor, consectetur eu nunc sit amet, efficitur viverra arcu. Duis magna mauris, sodales eu cursus eu, blandit vel dui. Maecenas eget ipsum leo. Suspendisse eu tortor in tellus finibus tincidunt ut vel nunc. Nam luctus eros eget consectetur consectetur.'],
            ['id' => 3, 'title' => 'Suspendisse iaculis enim at pellentesque euismod. 3', 'image' => '/memorialisation/granite.jpg', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse dapibus placerat turpis, et mollis mi elementum non. Vestibulum et ullamcorper lectus. Duis imperdiet turpis diam, quis malesuada odio ultricies at. Mauris congue nibh a eros faucibus condimentum. Donec tempus leo justo, ut mauris.', 'article' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tortor libero, tincidunt ac ornare ac, vehicula id ligula. Vestibulum arcu lectus, laoreet sed nulla sit amet, eleifend bibendum mi. Nam dignissim non enim quis consequat. Suspendisse potenti. Suspendisse vulputate a nisi id viverra. Aliquam iaculis erat orci, sit amet commodo nunc dignissim porta. In hac habitasse platea dictumst. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur metus lectus, varius non pretium ac, volutpat nec dui. Aenean sagittis pellentesque est, quis mollis purus vehicula quis. Sed scelerisque ultrices sapien, ac rhoncus quam condimentum vitae. Vestibulum nisl tortor, consectetur eu nunc sit amet, efficitur viverra arcu. Duis magna mauris, sodales eu cursus eu, blandit vel dui. Maecenas eget ipsum leo. Suspendisse eu tortor in tellus finibus tincidunt ut vel nunc. Nam luctus eros eget consectetur consectetur.'],
            ['id' => 4, 'title' => 'Suspendisse iaculis enim at pellentesque euismod. 4', 'image' => '/memorialisation/granite.jpg', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse dapibus placerat turpis, et mollis mi elementum non. Vestibulum et ullamcorper lectus. Duis imperdiet turpis diam, quis malesuada odio ultricies at. Mauris congue nibh a eros faucibus condimentum. Donec tempus leo justo, ut mauris.', 'article' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tortor libero, tincidunt ac ornare ac, vehicula id ligula. Vestibulum arcu lectus, laoreet sed nulla sit amet, eleifend bibendum mi. Nam dignissim non enim quis consequat. Suspendisse potenti. Suspendisse vulputate a nisi id viverra. Aliquam iaculis erat orci, sit amet commodo nunc dignissim porta. In hac habitasse platea dictumst. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur metus lectus, varius non pretium ac, volutpat nec dui. Aenean sagittis pellentesque est, quis mollis purus vehicula quis. Sed scelerisque ultrices sapien, ac rhoncus quam condimentum vitae. Vestibulum nisl tortor, consectetur eu nunc sit amet, efficitur viverra arcu. Duis magna mauris, sodales eu cursus eu, blandit vel dui. Maecenas eget ipsum leo. Suspendisse eu tortor in tellus finibus tincidunt ut vel nunc. Nam luctus eros eget consectetur consectetur.'],
        ];

        $predefinedImages = [
            ['id' => 1, 'name' => 'Bust1', 'imageurl' => '/uploadedimages/bust11659368316.jpeg', 'product_id' => 16, 'price' => 80],
            ['id' => 2, 'name' => 'Bust2', 'imageurl' => '/uploadedimages/bust21659368323.png', 'product_id' => 16, 'price' => 80],
        ];

        $this->call([SiteSeeder::class]);
        $this->call([MemorialisationsSeeder::class]);

        Supplier::factory()
            ->count(20)
            ->create();

        $this->call([ProductsSeeder::class]);

        Role::insert($roles);
        Settings::insert($settings);
        Status::insert($statuses);
        AttributeTypes::insert($attributeTypes);
        Attributes::insert($attributes);
        Image::insert($images);
        MemorialisationSite::insert($memorialisationSites);
        Material::insert($materials);
        CustomImage::insert($customImages);
        CustomProductText::insert($customProductTexts);
        CustomTextField::insert($customTextFields);
        Article::insert($articles);
        PredefinedImage::insert($predefinedImages);

        User::findOrFail(User::insertGetId($admin))->roles()->attach(['1', '2']);
        User::findOrFail(User::insertGetId($vivediaStaff))->roles()->attach(['2']);
        User::findOrFail(User::insertGetId($siteAdmin))->roles()->attach(['3', '4']);
        User::findOrFail(User::insertGetId($siteStaff))->roles()->attach(['4']);
        User::findOrFail(User::insertGetId($colchesterUser))->sites()->attach(['27']);
        User::findOrFail(User::insertGetId($westerleighUser))->sites()->attach([$westerlySiteId]);
        User::findOrFail(User::insertGetId($robDavisAdmin))->roles()->attach(['1', '2']);

        User::factory()
            ->count(20)
            ->create();

        Deceased::factory()
            ->count(30)
            ->create();

        $roles = Role::all();

        User::all()->forget(0)->each(function ($user) use ($roles) {
            $user->roles()->attach(
                $roles->random(rand(1, 2))->pluck('id')->toArray()
            );
        });

        $sites = Site::all();

        User::all()->each(function ($user) use ($sites) {
            $user->sites()->attach(
                $sites->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

        Supplier::all()->each(function ($supplier) use ($sites) {
            $supplier->sites()->attach(
                $sites->random(rand(1, 2))->pluck('id')->toArray()
            );
        });

        $this->call([FontSeeder::class]);
        $this->call([faqSeeder::class]);
        $this->call([UpdateFieldsInProductLines::class]);
        $this->call([SitestylesSeeder::class]);
        $this->call([ArticleSitesSeeder::class]);
    }
}
