<?php

namespace Domains\Context\LogHandler\Application\UseCases\LogFile;

use Illuminate\Support\Facades\Log;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFile;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileInfo;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileMetadata;
use Exception;

final class SelectLogFileUseCase implements ISelectLogFileUseCase
{

    private LogFile $logFile;

    public function __construct(LogFile $logFile)
    {
        $this->logFile = $logFile;
    }

    public function execute(SelectLogFileInput $input): void
    {

        try {
            $file = new \SplFileObject(storage_path('app/public/') . $input->fileName);
            $file->setFlags(\SplFileObject::SKIP_EMPTY);
            $this->logFile->extractOf($file, new LogFileMetadata($file->getSize(), $file->getExtension()));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new SelectLogFileException(LogFileInfo::UNABLE_TO_HANDLE_THIS_FILE_MESSAGE);
        }
    }
}
