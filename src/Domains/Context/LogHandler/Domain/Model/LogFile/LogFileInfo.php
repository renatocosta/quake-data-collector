<?php

namespace Domains\Context\LogHandler\Domain\Model\LogFile;

class LogFileInfo
{

    const DEFAULT_FILE_NAME = 'qgames.log';

    const DEFAULT_FILE_EXTENSION = 'log';

    const MIN_COUNT_ROWS = 1;

    const AT_LEAST_ONE_ROW_MUST_BE_FILLED = 'At least one row must be filled';

    const MISSING_FILE_EXTENSION_MESSAGE = 'Missing file extension %s';

    const NO_CONTENT_FILE_MESSAGE = "No content file";

    const MIN_SIZE_REQUIRED = 1;

    const MIN_SIZE_REQUIRED_MESSAGE = 'Min %s size required';

    const UNABLE_TO_HANDLE_THIS_FILE_MESSAGE = 'Unable to handle this file';

}