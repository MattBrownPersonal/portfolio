<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteStyles extends Model
{
    use HasFactory;

    const PRIMARY_COLOUR = '#25FF74';

    const SECONDARY_COLOUR = '#FDF12B';

    const PRIMARY_FONT_COLOUR = '#082DFF';

    const SECONDARY_FONT_COLOUR = '#FF2222';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_styles';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'primary_colour',
        'secondary_colour',
        'primary_font_colour',
        'secondary_font_colour',
        'image_id',
        'site_id',
    ];

    public static function storeSiteStyle($siteId)
    {
        $style = new self;
        $style->site_id = $siteId;
        $style->primary_colour = self::PRIMARY_COLOUR;
        $style->secondary_colour = self::SECONDARY_COLOUR;
        $style->primary_font_colour = self::PRIMARY_FONT_COLOUR;
        $style->secondary_font_colour = self::SECONDARY_FONT_COLOUR;
        $style->save();

        return $style;
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
