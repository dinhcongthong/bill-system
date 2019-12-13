<?php
namespace App\Http\Models\Loggable;
 
/**
 * Loggable Trait
 */
use Models\Loggable\Log;
use \Log as ErrorLog;
 
trait LoggableTrait
{
    protected static $log_model;
    public static $_model;
    public static $_fillable;
 
    public static function logger()
    {
        // Get new log
        $log = new Log;
        // Create new instance, so we can access non-static properties
        $model = new static;
 
        static::$_fillable = $model->log_fillable;
 
        // Get log column names
        if (is_null($model->log_fillable)) {
            static::$_fillable = [
                'record_id' => 'record_id',
                'changed_by' => 'changed_by',
                'event' => 'event',
                'description' => 'description'
            ];
        }
 
        $fillable = array_flatten(static::$_fillable);
 
        $log->fill($fillable);
        $log->setTable($model->table.'_log');
 
        $model->log_model = $log;
        static::$log_model = $log;
        static::$_model = $model;
    }
 
    public function log($changed_by, $event, $description)
    {
        static::logger();
        // Get the log model
        $log = $this->getLogModel();
 
        // Fill record
        $log->{static::$_fillable['record_id']} = $this->getKey();
        $log->{static::$_fillable['changed_by']} = $changed_by;
        $log->{static::$_fillable['event']} = $event;
        $log->{static::$_fillable['description']} = $description;
 
        // Save
        $log->save();
 
        return $log;
    }
 
    public function logs()
    {
        static::logger();
        $model = $this->getModel();
        $log = $this->getLogModel();
        $foreign = $model->log_fillable['record_id'];
        $local = $model->getKeyName();
     
        return $this->hasMany($log, $foreign, $local);
    }
 
    /**
     * These methods are for setting and getting attributes non-statically
     */
    public function setModel($model)
    {
        static::$_model = $model;
    }
 
    public function getModel()
    {
        return static::$_model;
    }
 
    public function setLogModel($log_model)
    {
        static::$log_model = $log_model;
    }
 
    public function getLogModel()
    {
        return static::$log_model;
    }
 
    public function setFillable($fillable)
    {
        static::$_fillable = $fillable;
    }
 
    public function getFillable()
    {
        return static::$_fillable;
    }
}