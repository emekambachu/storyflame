<?php

namespace App\Services\Base;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseService.
 * This class contains commonly used methods
 */
class BaseService
{
    public static function getIp(): ?string
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

    /**
     * @throws \JsonException
     */
    public static function getLocationFromIp(): array
    {
        $ip = static::getIp();
        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip), false, 512, JSON_THROW_ON_ERROR);
        return [
            'ip' => $ip,
            'country' => $ip_data->geoplugin_countryName ?? null,
            'city' => $ip_data->geoplugin_city ?? null,
            'state' => $ip_data->geoplugin_region ?? null,
        ];
    }

    public static function getDeviceInfo(): array
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? ''; // Use null coalescing operator to avoid undefined index notices
        $osPlatform = "Unknown OS Platform";
        $osArray = [
            '/windows nt 10\.0/i' => 'Windows 10', // Use escaping for dots as they are special characters in regex
            '/windows nt 6\.3/i'  => 'Windows 8.1',
            '/windows nt 6\.2/i'  => 'Windows 8',
            '/windows nt 6\.1/i'  => 'Windows 7',
            '/windows nt 6\.0/i'  => 'Windows Vista',
            '/windows nt 5\.2/i'  => 'Windows Server 2003/XP x64',
            '/windows nt 5\.1/i'  => 'Windows XP',
            '/windows xp/i'       => 'Windows XP',
            '/windows nt 5\.0/i'  => 'Windows 2000',
            '/windows me/i'       => 'Windows ME',
            '/win98/i'            => 'Windows 98',
            '/win95/i'            => 'Windows 95',
            '/win16/i'            => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'      => 'Mac OS 9',
            '/linux/i'            => 'Linux',
            '/ubuntu/i'           => 'Ubuntu',
            '/iphone/i'           => 'iPhone',
            '/ipod/i'             => 'iPod',
            '/ipad/i'             => 'iPad',
            '/android/i'          => 'Android',
            '/blackberry/i'       => 'BlackBerry',
            '/webos/i'            => 'Mobile'
        ];

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
                break; // Exit loop once a match is found
            }
        }

        $browser = "Unknown Browser";
        $browserArray = [
            '/msie/i'       => 'Internet Explorer',
            '/firefox/i'    => 'Firefox',
            '/safari/i'     => 'Safari',
            '/chrome/i'     => 'Chrome',
            '/edge/i'       => 'Edge',
            '/opera/i'      => 'Opera',
            '/netscape/i'   => 'Netscape',
            '/maxthon/i'    => 'Maxthon',
            '/konqueror/i'  => 'Konqueror',
            '/brave/i'      => 'Brave', // Added Brave browser
            '/mobile/i'     => 'Handheld Browser'
        ];

        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser = $value;
                break; // Exit loop once a match is found
            }
        }

        return [
            'os' => $osPlatform,
            'browser' => $browser,
        ];
    }

    public static function logInfo($info, $id = null): void
    {
        Log::info('INFO: ' . $info.' | ID: '.$id);
    }

    public static function logError($e, $id = null): void
    {
        Log::error('ERROR: Line ' . $e->getLine() . ' of ' . $e->getFile() . ', ' . $e->getMessage() . ' | ID: ' . ($id ?? ''));
    }

    public static function tryCatchException($e, $id = null, $message = null): JsonResponse
    {
        Log::error('SERVER: Line ' . $e->getLine() . ' of ' . $e->getFile() . ', ' . $e->getMessage() . ' | ID: ' . $id);

        return response()->json([
            'success' => false,
            'message' => $message,
            'server_error' => "Line ".$e->getLine()." of ".$e->getFile().", ".$e->getMessage(),
        ], 500);
    }

    public function generalApiParams($request, $query){
        if($request->per_page){
            $data = $query->paginate($request->per_page);
        }else if($request->limit){
            $data = $query->limit($request->limit)->get();
        }else{
            $data = $query->get();
        }
        return $data;
    }

    public static function sendEmailGeneral(
        Array $data, String $emailContent, String $subject, $mailTo, $mailToName, Array $recipients = null): void
    {
        Mail::send($emailContent, $data, static function ($message) use (
            $data, $subject, $mailTo, $mailToName, $recipients) {
            $message->from(config('app.mail_from'), config('app.name'));
            $message->to($mailTo, $mailToName);
            if(is_array($recipients) && count($recipients) > 0){
                $message->cc($recipients);
            }
            $message->replyTo(config('app.mail_from'), config('app.name'));
            $message->subject($subject);
        });
    }

    public static function randomCharacters(int $length, string $characters, string $staticChar = ''): string
    {
        $charactersLength = strlen($characters);
        $randomString = $staticChar;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

//    public static function randomChar(int $length, string $randChar, string $staticChar = null): string
//    {
//        $charactersLength = strlen($randChar);
//        $randomString = $staticChar ?? '';
//        for ($i = 0; $i < $length; $i++) {
//            $randomString .= $randChar[random_int(0, $charactersLength - 1)];
//        }
//        return $randomString;
//    }

    public static function noImageUser(): string
    {
        return config('app.app_url').'/images/no-image-user.png';
    }

    public static function getUserFromToken($token): array
    {
        $data = PersonalAccessToken::findToken($token)->tokenable;
        return [
            'success' => true,
            'user' => $data,
        ];
    }

}
