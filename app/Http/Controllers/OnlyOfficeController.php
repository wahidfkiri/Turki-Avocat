<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class OnlyOfficeController extends Controller
{
    public function open($filename)
    {
        $filePath = storage_path("app/$filename");
        $fileUrl = asset("storage/$filename"); // accessible via /storage symlink
 $key = md5($filename . '-' . (auth()->id() ?? '1'));
              // Clear any old cache for this key
            Cache::forget("onlyoffice_file_$key");
            Cache::put("onlyoffice_file_$key", $filename, 3600);
        $config = [
            "document" => [
                "fileType" => "docx",
                "key" =>$key,
                "title" => $filename,
                "url" => $fileUrl,
            ],
            "editorConfig" => [
    "mode" => "edit",
    "callbackUrl" => 'http://192.168.1.130:8000/onlyoffice/save',
    "user" => [
        "id" => (string) (auth()->id() ?? 1),
        "name" => auth()->user()->name ?? "User",
    ],
],

        ];

        return view('onlyoffice.editor', compact('config'));
    }

 public function save(Request $request)
{
    try {
        $data = $request->all();

        if (isset($data['status']) && $data['status'] == 2 && !empty($data['url'])) {
            $downloadUrl = $data['url'];
            $key = $data['key'] ?? '';

            $originalPath = Cache::get("onlyoffice_file_$key");

            if ($originalPath && Storage::disk('public')->exists($originalPath)) {
                $contents = @file_get_contents($downloadUrl);
                if ($contents !== false) {
                    Storage::disk('public')->put($originalPath, $contents);
                }
            }
        }

        return response()->json(['error' => 0]);

    } catch (\Throwable $e) {
        \Log::error("OnlyOffice save error: " . $e->getMessage());
        return response()->json(['error' => 0], 200);
    }
}


}
