<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';
    protected $primaryKey = 'evaluation_id';

    protected $fillable = [
        'evaluator_id',
        'evaluated_id',
        'package_id',
        'transaction_id',
        'overall_rating',
        'communication_rating',
        'punctuality_rating',
        'care_rating',
        'professionalism_rating',
        'comment',
        'pros',
        'cons',
        'would_recommend',
        'is_anonymous',
        'is_verified',
        'is_flagged',
        'flag_reason',
    ];

    protected $casts = [
        'overall_rating' => 'decimal:1',
        'communication_rating' => 'integer',
        'punctuality_rating' => 'integer',
        'care_rating' => 'integer',
        'professionalism_rating' => 'integer',
        'would_recommend' => 'boolean',
        'is_anonymous' => 'boolean',
        'is_verified' => 'boolean',
        'is_flagged' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who made the evaluation.
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id', 'user_id');
    }

    /**
     * Get the user who was evaluated.
     */
    public function evaluated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_id', 'user_id');
    }

    /**
     * Get the package related to this evaluation.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }

    /**
     * Get the transaction related to this evaluation.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Scope for verified evaluations.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for flagged evaluations.
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /**
     * Calculate average rating from individual ratings.
     */
    public function getAverageRatingAttribute()
    {
        $ratings = [
            $this->communication_rating,
            $this->punctuality_rating,
            $this->care_rating,
            $this->professionalism_rating
        ];
        
        $validRatings = array_filter($ratings, function($rating) {
            return !is_null($rating) && $rating > 0;
        });
        
        return count($validRatings) > 0 ? round(array_sum($validRatings) / count($validRatings), 1) : 0;
    }
}