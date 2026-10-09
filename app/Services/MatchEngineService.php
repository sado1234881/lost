<?php

namespace App\Services;

use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\Notification;

class MatchEngineService
{
    /**
     * Find potential matching Found items for a given Lost Item.
     */
    public static function findMatchesForLostItem(LostItem $lostItem)
    {
        $query = FoundItem::where('category_id', $lostItem->category_id)
            ->whereIn('approval_status', ['approved'])
            ->whereIn('status', ['reported', 'active']);

        if (!empty($lostItem->color)) {
            $query->where('color', 'like', "%{$lostItem->color}%");
        }

        // Keywords match from item name or description
        $keywords = explode(' ', preg_replace('/[^\w\s]/', '', $lostItem->item_name));
        $keywords = array_filter($keywords, fn($w) => strlen($w) > 3);

        if (!empty($keywords)) {
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('item_name', 'like', "%{$word}%")
                      ->orWhere('description', 'like', "%{$word}%");
                }
            });
        }

        return $query->latest()->get();
    }

    /**
     * Find potential matching Lost items for a given Found Item.
     */
    public static function findMatchesForFoundItem(FoundItem $foundItem)
    {
        $query = LostItem::where('category_id', $foundItem->category_id)
            ->whereIn('approval_status', ['approved'])
            ->whereIn('status', ['active']);

        if (!empty($foundItem->color)) {
            $query->where('color', 'like', "%{$foundItem->color}%");
        }

        $keywords = explode(' ', preg_replace('/[^\w\s]/', '', $foundItem->item_name));
        $keywords = array_filter($keywords, fn($w) => strlen($w) > 3);

        if (!empty($keywords)) {
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('item_name', 'like', "%{$word}%")
                      ->orWhere('description', 'like', "%{$word}%");
                }
            });
        }

        return $query->latest()->get();
    }

    /**
     * Process auto-matching for a newly approved item.
     */
    public static function processAutoMatching($item): void
    {
        if ($item instanceof LostItem) {
            $matches = self::findMatchesForLostItem($item);
            foreach ($matches as $foundMatch) {
                // Notify owner of lost item via In-System Alert
                Notification::create([
                    'user_id' => $item->user_id,
                    'title' => 'Potential Match Discovered!',
                    'message' => 'A found item matching your lost "' . $item->item_name . '" has been logged by SJUT Security.',
                    'type' => 'match',
                    'action_url' => route('found-items.show', $foundMatch->id),
                    'entity_type' => 'found_item',
                    'entity_id' => $foundMatch->id,
                ]);
            }
        } elseif ($item instanceof FoundItem) {
            $matches = self::findMatchesForFoundItem($item);
            foreach ($matches as $lostMatch) {
                // Notify owner of lost item via In-System Alert
                Notification::create([
                    'user_id' => $lostMatch->user_id,
                    'title' => 'Potential Match Discovered!',
                    'message' => 'A newly reported item matching your lost "' . $lostMatch->item_name . '" is now available for review.',
                    'type' => 'match',
                    'action_url' => route('found-items.show', $item->id),
                    'entity_type' => 'found_item',
                    'entity_id' => $item->id,
                ]);
            }
        }
    }
}
