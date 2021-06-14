<?php

namespace Domains\Context\LogHandler\Application\UseCases\Spreadsheet;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Domains\Context\Asset\Config\Storage;
use Domains\Context\Core\Entities\FileCategory;
use Domains\Context\Core\Entities\RemoteFile;
use Domains\Context\LogHandler\Domain\Model\SpreadsheetSource\ISpreadsheet;
use Domains\Context\LogHandler\Domain\Model\SpreadsheetSource\SpreadsheetMessagesEnum;
use Domains\Context\LogHandler\Domain\Services\SpreadsheetExtracted;
use Domains\Context\LogHandler\Outbound\ISpreadsheetOutputPort;

final class ExtractSpreadsheetUseCase implements IExtractSpreadsheetUseCase
{

    private ISpreadsheet $dataSourced;

    public ISpreadsheetOutputPort $outputPort;

    private SpreadsheetExtracted $spreadsheetExtractor;

    private RemoteFile $remoteFile;

    public function __construct(
        ISpreadsheet $dataSourced,
        SpreadsheetExtracted $spreadsheetExtractor,
        ISpreadsheetOutputPort $outputPort
    ) {
        $this->dataSourced = $dataSourced;
        $this->spreadsheetExtractor = $spreadsheetExtractor;
        $this->outputPort = $outputPort;
    }

    public function execute(ExtractSpreadsheetInput $input)
    {

        $rows = [];

        try {
            $this->spreadsheetExtractor->setup($input->file);
            $this->spreadsheetExtractor->find();
            $rows = $this->spreadsheetExtractor->rows();

            $this->remoteFile = RemoteFile::createRemoteFile(
                $input->file,
                FileCategory::GENERAL,
                Storage::VDIR_INVOICES_FILES,
                config('filesystems.disks.s3.url')
            );
        } catch (\Exception $e) {
            $input->modelState->addKeyError(
                'integration_yardi-spreadsheet-message-UNABLE_TO_HANDLE_THIS_FILE"'
            );
            Log::error($e->getMessage());
        }

        if ($input->modelState->isValid()) {
            $this->dataSourced->extractFrom($rows);
            if ($this->dataSourced->isValid()) {
                $this->outputPort->ok($this->dataSourced, $this->remoteFile->id, $input->contract, $input->modelState);
                return;
            }
            $input->modelState->addListError(
                $this->dataSourced->getErrors()
            );
        }

        $this->outputPort->invalid($input->modelState);
    }
}
