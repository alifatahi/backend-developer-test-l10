<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        event(new CommentWritten($user));
        die("----");
        // all achievements that user earned
        $unlockedAchievements = $user->achievements()->pluck('title');
        // next available achievements in each category
        $nextAvailableAchievements = $user->nextAvailableAchievements()->pluck('title');

        $currentBadge = $user->currentBadge();

        $nextBadge = $user->nextAvailableBadge();
        $achievementsCount = $unlockedAchievements->count();
        // calculate the remaing achievements count for the next badge
        $remaingToUnlockNextBadge = $nextBadge ? $nextBadge->target - $achievementsCount : 0;

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements ?? [],
            'next_available_achievements' => $nextAvailableAchievements ?? [],
            'current_badge' => $currentBadge->name ?? '',
            'next_badge' => $nextBadge->name ?? '',
            'remaing_to_unlock_next_badge' => $remaingToUnlockNextBadge,
        ]);
    }
}
