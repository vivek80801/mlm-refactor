<?php

namespace App\Services;

require_once basePath() . "/src/lib/phpqrcode/qrlib.php";

use App\Core\Request;
use App\Models\User;
use QRcode;

class  UserService
{
    public function register
    (
        Request $request
    ): void
    {
        $referedByUser = User::sql(
            "SELECT * FROM users
            WHERE referral_code=:referred_by",
            "single",
            [
                ":referred_by"
                => $request->input("referred_by")
            ]
        );

        $user = new User();
        $user->mobile = $request->input("mobile");
        $user->password = password_hash(
            $request->input("password"),
            PASSWORD_DEFAULT
        );
        $user->invite_code = $request->input("referred_by");
        $user->referred_by = (int) $referedByUser["id"];
        $user->referral_code  = generateRandomNum(6);
        $user->reffrel_qr = $this->generateRefaralQR();
        $user->otp = 01234;
        if($user->save())
        {
            $newUser = User::where(
                "mobile",
                $request->input("mobile")
            );
            $this->addToReferalChain(
                $newUser->id,
                (int) $referedByUser["id"]
            );
            $this->createUserWallet($newUser->id);
        }
    }

    public function addToReferalChain
    (
        int $userId,
        int $referedByUserID,
    ): void
    {
        User::sql(
            "INSERT INTO referral_chain
            (user_id, referred_user_id)
            VALUES (:user_id, :referred_user_id)",
            "single",
            [
                ":user_id" => $userId,
                ":referred_user_id"
                => $referedByUserID
            ]
        );
    }

    public function createUserWallet
    (
        int $userId
    ): void
    {
        User::sql(
            "INSERT INTO wallet (user_id, amount, type) VALUES(:user_id, 0, 'cr')",
            "single",
            [
                ":user_id" => $userId
            ]
        );
    }

    public function generateRefaralQR
    (): string
    {
        $reffrel_qr = generateRandomNum(6) . ".png";
        $buatFolder = basePath() . "public/assets/uploads/qr/";

        if (!file_exists($buatFolder)) {
        	mkdir($buatFolder);
        }

       $logoPath = basePath() . "public/assets/img/logo_bitcoin.png";
       $content = $reffrel_qr;

       QRcode::png($content, $buatFolder . $reffrel_qr, QR_ECLEVEL_H, 12, 2);

       $QR = imagecreatefrompng($buatFolder . $reffrel_qr);
       $logo = imagecreatefromstring(file_get_contents($logoPath));

       imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 127));
       imagealphablending($logo, false);
       imagesavealpha($logo, true);
       $QR_width = imagesx($QR);
       $QR_height = imagesy($QR);
       $logo_width = imagesx($logo);
       $logo_height = imagesy($logo);
       $logo_qr_width = $QR_width / 4;
       $scale = $logo_width / $logo_qr_width;
       $logo_qr_height = $logo_height / $scale;
       imagecopyresampled($QR, $logo, $QR_width / 2.5, $QR_height / 2.5, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
       imagepng($QR, $buatFolder . $reffrel_qr);

       return $reffrel_qr;
    }
}
