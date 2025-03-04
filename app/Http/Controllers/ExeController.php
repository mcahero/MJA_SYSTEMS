<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExeController extends Controller
{
    public function runCapture(Request $request)
    {
        // Full path to your executable
        $exePath = public_path('executable/Capture2TextPortable-20250302T162754Z-001/Capture2TextPortable/Capture2TextPortable.exe');

        // Get the argument from the AJAX request
        $argument = $request->input('argument');

        // Build the command, ensuring proper quoting
        $command = '"' . $exePath . '" "' . $argument . '"'; // Quote both path and argument

        // Execute the command and capture output
        exec($command, $output, $returnCode);

        // Check the return code for success or failure
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
