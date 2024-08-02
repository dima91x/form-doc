<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocRequest;
use App\Services\DocService;

class DocController extends Controller
{
    private DocService $docService;

    public function __construct(DocService $docService)
    {
        $this->docService = $docService;
    }

    public function generate(DocRequest $request)
    {
        $fields = $request->validated();

        $file = $this->docService->generate($fields["docName"], $fields["createDate"]);

        return response()->json([
            "file" => $file,
            "fileName" => $fields["docName"],
            "fileType" => "docx"
        ]);
    }
}
