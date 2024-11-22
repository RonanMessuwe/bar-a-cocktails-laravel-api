<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderEvent;
use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrdersByTableRequest;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

/**
* @OA\Info(
 *  version="1.0.0",
 *  title="RESTful API Bar à cocktails",
 *  description="API pour manipuler des commandes et donner la possibilité à un client de suivre le statut de sa commande."
 * )
 *
 *
 *
 *
 */
class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     *
     *  @OA\Server(
     *      url="http://localhost:8000/api/",
     *      description="RESTful API Server"
     * )
     *
     * @OA\Tag(
     *  name="Order status tracking",
     *  description="Allows you to track statuses of orders. [public]."
     * )
     *
     * @OA\Tag(
     *  name="Orders",
     *  description="Allows you to manage orders [admin]."
     * )
     *
      * @OA\SecurityScheme(
    *      type="http",
    *      name="bearer",
    *      securityScheme="bearerAuth",
    *      scheme="bearer",
    * )
     *
     *  @OA\Get(
     *      path="/admin/orders",
     *      tags={"Orders"},
     *      summary="Get all orders",
     *      description="Returns all orders.",
     *      @OA\Response(response="200", description="Success"),
     *      security={ {"bearerAuth": {}} }
     * ),
     *
     */
    public function index()
    {
        return new OrderResource(Order::all());
    }

    /**
     * Store a newly created order in storage.
     *
     * @OA\Post(
     *      path="/admin/orders",
     *      tags={"Orders"},
     *      summary="Create an order",
     *      description="Creates an new order.",
     *      @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="table",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     default="pending",
     *                     property="status",
     *                     type="string",
     *                     enum={"pending", "selecting_ingredients", "shaking", "adding_ice_cubes",  "ready"},
     *                 ),
     *
     *                 example={"table": "T3", "status": "pending"}
     *             )
     *         )
     *      ),
     *      @OA\Response(response="201", description="Created"),
     *      security={ {"bearerAuth": {}} },
     * ),
     */
    public function store(StoreOrderRequest $request)
    {
        $newOrder = Order::create($request->only(['table']))->fresh();

        return response()->json(new OrderResource($newOrder), HttpFoundationResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     *  @OA\Get(
     *      path="/admin/orders/{order_id}",
     *      tags={"Orders"},
     *      summary="Get this order",
     *      description="Returns this order.",
     *      @OA\Parameter(
     *          name="order_id",
     *          description="Order ID [ULID]",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string", format="ULID"),
     *          @OA\Examples(example="ULID", value="01JD88EFN4N493AM4V00Z96TBR", summary="Random ULID"),
     *      ),
     *      @OA\Response(response="200", description="Success"),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified order in storage.
     *
     * * @OA\Patch(
     *      path="/admin/orders/{order_id}",
     *      tags={"Orders"},
     *      summary="Update this order",
     *      description="Update status (or table) of this order.",
     *      @OA\Parameter(
     *          name="order_id",
     *          description="Order ID [ULID]",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string", format="ULID"),
     *          @OA\Examples(example="ULID", value="01JD88EFN4N493AM4V00Z96TBR", summary="Random ULID"),
     *      ),
     *      @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="table",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     default="pending",
     *                     property="status",
     *                     type="string",
     *                     enum={"pending", "selecting_ingredients", "shaking", "adding_ice_cubes",  "ready"},
     *                 ),
     *
     *                 example={"table": "T3", "status": "pending"}
     *             )
     *         )
     *      ),
     *      @OA\Response(response="200", description="Success"),
     *      security={ {"bearerAuth": {}} },
     *
     * )
     */
    public function update(Order $order, UpdateOrderRequest $request)
    {
        $orderStatusUpdated = $request->has('status') && $request->get('status') != $order->status;

        $inputs = $request->only(['status', 'table']);
        $order->update($inputs);

        /** Dispatch an event if the status has been updated. */
        OrderStatusUpdated::dispatchIf($orderStatusUpdated, $order);

        return new OrderResource($order);
    }

    /**
     * Remove the specified order from storage.
     *
     *  @OA\Delete(
     *      path="/admin/orders/{order_id}",
     *      tags={"Orders"},
     *      summary="Delete this order",
     *      description="Delete this order.",
     *      @OA\Parameter(
     *          name="order_id",
     *          description="Order ID [ULID]",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string", format="ULID"),
     *          @OA\Examples(example="ULID", value="01JD88EFN4N493AM4V00Z96TBR", summary="Random ULID"),
     *      ),
     *      @OA\Response(response="204", description="No Content"),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->noContent();
    }

    /**
     * Display a listing of orders by tableCode.
     * This "endpoint method" is public and used
     * by a customer to display the status of their orders.
     *
     * @OA\Get(
     *      path="/orders",
     *      tags={"Order status tracking"},
     *      summary="Orders by table code.",
     *      description="Returns all orders of this table.",
     *      @OA\Parameter(
     *          name="table",
     *          in="query",
     *          description="Table code in query.",
     *          required=true,
     *          @OA\Examples(example="T1", value="T1", summary="Table code: T1"),
     *          @OA\Examples(example="T3", value="T3", summary="Table code: T3"),
     *      ),
     *      @OA\Response(response="200", description="Success")
     * )
     */
    public function ordersByTableCode(OrdersByTableRequest $request)
    {
        return new OrderResource(Order::where('table', $request->only('table'))->orderByDesc('created_at')->get());
    }
}
