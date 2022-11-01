<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Contracts\Pagination\Paginator;


class ApiController extends Controller
{
    protected int $statusCode = HttpResponse::HTTP_OK;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondNotFound(string $message = 'Not Found!'): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondNotContent($status_code = HttpResponse::HTTP_NO_CONTENT, $status, $message, $data = []): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_NO_CONTENT)->respond([
            'status_code' => $status_code,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondInternalError(string $message = 'Internal Server Error!'): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondUnprocessed($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondCreated($status_code = HttpResponse::HTTP_CREATED, $status, $data): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_CREATED)->respond([
            'status_code' => $status_code,
            'status' => $status,
            'data' => $data,
        ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondAccepted($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_ACCEPTED)->respond([
            'message' => $message
        ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondSuccess($status_code = HttpResponse::HTTP_OK, $status, $message, $data): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_OK)->respond([
            'status_code' => $status_code,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondSuccessWithoutMessage($status_code = HttpResponse::HTTP_OK, $status, $data): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_OK)->respond([
            'status_code' => $status_code,
            'status' => $status,
            'data' => $data,
        ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondUnauthorized($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_UNAUTHORIZED)->respond([
            'message' => $message
        ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondNotAllowed($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)->respond([
            'message' => $message
        ]);
    }

    /**
     * @param Paginator $paginator
     * @param $data
     * @return JsonResponse
     */
    public function respondWithPagination(Paginator $paginator, $data): JsonResponse
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $paginator->total(),
                'total_pages' => ceil($paginator->total() / $paginator->perPage()),
                'current_page' => $paginator->currentPage(),
                'limit' => $paginator->perPage()
            ]
        ]);
        return $this->respond($data);
    }

    /**
     * @param $data
     * @param array $header
     * @return JsonResponse
     */
    public function respond($data, array $header = []): JsonResponse
    {
        return Response::json($data, $this->getStatusCode(), $header);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function respondWithError($message): JsonResponse
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }
}
