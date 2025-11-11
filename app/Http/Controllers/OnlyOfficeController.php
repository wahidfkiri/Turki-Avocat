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
                "key" => md5($filename . filemtime($filePath)),
                "title" => $filename,
                "url" => $fileUrl,
            ],
            "editorConfig" => [
                "mode" => "edit",
                "callbackUrl" => null,
                "user" => [
                    "id" => auth()->id() ?? 1,
                    "name" => auth()->user()->name ?? "User",
                ],
            ],
        ];

        return view('onlyoffice.editor', compact('config'));
    }

    public function save(Request $request)
    {
        $data = $request->all();

        if (isset($data['status']) && $data['status'] == 2) { // 2 = document ready for saving
            $downloadUrl = $data['url'];
            $contents = file_get_contents($downloadUrl);
            $filePath = storage_path("app/view.docx"); // le même fichier d’origine
            file_put_contents($filePath, $contents);
        }

        return response()->json(["result" => "ok"]);
    }
}
