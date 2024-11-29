<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use QrCode;

class SiteQrCode extends Model
{
    use HasFactory;

    public static function getSiteQrs($id)
    {
        $site = Site::find($id);

        $allSiteQrCodes = self::where('site_id', $id)->get();

        $siteCodes = [];

        foreach ($allSiteQrCodes as $qrCode) {
            $generatedQrCode = self::createQrCode($site->slug, $qrCode->id);
            array_push($siteCodes, ['qrId' => $qrCode->id, 'location' => $qrCode->location, 'qrCode' => $generatedQrCode->toHtml()]);
        }

        return $siteCodes;
    }

    public static function createQrCodeForLeaflet($request)
    {
        return QrCode::size(110)->generate(config('app.url').'m/'.$request->slug.'?location='.$request->location_id);
    }

    public static function createQrCode($slug, $location)
    {
        $size = 1180; // 1180px is 10cm at 300 DPI

        return QrCode::size($size)->generate(config('app.url').'m/'.$slug.'?location='.$location);
    }

    /**
     * create QR code for location at site.
     */
    public static function generateQrCode($request)
    {
        $qrCode = new self();
        $qrCode->site_id = $request->id;
        $qrCode->location = $request->location;
        $qrCode->save();

        return $qrCode;
    }

    /**
     * edit QR code for location at site.
     */
    public static function editQrCode($request, $id)
    {
        $qrCode = self::findOrFail($id);
        $qrCode->location = $request->location;
        $qrCode->save();

        return $qrCode;
    }

    public static function deleteSiteQrCode($id)
    {
        $siteQrCode = self::findOrFail($id);
        $siteQrCode->delete();

        return response()->json(['message' => 'Code Deleted Successfully'], 200);
    }
}
