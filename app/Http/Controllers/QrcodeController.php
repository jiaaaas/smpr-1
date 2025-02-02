<?php

namespace App\Http\Controllers;

use App\Models\Qrcode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;

class QrcodeController extends Controller
{
    public function show()
    {
        // Generate a random OTP (6 digits)
        $otp = rand(100000, 999999);

        // Generate a unique UUID for the QR code
        $uuid = Str::uuid();

        // Generate QR code with OTP and unique UUID
        $qrCode = QrCodeFacade::size(435)->generate('OTP: ' . $otp . ' ID: ' . $uuid);

        // Store the generated QR code and OTP in the database
        Qrcode::create([
            'otp' => $otp,  // Store the OTP in the table
        ]);

        // Pass QR code and OTP to the view
        return view('qrcodes.index', compact('qrCode', 'otp'));
    }

    public function regenerate()
    {
        // Generate a random OTP (6 digits)
        $otp = rand(100000, 999999);
    
        // Generate a new UUID
        $uuid = Str::uuid();
    
        // Generate a new QR code with OTP and UUID (in SVG format)
        $qrCode = QrCodeFacade::format('svg')->size(300)->generate('OTP: ' . $otp . ' ID: ' . $uuid);
    
        // Base64 encode the QR code
        $base64QrCode = base64_encode($qrCode);
    
        // Save the OTP to the database
        Qrcode::create(['otp' => $otp]);
    
        // Return the QR code as a JSON response
        return response()->json([
            'qrCode' => $base64QrCode,
            'otp' => $otp
        ]);
    }
    
    
    
    
    
}