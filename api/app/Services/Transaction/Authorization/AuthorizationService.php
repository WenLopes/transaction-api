<?php 

namespace App\Services\Transaction\Authorization;

use App\Exceptions\Transaction\Authorization\CheckAuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AuthorizationService implements AuthorizationServiceInterface{

    /** @var string */
    protected const URL = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';

    /** @var \Illuminate\Http\Client\Response */
    protected $response = null;

    public function authorized() : bool
    {
        try {
            $response = Http::get(self::URL);

            if(!$response->ok()){
                return false;
            }

            if( $response->status() !== JsonResponse::HTTP_OK){
                return false;
            }

            if( $response->json()['message'] != 'Autorizado') {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            throw new CheckAuthorizationException($e->getMessage());
        }

    }
}