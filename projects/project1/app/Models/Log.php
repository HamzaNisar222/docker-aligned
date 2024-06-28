<?php

namespace App\Models;

use MongoDB\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_type',
        'user_id',
        'method',
        'url',
        'headers',
        'body',
        'response_status',
        'response_content',
        'level',
        'ip',
    ];

    protected $casts = [
        'headers' => 'array',
        'body' => 'array',
    ];

    protected static $collection;

    // Method to initialize MongoDB connection
    protected static function initializeMongoDB()
    {
        if (is_null(self::$collection)) {
            $client = new Client(env('MONGO_DB_CONNECTION'));
            self::$collection = $client->selectCollection(env('MONGO_DB_DATABASE'), env('LOG_COLLECTION'));
        }
    }

    // save the generated logs to the mongoDB Collection
    public static function logToMongoDB($logData)
    {
        self::initializeMongoDB();

        try {
            self::$collection->insertOne($logData);
        } catch (\Exception $e) {
            Log::error('Failed to log to MongoDB: ' . $e->getMessage());
        }
    }
}
