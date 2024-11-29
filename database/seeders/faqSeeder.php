<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class faqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faqs')->insert(
            [
                ['question' => 'What happens if I want a bespoke memorial or something different to the personalisation options available on the website?', 'answer' => 'For a bespoke memorial, please contact your crematorium by submitting an enquiry on the product page.'],
                ['question' => 'What wording should I include in a book of remembrance or on a memorial plaque?', 'answer' => "Most people include their loved one's name, date of birth and date of death. You may also choose toadd a meaningful quote or some words of remembrance. Something that demonstrates your love forthe person who has passed away. There are lots of places where you can find inspiration online. Be aware, there will be a limit on the number of characters and lines of text you can include due to the space available on the memorial plaque or in the memorial book. So you will need to consider this when crafting your message."],
                ['question' => 'Can I include a picture on my memorial plaque?', 'answer' => 'You may be able to include a picture on your memorial plaque. You will need to check the individual product page to view the personalisation options.'],
                ['question' => 'Who will provide my memorial engraving?', 'answer' => 'We will use a highly skilled, experienced craftsperson to engrave your memorials with your chosen image and/or words.'],
                ['question' => 'Who will write my memorial book inscription?', 'answer' => 'The entry will be beautifully handwritten by a professional calligrapher.'],
                ['question' => 'What are living memorials?', 'answer' => 'A living memorial is a plant - often a tree, memorial rose or shrub - that’s typically placed in a garden of remembrance or memorial arboretum, often with a plaque to remember your loved one. Please check the product pages to see if this memorial option is available at your chosen location.'],
                ['question' => 'How much will my memorial cost?', 'answer' => "You can see the price for your chosen memorial by selecting your preferred options on the relevant product page. This will show the price you'll pay for the memorial option itself including any personalisation. It does not include additional costs, like leasing a space in a memorial garden or cemetery. You will need to call us directly to discuss these costs."],
                ['question' => 'Where can I place my memorial?', 'answer' => 'Memorials are placed in a garden of remembrance or cemetery where they can be cared for and tended. This will create a place for friends and family to visit and remember your loved one.'],
                ['question' => 'Can I place my memorial outside a cemetery or graveyard?', 'answer' => 'Maybe - this will depend on local regulations. Please ask your crematorium as the rules on placing memorials away from a graveyard or cemetery vary by council area.'],
                ['question' => 'How much are memorial fees?', 'answer' => 'It varies. Memorial fees are the costs associated with placing your memorial in a cemetery or memorial garden. They include items like leasing costs and maintenance and will vary depending on the memorial option you purchase and how long you want to display it for. Please contact us for more details or request a callback using the button at the top of this page.'],
                ['question' => 'Who is responsible for taking care of my memorial?', 'answer' => 'It depends where your memorial is located. If your memorial is placed in a cemetery, a memorial garden or memorial arboretum, we will contact you to discuss an agreement which will cover its care.'],
            ]
        );
    }
}
