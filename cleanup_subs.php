$user = \App\Models\User::where('email', 'freelancernishad123@gmail.com')->first();
if ($user) {
    // Keep the latest active one with a valid amount
    $validSub = $user->planSubscriptions()
        ->where('status', 'active')
        ->where('final_amount', '>', 0)
        ->latest('start_date')
        ->first();

    if ($validSub) {
        $deleted = $user->planSubscriptions()
            ->where('status', 'active')
            ->where('id', '!=', $validSub->id)
            ->delete();
        echo "Deleted $deleted duplicate/invalid active subscriptions.\n";
        echo "Kept subscription ID: " . $validSub->id . " with amount: " . $validSub->price . "\n";
        
        // Ensure final_amount is set if missing (for legacy or backfilled ones)
        if ($validSub->final_amount <= 0 && $validSub->original_amount > 0) {
             $validSub->final_amount = $validSub->original_amount;
             $validSub->save();
             echo "Updated final_amount for kept subscription.\n";
        }
    } else {
        echo "No valid subscription found to keep.\n";
    }
}
