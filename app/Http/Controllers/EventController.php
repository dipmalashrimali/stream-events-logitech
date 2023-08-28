<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    const PER_PAGE_NO = 100; // Set per page record limit

    /**
     * Fetch events data from multiple sources (Followers, Donations, Subscribers, Merch Sales)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Get authenticated user
            $user = Auth::user();

            /* Start : Update data with login user as we want some dummy data*/
            Donation::where('login_id', '!=', $user->id)->update(['login_id' => $user->id]);
            Follower::where('login_id', '!=', $user->id)->update(['login_id' => $user->id]);
            Subscriber::where('login_id', '!=', $user->id)->update(['login_id' => $user->id]);
            MerchSale::where('login_id', '!=', $user->id)->update(['login_id' => $user->id]);
            /* End : Update data with login user as we want some dummy data*/

            // Construct a subquery to fetch event data from multiple sources
            $subquery = DB::table('followers')
                ->where('login_id', $user->id)
                ->selectRaw(
                // Concatenate event description based on type
                    "
                    CONCAT((SELECT name FROM users WHERE id = followers.user_id), ' followed you!' ) as event,
                    id, status, '1' as type, created_at
                    "
                )
                ->union(function ($query) use ($user) {
                    $query
                        ->selectRaw(
                            "
                            CONCAT((SELECT name FROM users WHERE id = subscribers.user_id), ' (Tier', tier ,')', ' subscribed to you!' ) as event,
                            id, status, '2' as type, created_at
                            "
                        )
                        ->from('subscribers')
                        ->where('login_id', $user->id);
                })
                ->union(function ($query) use ($user) {
                    $query
                        ->selectRaw(
                            "
                            CONCAT((SELECT name FROM users WHERE id = donations.user_id), ' donated ', amount, ' ', currency, ' to you!' ) as event,
                            id, status, '3' as type, created_at
                            "
                        )
                        ->from('donations')
                        ->where('login_id', $user->id);
                })
                ->union(function ($query) use ($user) {
                    $query
                        ->selectRaw(
                            "
                            CONCAT((SELECT name FROM users WHERE id = merch_sales.user_id), ' bought some fancy pants from you for ', amount*price, ' USD!' ) as event,
                            id, status , '4' as type, created_at
                            "
                        )
                        ->from('merch_sales')
                        ->where('login_id', $user->id);
                });

            // Execute the subquery and paginate the results
            $eventsData = DB::table(DB::raw("({$subquery->toSql()}) as subquery"))
                ->mergeBindings($subquery)
                ->orderBy('created_at')
                ->paginate(self::PER_PAGE_NO); // Adjust the per-page limit as needed

            return response()->json($eventsData);
        } catch (\Throwable $e) {dd($e);
            return response()->json(['message' => 'Oops unexpected error, Please try again later or contact support.'], $e->status ?? 500);
        }
    }

    /**
     * Update event read/unread status
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            // Update event status based on type
            if ($request->type == 1) {
                Follower::where('id', $id)->update(['status' => $request->status]);
            }
            if ($request->type == 2) {
                Subscriber::where('id', $id)->update(['status' => $request->status]);
            }
            if ($request->type == 3) {
                Donation::where('id', $id)->update(['status' => $request->status]);
            }
            if ($request->type == 4) {
                MerchSale::where('id', $id)->update(['status' => $request->status]);
            }
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Oops unexpected error, Please try again later or contact support.'], $e->status ?? 500);
        }
    }

    /**
     * Fetch Dashboard/Event Summary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventsSummary()
    {
        try {
            // Define tier-based price array
            $tierPriceArray = [1 => 5, 2 => 10, 3 => 15];

            // Calculate the start date for the past 30 days
            $startDate = Carbon::now()->subDays(30);

            // Calculate total donation revenue
            $totalDonationRevenue = Donation::where('created_at', '>=', $startDate)
                ->sum('amount');

            // Calculate total subscription revenue
            $totalSubscriptionRevenue = Subscriber::where('created_at', '>=', $startDate)
                ->selectRaw('SUM(CASE WHEN tier = 1 THEN ? WHEN tier = 2 THEN ? WHEN tier = 3 THEN ? ELSE 0 END) AS total_revenue', [
                    $tierPriceArray[1] ?? 0,
                    $tierPriceArray[2] ?? 0,
                    $tierPriceArray[3] ?? 0,
                ])
                ->first()
                ->total_revenue;

            // Calculate total merch revenue
            $totalMerchRevenue = MerchSale::where('created_at', '>=', $startDate)
                ->sum('price');

            // Calculate overall total revenue
            $overallTotalRevenue = $totalDonationRevenue + $totalSubscriptionRevenue + $totalMerchRevenue;
            $overallTotalRevenue = round($overallTotalRevenue, 2);

            // Get total followers count
            $totalFollowers = Follower::where('created_at', '>=', $startDate)
                ->count();

            // Retrieve the top 3 items with the highest revenue in the past 30 days
            $topItems = MerchSale::selectRaw('item_name, amount * price AS total_sales')
                ->where('created_at', '>=', $startDate)
                ->orderByDesc('total_sales')
                ->take(3)
                ->get();

            // Prepare and return summary data
            $data = [
                'totalRevenue' => "$overallTotalRevenue USD",
                'totalFollowers' => $totalFollowers,
                'top3Items' => $topItems
            ];
            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Oops unexpected error, Please try again later or contact support.'], $e->status ?? 500);
        }
    }
}
