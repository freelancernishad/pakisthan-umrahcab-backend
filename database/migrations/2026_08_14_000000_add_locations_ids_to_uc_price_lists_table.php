<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('uc_price_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('pickup_id')->nullable()->after('route');
            $table->unsignedBigInteger('destination_id')->nullable()->after('pickup_id');

            $table->foreign('pickup_id')->references('id')->on('uc_locations')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('uc_locations')->onDelete('cascade');
        });

        // Populate existing routes
        $routes = DB::table('uc_price_lists')->get();
        foreach ($routes as $r) {
            $parts = preg_split('/\s+to\s+/i', $r->route);
            if (count($parts) === 2) {
                $pickupName = trim($parts[0]);
                $destName = trim($parts[1]);

                $pId = $this->resolveLocationId($pickupName);
                $dId = $this->resolveLocationId($destName);

                if ($pId && $dId) {
                    DB::table('uc_price_lists')->where('id', $r->id)->update([
                        'pickup_id' => $pId,
                        'destination_id' => $dId,
                    ]);
                }
            }
        }
    }

    private function resolveLocationId(string $name): ?int
    {
        $lower = strtolower($name);

        // Try exact match first
        $exact = DB::table('uc_locations')->where('name', $name)->first();
        if ($exact) {
            return $exact->id;
        }

        // Try loose match
        $loose = DB::table('uc_locations')->where('name', 'like', "%{$name}%")->first();
        if ($loose) {
            return $loose->id;
        }

        // Fallbacks for known names
        if (str_contains($lower, 'jaddah') || str_contains($lower, 'jeddah')) {
            if (str_contains($lower, 'airport')) {
                if (str_contains($lower, 'north')) {
                    return 2; // North Terminal
                }
                return 1; // Terminal 1
            }
            if (str_contains($lower, 'hotel')) {
                return 5; // Jeddah Hotel
            }
            if (str_contains($lower, 'station')) {
                return 10; // Jeddah Station
            }
            return 1; // Default to Jeddah Airport Terminal 1
        }

        if (str_contains($lower, 'makkah') || str_contains($lower, 'mecca')) {
            if (str_contains($lower, 'haram')) {
                return 6;
            }
            if (str_contains($lower, 'hotel')) {
                return 3;
            }
            if (str_contains($lower, 'station')) {
                return 8;
            }
            return 6; // Default to Makkah Haram
        }

        if (str_contains($lower, 'madinah') || str_contains($lower, 'medina')) {
            if (str_contains($lower, 'haram')) {
                return 7;
            }
            if (str_contains($lower, 'hotel')) {
                return 4;
            }
            if (str_contains($lower, 'station')) {
                return 9;
            }
            return 7; // Default to Madinah Haram
        }

        if (str_contains($lower, 'taif')) {
            return 11;
        }

        if (str_contains($lower, 'yanbu')) {
            return 12;
        }

        return null;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_price_lists', function (Blueprint $table) {
            $table->dropForeign(['pickup_id']);
            $table->dropForeign(['destination_id']);
            $table->dropColumn(['pickup_id', 'destination_id']);
        });
    }
};
