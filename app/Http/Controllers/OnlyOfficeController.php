<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnlyOfficeController extends Controller
{
    public function open($filename)
    {
        $filePath = storage_path("app/$filename");
        $fileUrl = asset("storage/$filename"); // accessible via /storage symlink

        $config = [
            "document" => [
                "fileType" => "docx",
                "key" => md5($filename . '-' . (auth()->id() ?? 1)),
                "title" => $filename,
                "url" => $fileUrl,
            ],
            "editorConfig" => [
    "mode" => "edit",
    "callbackUrl" => "http://host.docker.internal/onlyoffice/save",
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
    $data = $request->all();

    // Only save if status = 2 (document edited & ready to save)
    if (isset($data['status']) && $data['status'] == 2) {
        $downloadUrl = $data['url'];
        $filename = 'view.docx';

        try {
            $contents = file_get_contents($downloadUrl);
            if ($contents !== false) {
                \Storage::disk('local')->put($filename, $contents);
            }
        } catch (\Exception $e) {
            // optional logging
        }
    }

    // Always return JSON ok to prevent popup
    return response()->json(["error" => 0]);
}

}
