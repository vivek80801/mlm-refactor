<?php

namespace App\Services;

require_once basePath() . "/src/lib/phpqrcode/qrlib.php";

use App\Core\Exceptions\AppException;
use QRcode;

class  QRService
{
    public function generateRefaralQR
    (): string
    {
        $reffrel_qr = generateRandomNum(6) . ".png";
        $buatFolder = basePath() . "public/assets/uploads/qr/";

        if (!file_exists($buatFolder)) {
        	mkdir($buatFolder);
        }

        $logoPath = basePath() .
            "public/assets/img/" . env('APP_LOGO');
        if(!file_exists($logoPath))
        {
            throw new AppException("Logo Error: " . $logoPath . " does not exists.");
        }
        $content = $reffrel_qr;

        QRcode::png(
             $content,
             $buatFolder . $reffrel_qr,
             QR_ECLEVEL_H, 12, 2
        );

        $QR =
            imagecreatefrompng(
                $buatFolder . $reffrel_qr
            );

        $logo =
            imagecreatefromstring(
                file_get_contents($logoPath)
            );

        imagecolortransparent(
            $logo,
            imagecolorallocatealpha(
                $logo,
                0,
                0,
                0,
                127
            )
        );

       imagealphablending($logo, false);
       imagesavealpha($logo, true);

       $QR_width = imagesx($QR);
       $QR_height = imagesy($QR);
       $logo_width = imagesx($logo);
       $logo_height = imagesy($logo);
       $logo_qr_width = $QR_width / 4;
       $scale = $logo_width / $logo_qr_width;
       $logo_qr_height = $logo_height / $scale;

       imagecopyresampled(
            $QR,
            $logo,
            $QR_width / 2.5,
            $QR_height / 2.5,
            0,
            0,
            $logo_qr_width,
            $logo_qr_height,
            $logo_width,
            $logo_height
       );
       imagepng(
            $QR,
            $buatFolder . $reffrel_qr
       );

       return $reffrel_qr;
    }
}
