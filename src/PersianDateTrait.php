<?php namespace Bigsinoos\JEloquent;

use Illuminate\Support\Str;
use Miladr\Jalali\jDate;

trait PersianDateTrait {
    
    /**
     * Indicates that dates are appendded by default or no
     * 
     * @return bool
     */
    protected $appendsJalaliByDefault = true;
    
    /**
     * Prefix for getting jalali dates "{prefix}_{date_attribute}_{suffix}
     *
     * @var string
     */
    protected  $jalaliPrefix = "jalali_";

    /**
     * Date format for jalali dates
     *
     * @var string
     */
    protected $jalaliDateFormat = 'y/m/d';


    /**
     * Convert Gregorian date to Jalali date
     *
     * @param string $what
     * @param string $format
     */
    public function convertToPersian($what = 'created_at', $format = null)
    {
        $format = is_null($format) ? $this->getJalaliFormat() : $format;
        
        if (is_null($this->$what)) {
            return null;
        }
        
        return jDate::forge($this->$what)->format($format);
    }

    /**
     * Add persian dates to model's toJson, toArray, __toString
     *
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();
        
        if (!$this->appendsJalaliByDefault()) {
            return $arr;
        }
        
        foreach($this->getDates() as $date){
            $key = "{$this->getJalaliPrefix()}{$date}";
            $arr[$key] = $this->convertToPersian($date);
        }
        return $arr;
    }

    /**
     * Get jalali dates based on model
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        $prefix = $this->getJalaliPrefix();
        if (Str::startsWith($key, $prefix)){
            $parentKey = str_replace($prefix, '', $key);
            $parent = $this->$parentKey;
            return is_null($parent) ? null : $this->convertToPersian($parentKey);
        }
        return parent::__get($key);
    }

    /**
     * Get jalali prefix for accessing dates
     *
     * @return string
     */
    protected function getJalaliPrefix()
    {
        return $this->jalaliPrefix;
    }

    /**
     * Get jalali format
     *
     * @return string
     */
    protected function getJalaliFormat()
    {
        return $this->jalaliDateFormat;
    }

    /**
     * Set jalali format
     *
     * @return $this
     */
    public function setJalaliFormat($format)
    {
        $this->jalaliDateFormat = $format; return $this;
    }
    
    /**
     * Appends dates by default
     * 
     * @return bool
     */
    protected function appendsJalaliByDefault()
    {
        return $this->appendsJalaliByDefault;
    }
    
    public function doNotAppendJalaliByDefault()
    {
        $this->appendsJalaliByDefault = false;
        return $this;
    }
} 
