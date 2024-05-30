<?php

namespace App\Traits;

use App\Helpers\ApiCodes;
use App\Helpers\HttpStatusCodes;
use App\Helpers\Messages;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

trait ApiTrait
{
    /**
     * @param string $message
     * @param int    $statusId
     * @param array  $data
     *
     * @return array
     */
    public function responseToJson($message, $statusId, $data = []): array
    {
        return [
            'message' => $message,
            'status' => $statusId,
            'data' => $data,
        ];
    }
    /**
     * @param string $message
     * @param int    $statusId
     * @param array  $data
     *
     * @return array
     */
    public function responseToJsonNoData($message, $statusId): array
    {
        return [
            'message' => $message,
            'status' => $statusId,
        ];
    }

    /**
     * @param string $message
     * @param int $statusId
     * @param array $data
     * @param int $httpCode
     *
     * @return JsonResponse
     */
    public function jsonResponse($message, $statusId, $data = [], $httpCode = 200): JsonResponse
    {
        $json = $this->responseToJson($message, $statusId, $data);

        return response()->json($json, $httpCode);
    }

    /**
     * @param array $data
     *
     * @return JsonResponse
     */
    public function successResponse($data = []): JsonResponse
    {
        return $this->jsonResponse('Veprimi u krye me sukses!', ApiCodes::SUCCESS, $data);
    }

    /**
     * @param string|null $message
     * @param int|null $status
     *
     * @return JsonResponse
     */
    public function resourceNotFound($message = null, $status = null): JsonResponse
    {
        $message = $message ?: ApiCodes::getResourceNotFoundMessage();
        $status = $status ?: ApiCodes::RESOURCE_NOT_FOUND;

        $json = $this->responseToJson($message, $status, []);

        return response()->json($json, 404);
    }

    /**
     * @param string|null $message
     * @param int|null $status
     *
     * @return JsonResponse
     */
    public function validationFailed($error = null, $status = null, $httpStatusCode = 422, $messageOverride = null): JsonResponse
    {
        $message = $messageOverride ?? $this->transformValidationMessages($error);
        $status = $status ?: ApiCodes::VALIDATION_FAILED;

        $json = $this->responseToJson($message, $status, $error);

        return response()->json($json, $httpStatusCode);
    }

    /**
     * @param string|null $message
     * @param int|null $status
     *
     * @return JsonResponse
     */
    public function validationFailedMobile($error = null, $status = null, $httpStatusCode = 422): JsonResponse
    {
        $message = $this->transformValidationMessages($error);
        $status = $status ?: ApiCodes::VALIDATION_FAILED;
        $json = $this->responseToJson($message, $status, [$error]);

        return response()->json($json, $httpStatusCode);
    }

    /**
     * @param null $error
     * @param null $status
     *
     * @return JsonResponse
     */
    public function customValidationFailed($errors = null, $status = null): JsonResponse
    {
        $status = $status ?: ApiCodes::VALIDATION_FAILED;

        $json = $this->responseToJson('Gabim', $status, $errors);

        return response()->json($json, 422);
    }

    /**
     * @param string|null $message
     * @param int|null $status
     * @param null $data
     *
     * @return JsonResponse
     */
    public function singleValidationFailed($message = null, $status = null, $data = [], $httpStatusCode = 422): JsonResponse
    {
        $status = $status ?: ApiCodes::VALIDATION_FAILED;

        $json = $this->responseToJson($message, $status, $data);

        return response()->json($json, $httpStatusCode);
    }

    /**
     * @param string|null $message
     * @param int|null $status
     *
     * @return JsonResponse
     */
    public function resourceExists($message = null, $status = null, $httpStatusCode = 409): JsonResponse
    {
        $message = $message ?: 'Rekordi ekziston';
        $status = $status ?: ApiCodes::RESOURCE_EXISTS;

        $json = $this->responseToJson($message, $status, []);

        return response()->json($json, $httpStatusCode);
    }

    /**
     * @param $message string message returned in case of bad request
     *
     * @return JsonResponse
     */
    public function badRequest($message): JsonResponse
    {
        return response()->json([$message], 400);
    }

    /**
     * @param $message string message notifying the caller that the handling of this type of request has not been implemented yet
     *
     * @return JsonResponse
     */
    public function notImplemented($message): JsonResponse
    {
        return response()->json([$message], 501);
    }

    /**
     * @param string|null $message
     * @param int|null $status
     * @param int $httpStatusCode
     * @return JsonResponse
     */
    public function generalError(
        ?string $message = Messages::GENERAL_ERROR,
        int     $status = ApiCodes::GENERAL_ERROR,
        int     $httpStatusCode = HttpStatusCodes::INTERNAL_SERVER_ERROR
    ): JsonResponse {
        $message = $message ?: 'Gabim';
        $status = $status ?: ApiCodes::GENERAL_ERROR;

        $json = $this->responseToJson($message, $status, []);

        return response()->json($json, $httpStatusCode);
    }

    /**
     * @param string|null $message
     * @param string|null $status
     *
     * @return JsonResponse
     */
    public function resourceInactive($message = null, $status = null): JsonResponse
    {
        $message = $message ?: 'Rekordi është jo-aktiv';
        $status = $status ?: ApiCodes::RESOURCE_INACTIVE;

        $json = $this->responseToJson($message, $status, []);

        return response()->json($json, 422);
    }

    /**
     * @param $errors
     *
     * @return string
     */
    public function transformValidationMessages($errors): string
    {
        $errorMessages = '';
        if (is_string($errors)) {
            return $errors;
        }
        $errorsList = is_array($errors) ? $errors : $errors->all();
        foreach ($errorsList as $error) {
            $errorMessages .= $error . "\n";
        }

        return $errorMessages;
    }

    /**
     * @param $errors
     *
     * @return string
     */
    public function transformCustomValidationMessages($errors): string
    {
        $errorMessages = '';

        foreach ($errors as $error => $err) {
            $errorMessages .= $error . "\n";
        }

        return $errorMessages;
    }

    /**
     * @param string|null $message
     * @param int|null $status
     *
     * @return JsonResponse
     */
    public function permissionDenied($message = null, $status = null): JsonResponse
    {
        $message = $message ?: 'Nuk keni të drejta për të kryer veprimin';
        $status = $status ?: ApiCodes::RESOURCE_INACTIVE;

        $json = $this->responseToJson($message, $status, []);

        return response()->json($json, 403);
    }

    public function fetchResults($query)
    {
        $page = request()->get('page') ?? 1;
        $pageSize = request()->get('page-size') ?? 25;

        $query = $this->prepareSortingQuery($query);

        if ((request()->has('no-pagination') && (bool) request()->get('no-pagination')) || request()->filled('export')) {
            $results = $query->get();
        } else {
            $results = $query->paginate($pageSize);
        }

        return $results;
    }

    public function prepareSortingQuery($query)
    {
        if (request()->has('sort-by') && request()->get('sort-by') !== 'null' && request()->get('sort-by') !== 'undefined') {
            $sortBy = request()->get('sort-by');
            $direction = request()->get('sortDirection');
            $query->orderBy($sortBy, $direction);
        }

        return $query;
    }

    private function getJsonResponse($resourceItems, $resourceClass): JsonResponse
    {
        if (!$resourceItems instanceof Collection) {
            $resourceItems = collect([$resourceItems]);
        }
        $data = $resourceClass::collection($resourceItems);

        $json = $this->responseToJson('Veprimi u krye me sukses!', ApiCodes::SUCCESS, $data);

        return response()->json($json);
    }

    private function shouldCreateNew($request): bool
    {
        return $request->reason == true || !$request->id || $request->id == '0';
    }


    /**
     * @throws ApplicationTimeNotActiveException
     *
     * @return bool
     */

    /**
     * @param null $message
     * @param null $status
     * @param array $data
     * @param int $httpStatusCode
     *
     * @return JsonResponse
     */
    public function restApiResponse($message = null, $status = null, $data = [], $httpStatusCode = 200): JsonResponse
    {
        $message = $message ?: 'Rekordi është jo-aktiv';
        $status = $status ?: ApiCodes::RESOURCE_INACTIVE;

        $json = $this->responseToJson($message, $status, $data);

        return response()->json($json, $httpStatusCode);
    }
}
