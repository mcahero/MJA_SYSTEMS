<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExeController extends Controller
{
    public function runCapture(Request $request)
    {
        // Full path to your executable
        $exePath = 'C:\laragon\www\INVENTORY_MJA\public\executable\Capture2TextPortable-20250302T162754Z-001\Capture2TextPortable\Capture2TextPortable.exe';

        // Example: Passing an argument (if needed)
        $argument = $request->input('argument');
        $command = '"' . $exePath . '" ' . $argument; //quote the path and argument.

        // Execute the command
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            // Success
            return response()->json([
                'success' => true,
                'output' => $output,
            ]);
        } else {
            // Error
            return response()->json([
                'success' => false,
                'error' => 'Execution failed',
                'returnCode' => $returnCode,
                'output' => $output,
            ]);
        }
    }
}