<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class BookController extends ApiController
{

    /**
     * Display a listing of the books or search by name.
     * @param Request $request
     * @queryParam term string Search query parameter
     * @queryParam per_page int Items per page
     * @apiResourceCollection App\Http\Resources\BookResource
     * @apiResourceModel App\Modules\Book paginate=20
     * @return AnonymousResourceCollection
     * @throws Throwable
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $per_page = $request->get('per_page') ?: 20;
        $term = $request->get('term') ?: '';
        $books = Book::where('name', 'LIKE', "%$term%")
            ->orWhere('country', 'LIKE', "%$term%")
            ->orWhere('publisher', 'LIKE', "%$term%")
            ->orWhere('release_date', 'LIKE', "%$term%")
            ->latest();
        return BookResource::collection($books->paginate($per_page));
    }

    /**
     * Create a new book
     * @param StoreBookRequest $request
     * @apiResource App\Http\Resources\BookResource
     * @apiResourceModel App\Modules\Book
     * @return JsonResponse|BookResource
     * @throws Throwable
     */
    public function store(StoreBookRequest $request): JsonResponse|BookResource
    {
        try {
            $book = Book::create($request->all());
            return $this->respondCreated("success","The book ".$book->name." was updated successfully",new BookResource($book));
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    /**
     * Show a specified book.
     * @param int $id
     * @urlParam id int required The ID of the book
     * @apiResource App\Http\Resources\BookResource
     * @apiResourceModel App\Modules\Book
     * @return JsonResponse|BookResource
     * @throws Throwable
     */
    public function show(int $id): JsonResponse|BookResource
    {
        $book = Book::find($id);
        throw_if(!$book, NotFoundHttpException::class, 'Book not found');
        return new BookResource($book);
    }

    /**
     * Update the specified book
     * @param UpdateBookRequest $request
     * @param int $id
     * @urlParam id int required The ID of the book
     * @apiResource App\Http\Resources\BookResource
     * @apiResourceModel App\Modules\Book
     * @return JsonResponse|BookResource
     * @throws Throwable
     */
    public function update(UpdateBookRequest $request, int $id): JsonResponse|BookResource
    {
        $book = Book::find($id);
        throw_if(!$book, NotFoundHttpException::class, 'Book not found');
        try {
            $book->update($request->all());
            return $this->respondSuccess(HttpResponse::HTTP_OK, "success","The book ".$book->name." was updated successfully",new BookResource($book));
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    /**
     * Delete a book
     * @param int $id
     * @urlParam id int required The ID of the book
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(int $id): JsonResponse
    {
        $book = Book::find($id);
        throw_if(! $book, NotFoundHttpException::class, 'Book not found');
        $book->delete();
        return $this->respondNotContent("success","The book ".$book->name." was deleted successfully");
    }
}
