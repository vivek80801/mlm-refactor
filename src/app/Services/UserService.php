<?php

namespace App\Services;

use App\Core\Exceptions\AppException;
use App\Core\Request;
use App\Models\User;
use Throwable;

class UserService
{
    public function __construct
    (
        private QRService $qrService,
        private ReferalChainService $referalChainService,
        private UserWalletService $walletService
    )
    { }
    public function register
    (
        Request $request
    ): void
    {
        $referedByUser = User::query()
            ->where(
            "referral_code",
            $request->input("referred_by")
        )->get()[0];

        try{
            User::transactionBegin();

            $newUser = $this->createUser(
                $request,
                $referedByUser
            );
            $this
                ->referalChainService
                ->addToReferalChain(
                    $newUser->id,
                    $referedByUser->id
                );
            $this
                ->walletService
                ->createWallet($newUser->id);

            User::transactionCommit();
        }catch (Throwable $e) {
            User::transactionRollBack();
            throw new AppException(
                "User Service Error: ".
                $e->getMessage()
            );
        }
    }

    public function createUser
    (
        Request $request,
        User $referedByUser
    ): User
    {
        $user = new User();
        $user->mobile = $request->input("mobile");
        $user->password = password_hash(
            $request->input("password"),
            PASSWORD_DEFAULT
        );
        $user->invite_code = $request->input("referred_by");
        $user->referred_by =  $referedByUser->id;
        $user->referral_code  = generateRandomNum(6);
        $user->reffrel_qr = $this->qrService
            ->generateRefaralQR();
        $user->otp = generateRandomNum(6);
        $user->save();

        return $user;
    }
}
