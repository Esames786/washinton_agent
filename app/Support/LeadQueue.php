<?php

namespace App\Support;

use Closure;
use Illuminate\Database\Eloquent\Model;

/**
 * An agent's lead queue on Autos Approach and Dealer Master (client, 31 Oct 2026: "assigned 50
 * leads, the agent shows 500").
 *
 * Both screens used to hand out the number PER STATE (and per category on Autos Approach), and a
 * second Save added another batch on top of the first, so agents piled up thousands of leads from
 * states they were no longer assigned. Saving now sets the queue to exactly that number from the
 * chosen pool: leads outside it go back, any excess goes back, the shortfall is topped up from
 * unassigned leads. Leads the agent keeps stay with them (lowest ids first), so a re-save does not
 * swap out what they are halfway through calling.
 *
 * Nothing worked is taken away: saving a call note already returns a lead to the pool, so every
 * lead an agent holds is one still waiting to be called.
 */
class LeadQueue
{
    /**
     * @param  class-string<Model>  $model  UsedAndNewCarDealers or DealerMaster (both use user_id, 0 = unassigned)
     * @param  Closure  $pool  narrows a query to the chosen states / categories / phone filter
     * @return array{released:int, added:int, holding:int}
     */
    public static function set(string $model, int $agentId, int $limit, Closure $pool): array
    {
        $limit = max(0, $limit);

        $held = $model::where('user_id', $agentId)->pluck('id')->all();
        $keep = $model::where('user_id', $agentId)->where($pool)->orderBy('id')->limit($limit)->pluck('id')->all();

        $released = 0;
        foreach (array_chunk(array_values(array_diff($held, $keep)), 1000) as $ids) {
            $released += $model::whereIn('id', $ids)->where('user_id', $agentId)->update(['user_id' => 0]);
        }

        $added = 0;
        if (($need = $limit - count($keep)) > 0) {
            $added = $model::where('user_id', 0)->where($pool)->orderBy('id')->limit($need)->update(['user_id' => $agentId]);
        }

        return ['released' => $released, 'added' => $added, 'holding' => count($keep) + $added];
    }
}
