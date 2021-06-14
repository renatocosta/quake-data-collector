<?php

namespace Domains\Context\LogHandler\Outbound;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Domains\Context\LogHandler\Domain\Model\SpreadsheetSource\ISpreadsheet;
use Symfony\Component\HttpFoundation\Response;

final class ExtractSpreadsheetOutputPort implements ISpreadsheetOutputPort
{

    private MessageHandler $result;

    public function invalid(MessageHandler $messageHandler): void
    {
        $this->result = $messageHandler;
    }

    public function ok(ISpreadsheet $dataSourced, int $remoteFileId, string $contract, MessageHandler $messageHandler): void
    {
        $this->result = $messageHandler;
        //Upstream to Invoice Importer domain
        $request = Request::create('api/invoiceimporter', 'POST', ['contract' => $contract, 'remote_file_id' => $remoteFileId, 'company_id' => \Auth::user()->company->id, 'source_type' => 'YARDI_EXPORT_EXCEL', 'items' => iterator_to_array($dataSourced->getRows())]);
        Request::replace($request->input());
        $response = Route::dispatch($request);

        if ($response->status() == Response::HTTP_CREATED) {
            $responseData = json_decode($response->getContent());
            $this->result->addEntity($responseData);
        } else {
            $responseData = json_decode($response->getContent(), JSON_OBJECT_AS_ARRAY);
            $this->result->addListError($responseData);
        }
    }

    public function result(): MessageHandler
    {
        return $this->result;
    }
}
