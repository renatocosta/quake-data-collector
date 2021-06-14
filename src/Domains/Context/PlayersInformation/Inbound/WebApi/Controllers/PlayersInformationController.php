<?php


namespace Domains\Context\PlayersInformation\Inbound\WebApi\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domains\Context\PlayersInformation\Application\UseCases\CreateDataSource\CreateDataSourceInput;
use Domains\Context\PlayersInformation\Application\UseCases\CreateDataSource\ICreateDataSourceUseCase;
use Symfony\Component\HttpFoundation\Response;

class PlayersInformationController extends Controller
{

    public function store(Request $request, ICreateDataSourceUseCase $createDataSourceUseCase)
    {
        $input = new CreateDataSourceInput($request->get('contract'), $request->get('source_type'), $request->get('remote_file_id'), $request->get('company_id'), $request->get('items'));
        $createDataSourceUseCase->execute($input);
        $result = $createDataSourceUseCase->outputPort->result();

        if ($result->isValid()) {
            return response($result->messages, Response::HTTP_CREATED);
        }

        return response($result->errors, Response::HTTP_BAD_REQUEST);
    }
}