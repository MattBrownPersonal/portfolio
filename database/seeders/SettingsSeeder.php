<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['key' => 'videdia_email', 'value' => 'info@vivedia.com'],
            ['key' => 'new_vivedia_staff_email_subject_text', 'value' => 'Welcome'],
            ['key' => 'new_vivedia_staff_email_body', 'value' => 'You are receiving this email as you have been added as a new member of staff for Vivedia. Please click the below button to set your password'],
            ['key' => 'applicant_code_email_subject_text', 'value' => 'Your personalised shop'],
            ['key' => 'applicant_code_email_body', 'value' => 'You have received this email as a way to access your personalised shop. Plesae click the below button to begin'],
            ['key' => 'applicant_query_subject_text', 'value' => 'New Query'],
            ['key' => 'applicant_query_email_body', 'value' => 'You have received a new query.'],
            ['key' => 'homepage_remember_copy', 'value' => 'A customised tribute is a thoughtful and caring way to celebrate the life of your loved one and create a place of commemoration for family and friends.'],
            ['key' => 'homepage_bereavement_copy', 'value' => 'Coping with grief can take many forms. These articles cover a range of bereavement topics and provide details of grief and bereavement support groups and organisations.'],
            ['key' => 'welcome_copy', 'value' => 'Please enter your privacy key for bereavement support and memorial options.'],
            ['key' => 'bereavement_landing_copy', 'value' => 'Coping with grief can take many forms. These articles cover a range of bereavement topics and provide details of grief and bereavement support groups and organisations.'],
            ['key' => 'category_copy', 'value' => 'There are many ways of remembering a loved one. Choose a memorial and select meaningful words to personalise your tribute.'],
            ['key' => 'buy_now_copy', 'value' => "When you're happy with your personalised memorial design, simply place your order. Once received, we will be in touch as soon as possible. Alternatively, if you would like to confirm the exact placement of your memorial personalisation or if you have any special requests, you can call us on after placing your order."],
            ['key' => 'share_friends_family', 'value' => 'You can email or download a copy of your bespoke memorial to share with friends and family.'],
            ['key' => 'share_team', 'value' => 'Share a copy of your personalised memorial and one of our team will be in touch to discuss your design in more detail. Or, if you want to discuss additional personalisation options, please contact us on '],
            ['key' => 'email_greeting', 'value' => 'Hi friend,'],
            ['key' => 'email_content', 'value' => "We know how difficult the loss of a loved one can be. So, as part of our service, we've written a range of bereavement articles to support you. We've also created a dedicated space for you to personalise a lasting memorial. This can be an important part of the grieving process. Not only will your tribute help you remember your loved one, but it will create a place for friends and family to visit and reminisce. You can access the support articles and view your dedicated memorial page here. Your personalised memorial page will be accessible at any time during the next three years. Simply keep hold of this email until you're ready. Or contact us at a later date."],

        ];

        Settings::insert($settings);
    }
}
