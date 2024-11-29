<?php

namespace Database\Factories;

use App\Models\Faqs;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faqs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return
            ['id' => 1, 'question' => "What should I do if I'm having trouble logging in?", 'answer' => "If you're trying to watch a webcast but your username/password aren't working, please contact the funeral director or crematorium to confirm your login details. For privacy reasons we cannot tell you these directly. Your username should be a short word, then some numbers. Your password should just be numbers, no spaces.", 'site_id' => 145];
        ['id' => 2, 'question' => "I'd like to view a webcast again. How can I arrange this?", 'answer' => "If you'd like a recording of the webcast put back online to watch again, please speak to the funeral director or crematorium as they have to order this with us so the family can authorise it. Most of these will be back online within 2-3 working days, but some are taking longer if the crematorium's internet is slower.", 'site_id' => 145];
        ['id' => 3, 'question' => 'How can I order a DVD or USB keepsake copy of a service?', 'answer' => 'Please speak to the funeral director or crematorium - they have to order this with us so the family can authorise it.', 'site_id' => 145];
        ['id' => 4, 'question' => 'Is it possible to leave a memorial message?', 'answer' => "We regret we are unable to pass on memorial messages directly to family members (we don't have their contact details). Please send any memorial messages to the funeral director or the family themselves.", 'site_id' => 145];
        ['id' => 5, 'question' => "What should I do if I'm having trouble viewing a webcast?", 'answer' => "Due to the nature of the internet connections there are some setups that on occasion where due to the internet service provider you have, the type of network you are on or what network routers/devices you have this device connected to will mean that you are unable to view our streams/video content. Here are a few more things to try: 1. If you have another device, try that as well. Mobile devices, such as iPads, work most reliably. 2. Restart your computer and internet router. 3. Clear your web browser's 'browsing data', including history, cookies and cache. 4. Try using a different web browser. Google Chrome is often best, Firefox and Safari are good too. Edge/Internet Explorer both work, but are sometimes less reliable. Please make sure any browser is fully updated. 5. If you're using wireless/wifi, try plugging your computer into your internet router using a cable, if you can. 6. If you can't hear anything make sure your speakers are turned on and turned up. Try listening to something else just to check. If you are still experiencing problems viewing this service, please click the option below to contact us so we can help you troubleshoot this issue. It would be helpful if you are aware what type of device and browser you are viewing the webcast with – if not it may take longer to troubleshoot the issue you are having.", 'site_id' => 145];
        ['id' => 6, 'question' => 'What should I do if a webcast stops working?', 'answer' => "If you were viewing a Live Webcast (happening as the service takes place) and can no longer see the video, it's possible that connection to the crematorium has been lost. Please wait, and this should be restored as quickly as possible. If you were viewing a Watch Again (a service which has already taken place), please check your internet connection and reload the page.", 'site_id' => 145];
        ['id' => 7, 'question' => 'How can I contact you? ', 'answer' => 'Click the "REQUEST A CALLBACK" button.', 'site_id' => 0];
    }
}
