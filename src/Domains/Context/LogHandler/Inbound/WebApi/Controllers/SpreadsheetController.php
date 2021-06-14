<?php


namespace Domains\Context\LogHandler\Inbound\WebApi\Controllers;

use App\Http\Controllers\Controller;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Illuminate\Http\Request;
use Domains\Context\LogHandler\Application\UseCases\Spreadsheet\ExtractSpreadsheetInput;
use Domains\Context\LogHandler\Application\UseCases\Spreadsheet\IExtractSpreadsheetUseCase;
use Domains\Context\LogHandler\Infrastructure\Framework\Transformers\DataSourceErrorsResource;
use Domains\Context\LogHandler\Infrastructure\Framework\Transformers\DataSourceResource;
use Symfony\Component\HttpFoundation\Response;

class SpreadsheetController extends Controller
{

    public function extract(Request $request, IExtractSpreadsheetUseCase $extractDataUseCase)
    {

        $input = new ExtractSpreadsheetInput($request->file, new MessageHandler());
        $extractDataUseCase->execute($input);
        $result = $extractDataUseCase->outputPort->result();

        if ($result->isValid()) {
            return new DataSourceResource($result->messages);
        }
        return (new DataSourceErrorsResource($result->errors))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
